<?php
session_start();
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/cart_functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

$store_id = $_POST['store_id'];

try {
  $stmt = $conn->prepare("
        DELETE c FROM cart c
        JOIN card_listings cl ON c.card_listing_id = cl.id
        WHERE c.user_id = :user_id AND cl.store_id = :store_id
    ");
  $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
  $stmt->bindParam(':store_id', $store_id, PDO::PARAM_INT);
  $stmt->execute();

  $total_items = getCartItemCount($conn, $_SESSION['user_id']);
  echo json_encode([
    'success' => true,
    'message' => 'Package removed successfully',
    'unique_items_count' => $total_items
  ]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
