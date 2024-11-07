<?php
session_start();
require_once '../../../includes/db_connect.php';

if (!isset($_SESSION['store_id'])) {
  exit('Unauthorized');
}

$store_id = $_SESSION['store_id'];
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

// Get order details
$query = "
  SELECT o.*, oi.*, c.name as card_name, c.image_filename
  FROM orders o
  JOIN order_items oi ON o.id = oi.order_id
  JOIN card_listings cl ON oi.card_listing_id = cl.id
  JOIN cards c ON cl.card_id = c.id
  WHERE oi.store_id = ? AND o.id = ?
";

$stmt = $conn->prepare($query);
$stmt->execute([$store_id, $order_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($items)) {
  exit('Order not found');
}

$order = $items[0];
?>

<div class="order-details">
  <h2>Order #<?php echo $order_id; ?></h2>

  <div class="order-details__section">
    <h3>Customer Information</h3>
    <p>Name: <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
    <p>Email: <?php echo htmlspecialchars($order['email']); ?></p>
    <p>Phone: <?php echo htmlspecialchars($order['phone']); ?></p>
  </div>

  <div class="order-details__section">
    <h3>Shipping Address</h3>
    <p><?php echo htmlspecialchars($order['address']); ?></p>
    <p><?php echo htmlspecialchars($order['city'] . ', ' . $order['state'] . ' ' . $order['postal_code']); ?></p>
    <p><?php echo htmlspecialchars($order['country']); ?></p>
  </div>

  <div class="order-details__section">
    <h3>Order Items</h3>
    <table>
      <thead>
        <tr>
          <th>Image</th>
          <th>Card Name</th>
          <th>Quantity</th>
          <th>Price</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><img src="/public/images/cards/<?php echo $item['image_filename']; ?>" width="50"></td>
            <td><?php echo htmlspecialchars($item['card_name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>$<?php echo number_format($item['price'], 2); ?></td>
            <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="order-details__section">
    <h3>Order Summary</h3>
    <p>Subtotal: $<?php echo number_format($order['total_amount'] - $order['shipping_fee'], 2); ?></p>
    <p>Shipping Fee: $<?php echo number_format($order['shipping_fee'], 2); ?></p>
    <p>Total: $<?php echo number_format($order['total_amount'], 2); ?></p>
  </div>
</div>