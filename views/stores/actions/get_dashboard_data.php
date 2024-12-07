<?php
session_start();
include '../../../includes/db_connect.php';

if (!isset($_SESSION['store_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit();
}

header('Content-Type: application/json');

$store_id = $_SESSION['store_id'];

$period = $_GET['period'] ?? 'month';

try {
  // Get total games, sets and cards
  $query = "SELECT 
    (SELECT COUNT(*) FROM games) as total_games,
    (SELECT COUNT(*) FROM sets) as total_sets,
    (SELECT COUNT(*) FROM cards) as total_cards";

  $stmt = $conn->prepare($query);
  $stmt->execute();
  $stats = $stmt->fetch(PDO::FETCH_ASSOC);

  // Get orders stats and earnings with period
  $query = "SELECT 
    COUNT(DISTINCT oi.order_id) as total_orders,
    SUM(t.store_amount) as total_earnings
    FROM order_items oi
    LEFT JOIN transactions t ON oi.order_id = t.order_id AND t.store_id = oi.store_id
    WHERE oi.store_id = :store_id 
    AND oi.status != 'Cancelled'
    AND oi.created_at >= :date_filter";

  $dateFilter = getDateFilter($period);
  $stmt = $conn->prepare($query);
  $stmt->execute([
    ':store_id' => $store_id,
    ':date_filter' => $dateFilter
  ]);
  $orderStats = $stmt->fetch(PDO::FETCH_ASSOC);

  // Get top 5 most expensive cards
  $query = "SELECT 
    c.name as card_name,
    cl.price,
    cl.quantity,
    g.name as game_name,
    s.name as set_name
    FROM card_listings cl
    JOIN cards c ON cl.card_id = c.id
    JOIN sets s ON c.set_id = s.id
    JOIN games g ON s.game_id = g.id
    WHERE cl.store_id = ?
    ORDER BY cl.price DESC
    LIMIT 5";

  $stmt = $conn->prepare($query);
  $stmt->execute([$store_id]);
  $topExpensiveCards = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Get top 5 cards by quantity
  $query = "SELECT 
    c.name as card_name,
    cl.price,
    cl.quantity,
    g.name as game_name,
    s.name as set_name
    FROM card_listings cl
    JOIN cards c ON cl.card_id = c.id
    JOIN sets s ON c.set_id = s.id
    JOIN games g ON s.game_id = g.id
    WHERE cl.store_id = ?
    ORDER BY cl.quantity DESC
    LIMIT 5";

  $stmt = $conn->prepare($query);
  $stmt->execute([$store_id]);
  $topQuantityCards = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Update chart query with period
  $query = "SELECT 
    DATE_FORMAT(oi.created_at, :group_by) as label,
    SUM(t.store_amount) as revenue
    FROM order_items oi
    LEFT JOIN transactions t ON oi.order_id = t.order_id AND t.store_id = oi.store_id
    WHERE oi.store_id = :store_id
    AND oi.status != 'Cancelled'
    AND oi.created_at >= :date_filter
    GROUP BY label
    ORDER BY oi.created_at ASC";

  $groupBy = getGroupBy($period);
  $stmt = $conn->prepare($query);
  $stmt->execute([
    ':group_by' => $groupBy,
    ':store_id' => $store_id,
    ':date_filter' => $dateFilter
  ]);
  $chartResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Format chart data
  $chartData = [
    'labels' => array_column($chartResults, 'label'),
    'values' => array_column($chartResults, 'revenue')
  ];

  // Get top 5 best selling cards
  $query = "SELECT 
    c.name as card_name,
    g.name as game_name,
    s.name as set_name,
    SUM(oi.quantity) as total_sold,
    cl.price
    FROM order_items oi
    JOIN card_listings cl ON oi.card_listing_id = cl.id
    JOIN cards c ON cl.card_id = c.id
    JOIN sets s ON c.set_id = s.id
    JOIN games g ON s.game_id = g.id
    WHERE oi.store_id = ?
    GROUP BY cl.id
    ORDER BY total_sold DESC
    LIMIT 5";

  $stmt = $conn->prepare($query);
  $stmt->execute([$store_id]);
  $topSellingCards = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Get top 5 customers
  $query = "SELECT 
    CONCAT(o.first_name, ' ', o.last_name) as customer_name,
    COUNT(DISTINCT o.id) as total_orders,
    COUNT(oi.id) as total_items,
    SUM(oi.price * oi.quantity) as total_spent
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE oi.store_id = ?
    AND oi.status != 'Cancelled'
    GROUP BY o.first_name, o.last_name
    ORDER BY total_spent DESC
    LIMIT 5";

  $stmt = $conn->prepare($query);
  $stmt->execute([$store_id]);
  $topCustomers = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $response = [
    'stats' => [
      'games' => (int)$stats['total_games'],
      'sets' => (int)$stats['total_sets'],
      'cards' => (int)$stats['total_cards'],
      'orders' => (int)$orderStats['total_orders'],
      'earnings' => (float)$orderStats['total_earnings']
    ],
    'topExpensiveCards' => $topExpensiveCards,
    'topQuantityCards' => $topQuantityCards,
    'topSellingCards' => $topSellingCards,
    'topCustomers' => $topCustomers,
    'chartData' => $chartData
  ];

  echo json_encode($response);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode([
    'error' => 'Database error',
    'message' => $e->getMessage()
  ]);
}

// Add helper functions
function getDateFilter($period)
{
  switch ($period) {
    case 'today':
      return date('Y-m-d');
    case 'week':
      return date('Y-m-d', strtotime('-7 days'));
    case 'year':
      return date('Y-m-d', strtotime('-1 year'));
    default: // month
      return date('Y-m-d', strtotime('-30 days'));
  }
}

function getGroupBy($period)
{
  switch ($period) {
    case 'today':
      return '%H:00';
    case 'week':
      return '%Y-%m-%d';
    case 'year':
      return '%Y-%m';
    default: // month
      return '%Y-%m-%d';
  }
}
