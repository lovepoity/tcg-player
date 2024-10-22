<?php
session_start();
include '../includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];
$store_id = $_GET['store_id'] ?? null;

if (!$store_id) {
  echo json_encode(['success' => false, 'message' => 'Missing store_id parameter']);
  exit;
}

try {
  $stmt = $conn->prepare("
        SELECT c.quantity, cl.price, cl.shipping
        FROM cart c
        JOIN card_listings cl ON c.card_listing_id = cl.id
        WHERE c.user_id = :user_id AND cl.store_id = :store_id
    ");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindParam(':store_id', $store_id, PDO::PARAM_INT);
  $stmt->execute();

  $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $subtotal = 0;
  $item_count = 0;
  $shipping = 0;

  foreach ($items as $item) {
    $subtotal += $item['quantity'] * $item['price'];
    $item_count += $item['quantity'];
    $shipping = max($shipping, $item['shipping']);
  }

  $total = $subtotal + $shipping;

  echo json_encode([
    'success' => true,
    'subtotal' => $subtotal,
    'item_count' => $item_count,
    'shipping' => $shipping,
    'total' => $total
  ]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
