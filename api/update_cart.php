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
$quantity = $_POST['quantity'];

try {
  $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE id = :cart_id AND user_id = :user_id");
  $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
  $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
  $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    $total_items = getCartItemCount($conn, $_SESSION['user_id']);
    echo json_encode([
      'success' => true,
      'message' => 'Cart updated successfully',
      'total_items' => $total_items
    ]);
  } else {
    echo json_encode(['success' => false, 'message' => 'No changes made to the cart']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
