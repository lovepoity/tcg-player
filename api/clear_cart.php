<?php
session_start();
include '../includes/db_connect.php';
include '../includes/cart_functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

try {
  $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
  $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
  $stmt->execute();

  $total_items = getCartItemCount($conn, $_SESSION['user_id']);
  echo json_encode([
    'success' => true,
    'message' => 'Cart cleared successfully',
    'total_items' => $total_items
  ]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
