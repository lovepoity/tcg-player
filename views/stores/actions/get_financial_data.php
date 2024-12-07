<?php
session_start();
require_once '../../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['store_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Unauthorized']);
  exit();
}

$store_id = $_SESSION['store_id'];
$period = $_GET['period'] ?? 'month';

try {
  $stats = getFinancialStats($store_id, $period);
  $orders = getRecentOrders($store_id);
  $earnings = getEarningsHistory($store_id, $period);
  $chartData = getChartData($store_id, $period);

  echo json_encode([
    'stats' => $stats,
    'orders' => $orders,
    'earnings' => $earnings,
    'chartData' => $chartData
  ]);
} catch (PDOException $e) {
  error_log('Database Error: ' . $e->getMessage());
  http_response_code(500);
  echo json_encode(['error' => 'Database error occurred']);
  exit();
}

function getFinancialStats($store_id, $period)
{

  global $conn;

  $dateFilter = getDateFilter($period);

  $sql = "SELECT 
    COUNT(DISTINCT oi.order_id) as totalOrders,
    SUM(oi.quantity * oi.price) as totalRevenue,
    SUM(t.store_amount) as storeEarnings,
    SUM(t.tcg_amount) as tcgEarnings
    FROM order_items oi
    LEFT JOIN transactions t ON oi.order_id = t.order_id AND t.store_id = oi.store_id
    WHERE oi.store_id = :store_id 
    AND oi.created_at >= :date_filter
    AND oi.status != 'Cancelled'";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':store_id' => $store_id,
    ':date_filter' => $dateFilter
  ]);

  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  return [
    'totalOrders' => (int)$result['totalOrders'],
    'revenue' => (float)$result['totalRevenue'],
    'earnings' => (float)$result['storeEarnings'],
    'commission' => (float)$result['tcgEarnings']
  ];
}

function getRecentOrders($store_id)
{
  global $conn;

  $sql = "SELECT 
    o.id,
    o.created_at as date,
    SUM(oi.quantity) as items,
    SUM(oi.quantity * oi.price) as total,
    o.status
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE oi.store_id = :store_id
    GROUP BY o.id
    ORDER BY o.created_at DESC 
    LIMIT 7";

  $stmt = $conn->prepare($sql);
  $stmt->execute([':store_id' => $store_id]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getEarningsHistory($store_id, $period)
{
  global $conn;

  $dateFilter = getDateFilter($period);

  $sql = "SELECT 
    o.id as order_id,
    o.created_at as date,
    t.store_amount as amount,
    t.tcg_amount as commission,
    o.status
    FROM orders o
    JOIN transactions t ON o.id = t.order_id
    WHERE t.store_id = :store_id
    AND o.created_at >= :date_filter
    AND o.status != 'Cancelled'
    ORDER BY o.created_at DESC
    LIMIT 7";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':store_id' => $store_id,
    ':date_filter' => $dateFilter
  ]);

  return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

function getChartData($store_id, $period)
{
  global $conn;

  $dateFilter = getDateFilter($period);
  $groupBy = getGroupBy($period);

  $sql = "SELECT 
    DATE_FORMAT(o.created_at, :group_by) as label,
    SUM(t.store_amount) as value
    FROM orders o
    JOIN transactions t ON o.id = t.order_id
    WHERE t.store_id = :store_id
    AND o.created_at >= :date_filter 
    AND o.status != 'Cancelled'
    GROUP BY label
    ORDER BY o.created_at";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':group_by' => $groupBy,
    ':store_id' => $store_id,
    ':date_filter' => $dateFilter
  ]);

  $data = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

  return [
    'labels' => array_column($data, 'label'),
    'values' => array_column($data, 'value')
  ];
}

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
