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

  $cart_items = $_SESSION['cart_items'] ?? [];
  $shipping_info = $_SESSION['shipping_info'] ?? [];

  if (empty($cart_items) || empty($shipping_info)) {
    throw new Exception('Empty cart or missing shipping information');
  }

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
        INSERT INTO orders (user_id, first_name, last_name, total_amount, shipping_fee, address, city, state, postal_code, country, phone, email, payment_method, payment_status)
        VALUES (:user_id, :first_name, :last_name, :total_amount, :shipping_fee, :address, :city, :state, :postal_code, :country, :phone, :email, :payment_method, 'completed')
    ");

  $stmt->execute([
    ':user_id' => $user_id,
    ':first_name' => $shipping_info['first_name'],
    ':last_name' => $shipping_info['last_name'],
    ':total_amount' => $grand_total,
    ':shipping_fee' => $total_shipping,
    ':address' => $shipping_info['address_line_1'] . ' ' . $shipping_info['address_line_2'],
    ':city' => $shipping_info['city'],
    ':state' => $shipping_info['state'],
    ':postal_code' => $shipping_info['zip_code'],
    ':country' => $shipping_info['country'],
    ':phone' => $shipping_info['phone'],
    ':email' => $_SESSION['user_email'],
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

  unset($_SESSION['cart_items']);
  unset($_SESSION['shipping_info']);

  // Tính toán và lưu transactions cho từng store
  foreach ($stores as $store_id => $shipping) {
    $store_total = 0;
    foreach ($cart_items as $item) {
      if ($item['store_id'] == $store_id) {
        $store_total += $item['price'] * $item['quantity'];
      }
    }

    // Tính toán số tiền
    $tcg_amount = $store_total * 0.1;    // 10% cho TCG
    $store_amount = $store_total * 0.9;   // 90% cho store

    // Lưu transaction
    $stmt = $conn->prepare("
        INSERT INTO transactions (
            order_id,
            store_id,
            store_amount,
            tcg_amount
        )
        VALUES (
            :order_id,
            :store_id, 
            :store_amount,
            :tcg_amount
        )
    ");

    $stmt->execute([
      ':order_id' => $order_id,
      ':store_id' => $store_id,
      ':store_amount' => $store_amount,
      ':tcg_amount' => $tcg_amount
    ]);
  }

  echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Exception $e) {
  echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
