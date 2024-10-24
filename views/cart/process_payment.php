<?php
session_start();
include '../../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['payment_method'])) {
  header('Location: /views/cart/review.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$payment_method = $_POST['payment_method'];

// Lấy thông tin giỏ hàng và thông tin shipping từ session
$cart_items = $_SESSION['cart_items'] ?? [];
$shipping_info = $_SESSION['shipping_info'] ?? [];

// Tính tổng giá trị đơn hàng
$total_amount = 0;
$shipping_fee = 0;
foreach ($cart_items as $item) {
  $total_amount += $item['price'] * $item['quantity'];
  $shipping_fee = max($shipping_fee, $item['shipping']);
}
$grand_total = $total_amount + $shipping_fee;

// Tạo đơn hàng mới
$stmt = $conn->prepare("
    INSERT INTO orders (user_id, total_amount, shipping_fee, address, city, state, postal_code, country, phone, email, payment_method, payment_status)
    VALUES (:user_id, :total_amount, :shipping_fee, :address, :city, :state, :postal_code, :country, :phone, :email, :payment_method, 'completed')
");

$stmt->execute([
  ':user_id' => $user_id,
  ':total_amount' => $grand_total,
  ':shipping_fee' => $shipping_fee,
  ':address' => $shipping_info['address_line_1'] . ' ' . $shipping_info['address_line_2'],
  ':city' => $shipping_info['city'],
  ':state' => $shipping_info['state'],
  ':postal_code' => $shipping_info['zip_code'],
  ':country' => $shipping_info['country'],
  ':phone' => $shipping_info['phone'],
  ':email' => $_SESSION['user_email'], // Giả sử email được lưu trong session
  ':payment_method' => $payment_method
]);

$order_id = $conn->lastInsertId();

// Thêm các mặt hàng vào bảng order_items
foreach ($cart_items as $item) {
  $stmt = $conn->prepare("
        INSERT INTO order_items (order_id, card_listing_id, store_id, quantity, price)
        VALUES (:order_id, :card_listing_id, :store_id, :quantity, :price)
    ");

  $stmt->execute([
    ':order_id' => $order_id,
    ':card_listing_id' => $item['listing_id'],
    ':store_id' => $item['store_id'],
    ':quantity' => $item['quantity'],
    ':price' => $item['price']
  ]);

  // Cập nhật số lượng trong card_listings
  $stmt = $conn->prepare("
        UPDATE card_listings 
        SET quantity = quantity - :ordered_quantity 
        WHERE id = :listing_id
    ");

  $stmt->execute([
    ':ordered_quantity' => $item['quantity'],
    ':listing_id' => $item['listing_id']
  ]);
}

// Xóa giỏ hàng sau khi đặt hàng thành công
$stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);

// Xóa thông tin giỏ hàng và shipping từ session
unset($_SESSION['cart_items']);
unset($_SESSION['shipping_info']);

// Chuyển hướng đến trang xác nhận đơn hàng
header('Location: /views/cart/order_confirmation.php?order_id=' . $order_id);
exit;
