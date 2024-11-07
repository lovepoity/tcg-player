<?php
session_start();
require_once '../../../includes/db_connect.php';

if (!isset($_SESSION['store_id'])) {
  exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

$store_id = $_SESSION['store_id'];
$order_id = isset($_POST['order_id']) ? $_POST['order_id'] : 0;
$status = isset($_POST['status']) ? $_POST['status'] : '';

if (!$order_id || !$status) {
  exit(json_encode(['success' => false, 'message' => 'Invalid parameters']));
}

try {
  $conn->beginTransaction();

  // Update order items status for this store
  $query = "
    UPDATE order_items 
    SET status = ? 
    WHERE order_id = ? AND store_id = ?
  ";

  $stmt = $conn->prepare($query);
  $stmt->execute([$status, $order_id, $store_id]);

  // Check if all items are in final status to update main order status
  $query = "
    SELECT COUNT(*) as total, 
           SUM(CASE WHEN status IN ('Completed', 'Cancelled') THEN 1 ELSE 0 END) as finished
    FROM order_items 
    WHERE order_id = ?
  ";

  $stmt = $conn->prepare($query);
  $stmt->execute([$order_id]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result['total'] == $result['finished']) {
    $query = "UPDATE orders SET status = 'Completed' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$order_id]);
  }

  $conn->commit();
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  $conn->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
