<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
  header('Location: /index.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'];

// Kiểm tra xem đơn hàng có thuộc về user hiện tại không
$stmt = $conn->prepare("
    SELECT COUNT(*) as count 
    FROM orders 
    WHERE id = :order_id AND user_id = :user_id
");
$stmt->execute([
  ':order_id' => $order_id,
  ':user_id' => $user_id
]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result['count'] == 0) {
  // Nếu không tìm thấy đơn hàng hoặc đơn hàng không thuộc về user này
  $_SESSION['error'] = "You don't have permission to view this order.";
  header('Location: /index.php');
  exit;
}

// Get order information
$stmt = $conn->prepare("
    SELECT * FROM orders
    WHERE id = :order_id AND user_id = :user_id
");
$stmt->execute([':order_id' => $order_id, ':user_id' => $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  header('Location: /index.php');
  exit;
}

// Lấy chi tiết đơn hàng
$stmt = $conn->prepare("
    SELECT oi.*, c.name, c.image_filename, c.rarity, c.card_number, c.subtype, c.id as card_id,
           s.name as store_name, cl.shipping,
           sets.name as set_name, g.name as game_name
    FROM order_items oi
    JOIN card_listings cl ON oi.card_listing_id = cl.id
    JOIN cards c ON cl.card_id = c.id
    JOIN stores s ON oi.store_id = s.id
    LEFT JOIN sets ON c.set_id = sets.id
    LEFT JOIN games g ON sets.game_id = g.id
    WHERE oi.order_id = :order_id
");
$stmt->execute([':order_id' => $order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính toán tổng
$subtotal = 0;
$total_shipping = 0;
$stores = [];
foreach ($order_items as $item) {
  $subtotal += $item['price'] * $item['quantity'];
  if (!isset($stores[$item['store_id']])) {
    $stores[$item['store_id']] = [
      'name' => $item['store_name'],
      'shipping' => $item['shipping']
    ];
    $total_shipping += $item['shipping'];
  }
}
$total = $subtotal + $total_shipping;

// Lấy thông tin shipping từ session
$shipping_info = $_SESSION['order_shipping_info'] ?? [];

// Xóa thông tin shipping khỏi session sau khi đã sử dụng
unset($_SESSION['order_shipping_info']);
?>
<link rel="stylesheet" href="/public/css/checkout.css">
<div class="container">
  <div class="checkout order-confirmed">
    <h1>Order Confirmed</h1>
    <!-- ORDER CONFIRMED -->
    <div class="processed">
      <div class="processed__img">
        <img src="/public/images/pikachu.png" alt="pikachu">
      </div>
      <div class="processed__text">
        <p>ORDER HAS BEEN PROCESSED</p>
        <span>TCG PLAYER will send information update to you.</span>
      </div>
    </div>
    <!-- ORDER DETAILS -->
    <div class="confirm__block margin-bottom">
      <h2>Order Details</h2>
      <div class="confirm__box">
        <div class="order-details__content-item text--custom">
          <p>Order ID </p><span><?php echo $order_id; ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Items Quantity </p><span><?php echo count($order_items); ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Payment Method </p><span><?php echo $order['payment_method']; ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Status </p><span><?php echo ucfirst($order['status']); ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Total Amount </p><span>$<?php echo number_format($order['total_amount'], 2); ?></span>
        </div>
      </div>
    </div>
    <!-- delivery information -->
    <div class="confirm__block margin-bottom">
      <h2>Delivery Information</h2>
      <div class="confirm__box">
        <div class="delivery-information__content-item text--custom">
          <p>Name: </p><span>
            <?php
            $name = trim($order['first_name'] . ' ' . $order['last_name']);
            echo $name ? htmlspecialchars($name) : 'No information';
            ?>
          </span>
        </div>
        <div class="delivery-information__content-item text--custom">
          <p>Telephone: </p><span><?php echo htmlspecialchars($order['phone']); ?></span>
        </div>
        <div class="delivery-information__content-item text--custom">
          <p>Email: </p><span><?php echo htmlspecialchars($order['email']); ?></span>
        </div>
        <div class="delivery-information__content-item text--custom">
          <p>Postal Code: </p><span><?php echo isset($order['postal_code']) ? htmlspecialchars($order['postal_code']) : 'No information'; ?></span>
        </div>
        <div class="delivery-information__content-item text--custom">
          <p>Shipping Address: </p><span><?php echo htmlspecialchars($order['address']); ?>, <?php echo htmlspecialchars($order['city']); ?>, <?php echo htmlspecialchars($order['state']); ?>, <?php echo htmlspecialchars($order['country']); ?></span>
        </div>
      </div>
    </div>
    <!-- ORDER ITEMS -->
    <div class="confirm__table margin-bottom">
      <h2>Order Items</h2>
      <?php
      $grouped_items = [];
      foreach ($order_items as $item) {
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
                      <a target="_blank" href="/views/card_details.php?id=<?php echo $item['card_id']; ?>">
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
    <!-- CONTINUE SHOPPING -->
    <div class="continue-shopping">
      <a href="/views/card_all.php?show_all=1" class="confirm__btn">Continue Shopping</a>
    </div>
  </div>
</div>

<script>
  history.pushState(null, null, location.href);
  window.onpopstate = function() {
    history.go(1);
  };
  window.onunload = function() {};
  document.onkeydown = function(e) {
    if (e.key === "Backspace") {
      var element = e.target;
      if (element.tagName.toLowerCase() !== "input" && element.tagName.toLowerCase() !== "textarea") {
        e.preventDefault();
      }
    }
  };
</script>
<?php include '../../includes/footer.php'; ?>