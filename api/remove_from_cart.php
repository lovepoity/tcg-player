<?php
session_start();
include '../includes/db_connect.php';
include '../includes/cart_functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

$cart_id = $_POST['cart_id'];

try {
  $stmt = $conn->prepare("DELETE FROM cart WHERE id = :cart_id AND user_id = :user_id");
  $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
  $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
  $stmt->execute();

  $total_items = getCartItemCount($conn, $_SESSION['user_id']);
  $unique_items_count = getUniqueCartItemCount($conn, $_SESSION['user_id']);

  echo json_encode([
    'success' => true,
    'message' => 'Item removed from cart successfully',
    'total_items' => $total_items,
    'unique_items_count' => $unique_items_count
  ]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
