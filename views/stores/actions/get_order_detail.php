<?php
session_start();
require_once '../../../includes/db_connect.php';

$store_id = $_SESSION['store_id'];
$order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;

// Lấy thông tin store
$storeQuery = "SELECT name FROM stores WHERE id = :store_id";
$stmt = $conn->prepare($storeQuery);
$stmt->execute([':store_id' => $store_id]);
$store = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy thông tin order và items của store hiện tại
$query = "SELECT o.*, 
          oi.quantity, oi.status as item_status,
          c.name as card_name, c.image_filename, c.rarity, c.card_number,
          s.name as set_name,
          cl.price, cl.shipping
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN card_listings cl ON oi.card_listing_id = cl.id
          JOIN cards c ON cl.card_id = c.id
          JOIN sets s ON c.set_id = s.id
          WHERE oi.store_id = :store_id AND o.id = :order_id";

$stmt = $conn->prepare($query);
$stmt->execute([
  ':store_id' => $store_id,
  ':order_id' => $order_id
]);

$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($items) > 0) {
  $order = $items[0];
?>
  <div class="order-detail">
    <h2>Order #<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?></h2>

    <div class="order-detail__section">
      <h3>Customer Information</h3>
      <div class="order-detail__info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
      </div>
    </div>

    <div class="order-detail__section">
      <h3>Shipping Address</h3>
      <div class="order-detail__info">
        <p><?php echo htmlspecialchars($order['address']); ?></p>
        <p><?php
            $location = [];
            if (!empty($order['city'])) $location[] = $order['city'];
            if (!empty($order['state'])) $location[] = $order['state'];
            echo htmlspecialchars(implode(', ', $location));
            ?></p>
        <?php if (!empty($order['country'])): ?>
          <p><?php echo htmlspecialchars($order['country']); ?></p>
        <?php endif; ?>
      </div>
    </div>

    <div class="order-detail__section">
      <h3>Order Information</h3>
      <div class="order-detail__info">
        <p>
          <strong>Status:</strong>
          <span class="order-detail__status order-detail__status--<?php echo strtolower($order['item_status']); ?>">
            <?php echo $order['item_status']; ?>
          </span>
        </p>
        <p><strong>Order Date:</strong> <?php echo date('d M, Y H:i', strtotime($order['created_at'])); ?></p>
      </div>
    </div>

    <h2>Order Items</h2>
    <table class="order-detail__table">
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
        <?php
        $total = 0;
        foreach ($items as $item):
          $item_total = $item['price'] * $item['quantity'];
          $total += $item_total + $item['shipping'];
        ?>
          <tr>
            <td>
              <div class="order-detail__card">
                <img src="/public/images/product/<?php echo $item['image_filename']; ?>"
                  alt="<?php echo htmlspecialchars($item['card_name']); ?>">
                <div class="order-detail__card-info">
                  <h4><?php echo htmlspecialchars($item['card_name']); ?></h4>
                  <p><?php echo htmlspecialchars($item['set_name']); ?></p>
                </div>
              </div>
            </td>
            <td>
              <div class="order-detail__details">
                <p><?php echo $item['rarity']; ?></p>
                <p><?php echo $item['card_number']; ?></p>
                <p><?php echo htmlspecialchars($item['set_name']); ?></p>
              </div>
            </td>
            <td class="order-detail__price">$<?php echo number_format($item_total, 2); ?></td>
            <td class="order-detail__shipping">$<?php echo number_format($item['shipping'], 2); ?></td>
            <td class="order-detail__quantity"><?php echo $item['quantity']; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2" class="order-detail__total-label">Total:</td>
          <td colspan="3" class="order-detail__total-value">$<?php echo number_format($total, 2); ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
<?php
} else {
  echo '<div class="order-detail__empty">Order not found.</div>';
}
?>