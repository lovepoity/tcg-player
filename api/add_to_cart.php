<?php
session_start();
include '../includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$listing_id = $data['listing_id'];
$quantity = $data['quantity'];

try {
  // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
  $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = :user_id AND card_listing_id = :listing_id");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);
  $stmt->execute();
  $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($existing_item) {
    // Cập nhật số lượng nếu sản phẩm đã có trong giỏ hàng
    $new_quantity = $existing_item['quantity'] + $quantity;
    $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id");
    $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
    $stmt->bindParam(':id', $existing_item['id'], PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Item quantity updated in cart']);
  } else {
    // Thêm sản phẩm mới vào giỏ hàng
    $stmt = $conn->prepare("INSERT INTO cart (user_id, card_listing_id, quantity) VALUES (:user_id, :listing_id, :quantity)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode(['success' => true, 'message' => 'Item added to cart successfully']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
