<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
  header('Location: /index.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'];

// Get order information
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = :order_id AND user_id = :user_id");
$stmt->execute([':order_id' => $order_id, ':user_id' => $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  header('Location: /index.php');
  exit;
}

// Get order details
$stmt = $conn->prepare("
    SELECT oi.*, c.name, c.image_filename
    FROM order_items oi
    JOIN card_listings cl ON oi.card_listing_id = cl.id
    JOIN cards c ON cl.card_id = c.id
    WHERE oi.order_id = :order_id
");
$stmt->execute([':order_id' => $order_id]);
$order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container">
  <h1>Order Confirmed</h1>
  <p>Thank you for your order. Your order number is: <?php echo $order_id; ?></p>

  <h2>Order Details</h2>
  <?php foreach ($order_items as $item): ?>
    <div class="order-item">
      <img src="/public/images/product/<?php echo htmlspecialchars($item['image_filename']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
      <p><?php echo htmlspecialchars($item['name']); ?></p>
      <p>Quantity: <?php echo $item['quantity']; ?></p>
      <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
    </div>
  <?php endforeach; ?>

  <p>Total: $<?php echo number_format($order['total_amount'], 2); ?></p>
  <p>Shipping Fee: $<?php echo number_format($order['shipping_fee'], 2); ?></p>

  <h2>Shipping Address</h2>
  <p><?php echo htmlspecialchars($order['address']); ?></p>
  <p><?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' ' . $order['postal_code']); ?></p>
  <p><?php echo htmlspecialchars($order['country']); ?></p>

  <p>We will send a confirmation email to <?php echo htmlspecialchars($order['email']); ?></p>
</div>

<?php include '../../includes/footer.php'; ?>