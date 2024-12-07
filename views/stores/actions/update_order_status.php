<?php
session_start();
require_once '../../../includes/db_connect.php';

if (!isset($_SESSION['store_id']) || !isset($_POST['order_id']) || !isset($_POST['status'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid request']);
  exit;
}

$store_id = $_SESSION['store_id'];
$order_id = $_POST['order_id'];
$new_status = $_POST['status'];

try {
  $conn->beginTransaction();

  // 1. Cập nhật status cho order_item của store hiện tại
  $updateItemQuery = "UPDATE order_items 
                       SET status = :status 
                       WHERE order_id = :order_id 
                       AND store_id = :store_id";

  $stmt = $conn->prepare($updateItemQuery);
  $stmt->execute([
    ':status' => $new_status,
    ':order_id' => $order_id,
    ':store_id' => $store_id
  ]);

  // 2. Lấy tất cả status của order_items
  $checkStatusQuery = "SELECT oi.status
                      FROM order_items oi
                      WHERE oi.order_id = :order_id";

  $stmt = $conn->prepare($checkStatusQuery);
  $stmt->execute([':order_id' => $order_id]);
  $allStatuses = $stmt->fetchAll(PDO::FETCH_COLUMN);

  // 3. Kiểm tra nếu tất cả đều là Cancelled
  $allCancelled = true;
  foreach ($allStatuses as $status) {
    if ($status !== 'Cancelled') {
      $allCancelled = false;
      break;
    }
  }

  if ($allCancelled) {
    $orderStatus = 'Cancelled';
  } else {
    // 4. Xác định status cho order nếu không phải tất cả đều cancel
    $statusOrder = [
      'Processing' => 0,
      'Picking' => 1,
      'Packing' => 2,
      'Shipping' => 3,
      'Delivered' => 4,
      'Completed' => 5
    ];

    $hasProcessing = false;
    $allCompleted = true;
    $lowestNonCancelStatus = null;
    $lowestOrder = 5;

    // Tìm status thấp nhất và kiểm tra các điều kiện đặc biệt
    foreach ($allStatuses as $status) {
      if ($status !== 'Cancelled') {
        if ($status === 'Processing') {
          $hasProcessing = true;
        }
        if ($status !== 'Completed') {
          $allCompleted = false;
        }
        if (isset($statusOrder[$status]) && $statusOrder[$status] < $lowestOrder) {
          $lowestOrder = $statusOrder[$status];
          $lowestNonCancelStatus = $status;
        }
      }
    }

    // Xác định order status
    if ($allCompleted) {
      $orderStatus = 'Completed';
    } else {
      $orderStatus = $hasProcessing ? 'Processing' : $lowestNonCancelStatus;
    }
  }

  // 5. Cập nhật status cho orders
  $updateOrderQuery = "UPDATE orders 
                      SET status = :status, 
                          updated_at = CURRENT_TIMESTAMP
                      WHERE id = :order_id";

  $stmt = $conn->prepare($updateOrderQuery);
  $stmt->execute([
    ':status' => $orderStatus,
    ':order_id' => $order_id
  ]);

  $conn->commit();

  echo json_encode([
    'success' => true,
    'message' => 'Order status updated successfully',
    'order_status' => $orderStatus
  ]);
} catch (Exception $e) {
  $conn->rollBack();
  echo json_encode([
    'success' => false,
    'message' => 'Error updating order status: ' . $e->getMessage()
  ]);
}
