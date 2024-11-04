<?php
session_start();
require_once __DIR__ . '/../../../includes/db_connect.php';

header('Content-Type: application/json');

try {
  if (!isset($_SESSION['user_id']) || !isset($_POST['order_id'])) {
    throw new Exception('Invalid request');
  }

  $user_id = $_SESSION['user_id'];
  $order_id = $_POST['order_id'];

  // Kiểm tra order có thuộc về user không
  $stmt = $conn->prepare("
        SELECT * FROM orders 
        WHERE id = :order_id AND user_id = :user_id AND status = 'Processing'
    ");
  $stmt->execute([
    ':order_id' => $order_id,
    ':user_id' => $user_id
  ]);

  if (!$stmt->fetch()) {
    throw new Exception('Order not found or cannot be cancelled');
  }

  // Bắt đầu transaction
  $conn->beginTransaction();

  // Cập nhật trạng thái order
  $stmt = $conn->prepare("
        UPDATE orders 
        SET status = 'Cancelled' 
        WHERE id = :order_id
    ");
  $stmt->execute([':order_id' => $order_id]);

  // Cập nhật transaction
  $stmt = $conn->prepare("
    UPDATE transactions 
    SET amount = 0,
        admin_commission = 0,
        store_earnings = 0
    WHERE order_id = :order_id
");
  $stmt->execute([':order_id' => $order_id]);

  // Cập nhật store_earnings status thành Cancelled
  $stmt = $conn->prepare("
        UPDATE store_earnings 
        SET status = 'Cancelled' 
        WHERE order_id = :order_id
    ");
  $stmt->execute([':order_id' => $order_id]);

  // Hoàn lại số lượng sản phẩm
  $stmt = $conn->prepare("
        SELECT card_listing_id, quantity 
        FROM order_items 
        WHERE order_id = :order_id
    ");
  $stmt->execute([':order_id' => $order_id]);
  $items = $stmt->fetchAll();

  foreach ($items as $item) {
    $stmt = $conn->prepare("
            UPDATE card_listings 
            SET quantity = quantity + :quantity 
            WHERE id = :listing_id
        ");
    $stmt->execute([
      ':quantity' => $item['quantity'],
      ':listing_id' => $item['card_listing_id']
    ]);
  }

  $conn->commit();
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  if ($conn->inTransaction()) {
    $conn->rollBack();
  }
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
