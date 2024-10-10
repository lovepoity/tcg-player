<?php
require_once '../../includes/db_connect.php';

$stores = isset($_GET['stores']) ? $_GET['stores'] : [];
$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : null;
$set_id = isset($_GET['set_id']) ? intval($_GET['set_id']) : null;

if (empty($stores) || !$game_id || !$set_id) {
  echo json_encode(['error' => 'Invalid parameters']);
  exit;
}

$store_ids = implode(',', array_map('intval', $stores));

$query = "SELECT c.id, c.name, c.image_filename, c.rarity, c.card_number, 
                 g.name AS game_name, s.name AS set_name,
                 cl.store_id, st.name AS store_name, cl.price, cl.quantity, cl.shipping
          FROM cards c
          JOIN sets s ON c.set_id = s.id
          JOIN games g ON s.game_id = g.id
          LEFT JOIN card_listings cl ON c.id = cl.card_id AND cl.store_id IN ($store_ids)
          LEFT JOIN stores st ON cl.store_id = st.id
          WHERE g.id = :game_id AND s.id = :set_id
          ORDER BY c.id ASC, st.name ASC";

$stmt = $conn->prepare($query);
$stmt->execute([':game_id' => $game_id, ':set_id' => $set_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$cards = [];
foreach ($results as $row) {
  if (!isset($cards[$row['id']])) {
    $cards[$row['id']] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'image_filename' => $row['image_filename'],
      'rarity' => $row['rarity'],
      'card_number' => $row['card_number'],
      'game_name' => $row['game_name'],
      'set_name' => $row['set_name'],
      'prices' => []
    ];
  }
  if ($row['store_id']) {
    $cards[$row['id']]['prices'][$row['store_id']] = [
      'store_name' => $row['store_name'],
      'price' => $row['price'],
      'quantity' => $row['quantity'],
      'shipping' => $row['shipping']
    ];
  }
}

// Lấy thông tin tất cả các cửa hàng đã chọn
$store_query = "SELECT * FROM stores WHERE id IN ($store_ids)";
$store_stmt = $conn->query($store_query);
$all_stores = $store_stmt->fetchAll(PDO::FETCH_ASSOC);
$all_stores = array_column($all_stores, null, 'id');

// Trả về dữ liệu dưới dạng JSON
echo json_encode([
  'cards' => array_values($cards),
  'stores' => $all_stores
]);
