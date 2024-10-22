<?php
session_start();
include '../includes/db_connect.php';

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

  if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Item removed from cart successfully']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Item not found in cart']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
