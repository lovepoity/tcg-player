<?php
session_start();
require_once '../../../includes/db_connect.php';

if (!isset($_SESSION['store_id']) || !isset($_POST['card_id'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

$store_id = $_SESSION['store_id'];
$card_id = $_POST['card_id'];
$quantity = $_POST['quantity'];
$price = $_POST['price'];
$shipping = $_POST['shipping'];

try {
  // Kiểm tra xem đã có listing chưa
  $check_query = "SELECT id FROM card_listings 
                   WHERE store_id = ? AND card_id = ?";
  $check_stmt = $conn->prepare($check_query);
  $check_stmt->execute([$store_id, $card_id]);
  $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

  if ($existing) {
    // Update listing hiện có
    $query = "UPDATE card_listings 
                 SET quantity = ?, price = ?, shipping = ?, updated_at = NOW()
                 WHERE store_id = ? AND card_id = ?";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([$quantity, $price, $shipping, $store_id, $card_id]);
  } else {
    // Tạo listing mới
    $query = "INSERT INTO card_listings (store_id, card_id, quantity, price, shipping)
                 VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([$store_id, $card_id, $quantity, $price, $shipping]);
  }

  if ($result) {
    echo json_encode(['success' => true, 'message' => 'Changes saved successfully']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Error saving changes']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
