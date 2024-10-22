<?php
session_start();
include '../includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];

try {
  $stmt = $conn->prepare("
        SELECT cl.store_id, c.quantity, cl.price, cl.shipping
        FROM cart c
        JOIN card_listings cl ON c.card_listing_id = cl.id
        WHERE c.user_id = :user_id
    ");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $total_packages = 0;
  $total_items = 0;
  $subtotal = 0;
  $total_shipping = 0;
  $stores = [];

  foreach ($items as $item) {
    if (!isset($stores[$item['store_id']])) {
      $stores[$item['store_id']] = ['shipping' => 0];
      $total_packages++;
    }
    $subtotal += $item['quantity'] * $item['price'];
    $total_items += $item['quantity'];
    $stores[$item['store_id']]['shipping'] = max($stores[$item['store_id']]['shipping'], $item['shipping']);
  }

  foreach ($stores as $store) {
    $total_shipping += $store['shipping'];
  }

  $grand_total = $subtotal + $total_shipping;

  echo json_encode([
    'success' => true,
    'total_packages' => $total_packages,
    'total_items' => $total_items,
    'subtotal' => $subtotal,
    'total_shipping' => $total_shipping,
    'grand_total' => $grand_total
  ]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
