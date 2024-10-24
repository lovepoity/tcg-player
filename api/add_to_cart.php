<?php
session_start();
include '../includes/db_connect.php';
include '../includes/cart_functions.php'; // Thêm dòng này

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
  $conn->beginTransaction();

  // Check available quantity
  $stmt = $conn->prepare("SELECT quantity FROM card_listings WHERE id = :listing_id");
  $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);
  $stmt->execute();
  $available_quantity = $stmt->fetchColumn();
  if ($available_quantity < $quantity) {
    $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'Not enough stock available', 'available_quantity' => $available_quantity]);
    exit;
  }

  // Check if the item is already in the cart
  $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = :user_id AND card_listing_id = :listing_id");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);
  $stmt->execute();
  $existing_item = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($existing_item) {
    // Update quantity if the item is already in the cart
    $new_quantity = $existing_item['quantity'] + $quantity;
    if ($new_quantity > $available_quantity) {
      $conn->rollBack();
      echo json_encode(['success' => false, 'message' => 'Not enough stock available.', 'available_quantity' => $available_quantity]);
      exit;
    }
    $stmt = $conn->prepare("UPDATE cart SET quantity = :quantity WHERE id = :id");
    $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
    $stmt->bindParam(':id', $existing_item['id'], PDO::PARAM_INT);
    $stmt->execute();
  } else {
    // Add new item to the cart
    $stmt = $conn->prepare("INSERT INTO cart (user_id, card_listing_id, quantity) VALUES (:user_id, :listing_id, :quantity)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':listing_id', $listing_id, PDO::PARAM_INT);
    $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt->execute();
  }

  $conn->commit();

  $unique_items_count = getCartItemCount($conn, $user_id); // Thêm dòng này

  echo json_encode([
    'success' => true,
    'message' => 'Item added to cart successfully',
    'remaining_quantity' => $available_quantity,
    'unique_items_count' => $unique_items_count // Thêm dòng này
  ]);
} catch (PDOException $e) {
  $conn->rollBack();
  echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
