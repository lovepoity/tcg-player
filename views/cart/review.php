<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
  header('Location: /views/login/sign_in.php');
  exit;
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin đơn hàng từ session hoặc database
// Giả sử thông tin shipping đã được lưu trong session
$shipping_info = $_SESSION['shipping_info'] ?? [];

// Lấy thông tin giỏ hàng
$cart_items = $_SESSION['cart_items'] ?? [];
$error_messages = [];

// Kiểm tra số lượng tồn kho
foreach ($cart_items as $item) {
  $stmt = $conn->prepare("
        SELECT quantity 
        FROM card_listings 
        WHERE id = :listing_id
    ");
  $stmt->execute([':listing_id' => $item['listing_id']]);
  $available_quantity = $stmt->fetchColumn();

  if ($item['quantity'] > $available_quantity) {
    $error_messages[] = "Sản phẩm '{$item['name']}' chỉ còn {$available_quantity} trong kho.";
  }
}

// Display error messages if any
if (!empty($error_messages)) {
  foreach ($error_messages as $message) {
    echo "<p style='color: red;'>{$message}</p>";
  }
  echo "<a href='/views/cart/cart.php'>Return to Cart</a>";
  exit;
}

// Tính tổng
$subtotal = 0;
$total_shipping = 0;
foreach ($cart_items as $item) {
  $subtotal += $item['price'] * $item['quantity'];
  $total_shipping = max($total_shipping, $item['shipping']);
}
$grand_total = $subtotal + $total_shipping;

// Sau khi xử lý thanh toán thành công
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
  // Xử lý thanh toán ở đây

  // Nếu thanh toán thành công, tạo đơn hàng mới
  $stmt = $conn->prepare("
        INSERT INTO orders (user_id, total_amount, shipping_fee, address, city, state, postal_code, country, phone, email, payment_method, payment_status)
        VALUES (:user_id, :total_amount, :shipping_fee, :address, :city, :state, :postal_code, :country, :phone, :email, :payment_method, 'completed')
    ");

  $stmt->execute([
    ':user_id' => $user_id,
    ':total_amount' => $grand_total,
    ':shipping_fee' => $total_shipping,
    ':address' => $shipping_info['address_line_1'] . ' ' . $shipping_info['address_line_2'],
    ':city' => $shipping_info['city'],
    ':state' => $shipping_info['state'],
    ':postal_code' => $shipping_info['zip_code'],
    ':country' => $shipping_info['country'],
    ':phone' => $shipping_info['phone'],
    ':email' => $user['email'], // Giả sử email được lưu trong thông tin người dùng
    ':payment_method' => $_POST['payment_method']
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
  }

  // Xóa giỏ hàng sau khi đặt hàng thành công
  $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = :user_id");
  $stmt->execute([':user_id' => $user_id]);

  // Chuyển hướng đến trang xác nhận đơn hàng
  header('Location: /views/cart/order_confirmation.php?order_id=' . $order_id);
  exit;
}

?>

<link rel="stylesheet" href="/public/css/checkout.css">

<div class="container">
  <div class="checkout">
    <div class="progress-bar">
      <p><i class="fa-solid fa-lock"></i> CHECKOUT</p> <i class="fa-solid fa-angle-right"></i>
      <p>1. SHIPPING</p> <i class="fa-solid fa-angle-right"></i>
      <p class="progress-bar-active">2. REVIEW AND PAY</p>
    </div>
    <h1>Review Your Order and Select Payment</h1>

    <div class="checkout__container">
      <div class="checkout__form">
        <h2>Order Summary</h2>
        <?php foreach ($cart_items as $item): ?>
          <div class="order-item">
            <img src="/public/images/product/<?php echo htmlspecialchars($item['image_filename']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
            <div class="order-item-details">
              <h3><?php echo htmlspecialchars($item['name']); ?></h3>
              <p>Set: <?php echo htmlspecialchars($item['set_name']); ?></p>
              <p>Game: <?php echo htmlspecialchars($item['game_name']); ?></p>
              <p>Store: <?php echo htmlspecialchars($item['store_name']); ?></p>
              <p>Quantity: <?php echo $item['quantity']; ?></p>
              <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
            </div>
          </div>
        <?php endforeach; ?>

        <h2>Shipping Address</h2>
        <div class="shipping-address">
          <p><?php echo htmlspecialchars($shipping_info['first_name'] . ' ' . $shipping_info['last_name']); ?></p>
          <p><?php echo htmlspecialchars($shipping_info['address_line_1']); ?></p>
          <?php if (!empty($shipping_info['address_line_2'])): ?>
            <p><?php echo htmlspecialchars($shipping_info['address_line_2']); ?></p>
          <?php endif; ?>
          <p><?php echo htmlspecialchars($shipping_info['city'] . ', ' . $shipping_info['state'] . ' ' . $shipping_info['zip_code']); ?></p>
          <p><?php echo htmlspecialchars($shipping_info['country']); ?></p>
          <p>Phone: <?php echo htmlspecialchars($shipping_info['phone']); ?></p>
        </div>

        <h2>Payment Method</h2>
        <form action="/views/cart/process_payment.php" method="post">
          <div class="payment-options">
            <label>
              <input type="radio" name="payment_method" value="credit_card" checked>
              Credit Card
            </label>
            <label>
              <input type="radio" name="payment_method" value="paypal">
              PayPal
            </label>
          </div>
          <button type="submit" class="checkout__button">Place Order</button>
        </form>
      </div>

      <div class="checkout__summary">
        <h2>Order Total</h2>
        <div class="checkout__summary-item">
          <p>Subtotal:</p>
          <p>$<?php echo number_format($subtotal, 2); ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Shipping:</p>
          <p>$<?php echo number_format($total_shipping, 2); ?></p>
        </div>
        <div class="checkout__summary-item">
          <p><strong>Total:</strong></p>
          <p><strong>$<?php echo number_format($grand_total, 2); ?></strong></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>