<?php
ob_start(); // Bắt đầu output buffering

session_start();
include '../../includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
  header('Location: /views/login/sign_in.php');
  exit;
}

$user_id = $_SESSION['user_id'];

// Xử lý form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Lưu thông tin shipping vào session
  $_SESSION['shipping_info'] = [
    'first_name' => $_POST['first_name'],
    'last_name' => $_POST['last_name'],
    'address_line_1' => $_POST['address_line_1'],
    'address_line_2' => $_POST['address_line_2'],
    'city' => $_POST['city'],
    'state' => $_POST['state'],
    'zip_code' => $_POST['zip_code'],
    'country' => $_POST['country'],
    'phone' => $_POST['phone']
  ];

  // Lấy và lưu thông tin giỏ hàng vào session
  $stmt = $conn->prepare("
    SELECT c.id as cart_id, c.quantity, cl.id as listing_id, cl.price, cl.shipping, 
           cd.name, cd.image_filename, s.name as set_name, g.name as game_name,
           st.id as store_id, st.name as store_name
    FROM cart c
    JOIN card_listings cl ON c.card_listing_id = cl.id
    JOIN cards cd ON cl.card_id = cd.id
    JOIN sets s ON cd.set_id = s.id
    JOIN games g ON s.game_id = g.id
    JOIN stores st ON cl.store_id = st.id
    WHERE c.user_id = :user_id
  ");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $_SESSION['cart_items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Chuyển hướng đến trang xem lại và thanh toán
  header('Location: /views/cart/review.php');
  exit;
}

// Lấy thông tin người dùng
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy thông tin giỏ hàng
$stmt = $conn->prepare("
    SELECT c.id as cart_id, c.quantity, cl.id as listing_id, cl.price, cl.shipping, 
           cd.name, cd.image_filename, s.name as set_name, g.name as game_name,
           st.id as store_id, st.name as store_name
    FROM cart c
    JOIN card_listings cl ON c.card_listing_id = cl.id
    JOIN cards cd ON cl.card_id = cd.id
    JOIN sets s ON cd.set_id = s.id
    JOIN games g ON s.game_id = g.id
    JOIN stores st ON cl.store_id = st.id
    WHERE c.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính toán tổng
$subtotal = 0;
$shipping = 0;
$total_items = 0;
$stores = [];

foreach ($cart_items as $item) {
  $subtotal += $item['price'] * $item['quantity'];
  $total_items += $item['quantity'];

  // Nếu cửa hàng chưa được tính phí vận chuyển, thêm vào
  if (!isset($stores[$item['store_id']])) {
    $stores[$item['store_id']] = $item['shipping'];
    $shipping += $item['shipping'];
  }
}

$total = $subtotal + $shipping;
$package_count = count($stores);

include '../../includes/header.php';
?>

<link rel="stylesheet" href="/public/css/checkout.css">

<div class="container">
  <div class="checkout">
    <div class="progress-bar">
      <p><i class="fa-solid fa-lock"></i> CHECKOUT</p> <i class="fa-solid fa-angle-right"></i>
      <p class="progress-bar-active">1. SHIPPING</p> <i class="fa-solid fa-angle-right"></i>
      <p>2. REVIEW AND PAY</p>
    </div>
    <h1>Shipping Address</h1>
    <div class="checkout__container">
      <div class="checkout__form">
        <h2>Enter a Shipping Address</h2>
        <form action="" method="post">
          <div class="checkout__form-row">
            <p style="padding-left: 100px;">*First Name</p>
            <input type="text" name="first_name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>" required>
          </div>
          <div class="checkout__form-row">
            <p style="padding-left: 100px;">*Last Name</p>
            <input type="text" name="last_name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>" required>
          </div>
          <div class="checkout__form-row">
            <p style="padding-left: 80px;">*Address Line 1</p>
            <input type="text" name="address_line_1" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
          </div>
          <div class="checkout__form-row">
            <p style="padding-left: 80px;">*Address Line 2</p>
            <input type="text" name="address_line_2">
          </div>
          <div class="checkout__form-row">
            <p style="padding-left: 130px;">*City</p>
            <input type="text" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>" required>
          </div>
          <div class="checkout__form-row">
            <p style="padding-left: 43px;">*State/Province/Region</p>
            <input style="width: 30%;" type="text" name="state" value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>" required>
          </div>
          <div class="checkout__form-row">
            <p style="padding-left: 105px;">*Zip Code</p>
            <input style="width: 30%;" type="text" name="zip_code" value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>" required>
          </div>
          <div class="checkout__form-row">
            <p style="padding-left: 110px;">*Country</p>
            <input style="width: 50%;" type="text" name="country" value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>" required>
          </div>
          <div class="checkout__form-row">
            <p style="padding-left: 76px;">*Phone Number</p>
            <input style="width: 30%;" type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
          </div>
          <button type="submit">Continue</button>
        </form>
      </div>
      <div class="checkout__summary">
        <h2>Shopping Cart Summary</h2>
        <div class="checkout__summary-item">
          <p>Number of Packages:</p>
          <p><?php echo $package_count; ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Number of Items:</p>
          <p><?php echo $total_items; ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Items:</p>
          <p>$<?php echo number_format($subtotal, 2); ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Estimated Shipping:</p>
          <p>$<?php echo number_format($shipping, 2); ?></p>
        </div>
        <div class="checkout__summary-item">
          <p>Subtotal:</p>
          <p>$<?php echo number_format($total, 2); ?></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
<?php
ob_end_flush(); // Kết thúc và đẩy output buffer
?>