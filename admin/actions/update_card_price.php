<?php
require_once '../../includes/db_connect.php';

$card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : null;
$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : null;
$price = isset($_POST['price']) ? floatval($_POST['price']) : null;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : null;
$shipping = isset($_POST['shipping']) ? floatval($_POST['shipping']) : null;

if (!$card_id || !$store_id || $price === null || $quantity === null || $shipping === null) {
  http_response_code(400);
  echo json_encode(['error' => 'Tham số không hợp lệ']);
  exit;
}

try {
  $conn->beginTransaction();

  // Kiểm tra xem đã có bản ghi cho card và store này chưa
  $check_query = "SELECT id FROM card_listings WHERE card_id = :card_id AND store_id = :store_id";
  $check_stmt = $conn->prepare($check_query);
  $check_stmt->execute([':card_id' => $card_id, ':store_id' => $store_id]);
  $existing_record = $check_stmt->fetch(PDO::FETCH_ASSOC);

  if ($existing_record) {
    // Nếu đã có, cập nhật thông tin
    $update_query = "UPDATE card_listings SET price = :price, quantity = :quantity, shipping = :shipping WHERE card_id = :card_id AND store_id = :store_id";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->execute([
      ':price' => $price,
      ':quantity' => $quantity,
      ':shipping' => $shipping,
      ':card_id' => $card_id,
      ':store_id' => $store_id
    ]);
  } else {
    // Nếu chưa có, thêm mới
    $insert_query = "INSERT INTO card_listings (card_id, store_id, price, quantity, shipping) VALUES (:card_id, :store_id, :price, :quantity, :shipping)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->execute([
      ':card_id' => $card_id,
      ':store_id' => $store_id,
      ':price' => $price,
      ':quantity' => $quantity,
      ':shipping' => $shipping
    ]);
  }

  $conn->commit();
  echo json_encode(['success' => true]);
} catch (Exception $e) {
  $conn->rollBack();
  http_response_code(500);
  echo json_encode(['error' => 'Lỗi khi cập nhật thông tin: ' . $e->getMessage()]);
}
