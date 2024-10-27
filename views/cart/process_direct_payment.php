<?php
session_start();
include '../../includes/db_connect.php';

header('Content-Type: application/json');

try {
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  if (!isset($_SESSION['user_id']) || !isset($data['paymentMethod'])) {
    throw new Exception('Invalid user information or payment method');
  }

  $user_id = $_SESSION['user_id'];
  $payment_method = $data['paymentMethod'];

  // Lấy thông tin giỏ hàng
  $stmt = $conn->prepare("
        SELECT c.id as cart_id, c.quantity, cl.id as listing_id, cl.price, cl.shipping, 
               cd.name, s.id as store_id, s.name as store_name
        FROM cart c
        JOIN card_listings cl ON c.card_listing_id = cl.id
        JOIN cards cd ON cl.card_id = cd.id
        JOIN stores s ON cl.store_id = s.id
        WHERE c.user_id = :user_id
    ");
  $stmt->execute([':user_id' => $user_id]);
  $cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($cart_items)) {
    throw new Exception('Empty cart');
  }

  // Lấy thông tin người dùng
  $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
  $stmt->execute([':user_id' => $user_id]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  $total_amount = 0;
  $total_shipping = 0;
  $stores = [];
  foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
    if (!isset($stores[$item['store_id']])) {
      $stores[$item['store_id']] = $item['shipping'];
      $total_shipping += $item['shipping'];
    }
  }
  $grand_total = $total_amount + $total_shipping;

  $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total_amount, shipping_fee, address, city, state, postal_code, country, phone, email, payment_method, payment_status)
        VALUES (:user_id, :total_amount, :shipping_fee, :address, :city, :state, :postal_code, :country, :phone, :email, :payment_method, 'completed')
    ");

  $stmt->execute([
    ':user_id' => $user_id,
    ':total_amount' => $grand_total,
    ':shipping_fee' => $total_shipping,
    ':address' => $user['address'],
    ':city' => $user['city'],
    ':state' => $user['state'],
    ':postal_code' => $user['postal_code'],
    ':country' => $user['country'],
    ':phone' => $user['phone'],
    ':email' => $user['email'],
    ':payment_method' => $payment_method
  ]);

  $order_id = $conn->lastInsertId();

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

  $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
  $stmt->execute([':user_id' => $user_id]);

  echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
