<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';
include '../../includes/config.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
  header('Location: /views/login/sign_in.php');
  exit;
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin đơn hàng từ session hoặc database
$shipping_info = $_SESSION['shipping_info'] ?? [];

// Lưu thông tin shipping vào session
$_SESSION['order_shipping_info'] = $shipping_info;

// Lấy thông tin giỏ hàng và chi tiết từ bảng cards
$cart_items = [];
if (isset($_SESSION['cart_items'])) {
  foreach ($_SESSION['cart_items'] as $cart_item) {
    $stmt = $conn->prepare("
            SELECT cl.card_id, cl.price, c.rarity, c.card_number, c.subtype, c.name, c.image_filename
            FROM card_listings cl
            JOIN cards c ON cl.card_id = c.id
            WHERE cl.id = :listing_id
        ");
    $stmt->execute([':listing_id' => $cart_item['listing_id']]);
    $item_details = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item_details) {
      $cart_items[] = array_merge($cart_item, $item_details);
    }
  }
}
$error_messages = [];

// Tính tổng
$subtotal = 0;
$total_shipping = 0;
$stores = [];

foreach ($cart_items as $item) {
  $subtotal += $item['price'] * $item['quantity'];

  // Nếu cửa hàng chưa được tính phí vận chuyển, thêm vào
  if (!isset($stores[$item['store_id']])) {
    $stores[$item['store_id']] = $item['shipping'];
    $total_shipping += $item['shipping'];
  }
}

$grand_total = $subtotal + $total_shipping;

// Định nghĩa biến $cart_summary
$cart_summary = [
  'package_count' => count($stores),
  'total_items' => array_sum(array_column($cart_items, 'quantity'))
];

// Sau khi xử lý thanh toán thành công
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
  $stmt = $conn->prepare("
        INSERT INTO orders (user_id, first_name, last_name, total_amount, shipping_fee, address, city, state, postal_code, country, phone, payment_method, payment_status)
        VALUES (:user_id, :first_name, :last_name, :total_amount, :shipping_fee, :address, :city, :state, :postal_code, :country, :phone, :payment_method, 'completed')
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

$shipping = $total_shipping;
$total = $grand_total;
?>

<!-- Thêm đoạn này vào phần đầu của file, sau các câu lệnh PHP -->
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>"></script>

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
        <?php
        $grouped_items = [];
        foreach ($cart_items as $item) {
          $grouped_items[$item['store_name']][] = $item;
        }
        ?>

        <?php foreach ($grouped_items as $store_name => $items): ?>
          <h3><?php echo htmlspecialchars($store_name); ?></h3>
          <p class="shipped-by">Shipped by <?php echo htmlspecialchars($store_name); ?></p>
          <table class="order-table">
            <thead>
              <tr>
                <th>Items</th>
                <th>Details</th>
                <th>Price</th>
                <th>Shipping</th>
                <th>Quantity</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($items as $item): ?>
                <tr>
                  <td>
                    <div class="item-info">
                      <img src="/public/images/product/<?php echo htmlspecialchars($item['image_filename']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                      <div>
                        <a href="/views/card_details.php?id=<?php echo $item['card_id']; ?>">
                          <p style="color: var(--blue-color); font-weight: 500;"><?php echo htmlspecialchars($item['name']); ?></p>
                        </a>
                        <p><?php echo htmlspecialchars($item['set_name'] ?? 'Unknown Set') . ' - ' . htmlspecialchars($item['game_name'] ?? 'Unknown Game'); ?></p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div><?php echo htmlspecialchars($item['rarity'] ?? 'Unknown'); ?></div>
                    <div><?php echo htmlspecialchars($item['card_number'] ?? 'Unknown'); ?></div>
                    <div><?php echo htmlspecialchars($item['subtype'] ?? 'Unknown'); ?></div>
                  </td>
                  <td>$<?php echo number_format($item['price'], 2); ?></td>
                  <td>$<?php echo number_format($item['shipping'], 2); ?></td>
                  <td><?php echo $item['quantity']; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endforeach; ?>
      </div>

      <!-- SUMMARY -->
      <div class="checkout__summary">
        <h2>Shopping Cart Summary</h2>
        <div class="checkout__summary-item">
          <p>Number of Packages:</p>
          <p><?php echo $cart_summary['package_count']; ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Number of Items:</p>
          <p><?php echo $cart_summary['total_items']; ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Items:</p>
          <p>$<?php echo number_format($subtotal, 2); ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Estimated Shipping:</p>
          <p>$<?php echo number_format($total_shipping, 2); ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Order Total:</p>
          <p>$<?php echo number_format($grand_total, 2); ?></p>
        </div>
      </div>
    </div>
    <div class="submit__container">
      <div class="checkout__form">
        <h2 style="margin-bottom: 16px;">Shipping Address</h2>
        <div class="shipping-address">
          <div class="address__left">
            <p>Name: <?php echo htmlspecialchars($shipping_info['first_name'] . ' ' . $shipping_info['last_name']); ?></p>
            <p>Phone: <?php echo htmlspecialchars($shipping_info['phone']); ?></p>
            <p>Address line 1: <?php echo htmlspecialchars($shipping_info['address_line_1']); ?></p>
            <?php if (!empty($shipping_info['address_line_2'])): ?>
              <p>Address line 2: <?php echo htmlspecialchars($shipping_info['address_line_2']); ?></p>
            <?php endif; ?>
          </div>
          <div class="address__right">
            <p>City: <?php echo htmlspecialchars($shipping_info['city']); ?></p>
            <p>State: <?php echo htmlspecialchars($shipping_info['state']); ?></p>
            <p>Country: <?php echo htmlspecialchars($shipping_info['country']); ?></p>
            <p>Postal Code: <?php echo htmlspecialchars($shipping_info['zip_code']); ?></p>
          </div>
        </div>

        <!-- Thay thế phần Payment Method và nút Submit Order bằng đoạn code sau -->
        <h2>Payment Method</h2>
        <div class="payment-options">
          <div class="payment-option">
            <input type="checkbox" id="paypal-cbx" name="payment_method" value="paypal" style="display: none;">
            <label for="paypal-cbx" class="check">
              <svg width="18px" height="18px" viewBox="0 0 18 18">
                <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path>
                <polyline points="1 9 7 14 15 4"></polyline>
              </svg>
              <img src="/public/images/paypal.webp" alt="Paypal">
            </label>
          </div>
        </div>
        <div class="checkout-buttons">
          <div id="paypal-button-container" style="display: none;"></div>
          <button id="submit-order" style="margin-top: -24px;" type="button" class="checkout__button" disabled>Submit Order</button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>

<!-- Thêm đoạn script này vào cuối file, trước đóng thẻ body -->
<script src="/public/js/paypal-checkout.js"></script>

<script>
  var grandTotal = <?php echo json_encode($grand_total); ?>;
</script>