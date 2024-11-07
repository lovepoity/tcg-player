<?php
session_start();
require_once '../../../includes/db_connect.php';

if (!isset($_SESSION['store_id'])) {
  exit('Unauthorized');
}

$store_id = $_SESSION['store_id'];

$query = "
  SELECT 
    o.*,
    oi.status as item_status,
    oi.quantity,
    oi.price,
    c.name as card_name,
    c.image_filename,
    COUNT(oi.id) as total_items,
    SUM(oi.quantity * oi.price) as items_total
  FROM orders o
  JOIN order_items oi ON o.id = oi.order_id
  JOIN card_listings cl ON oi.card_listing_id = cl.id
  JOIN cards c ON cl.card_id = c.id
  WHERE oi.store_id = ?
  GROUP BY o.id 
  ORDER BY o.created_at DESC
";

try {
  $stmt = $conn->prepare($query);
  $stmt->execute([$store_id]);
  $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Error: " . $e->getMessage());
  exit('Database error occurred');
}
?>

<table>
  <thead>
    <tr>
      <th>Order ID</th>
      <th>Customer Name</th>
      <th>Items</th>
      <th>Total Amount</th>
      <th>Status</th>
      <th>Order Date</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($orders)): ?>
      <tr>
        <td colspan="7" class="store-orders__empty">No orders found</td>
      </tr>
    <?php else: ?>
      <?php foreach ($orders as $order): ?>
        <tr>
          <td>#<?php echo $order['id']; ?></td>
          <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
          <td><?php echo $order['total_items']; ?></td>
          <td>$<?php echo number_format($order['items_total'], 2); ?></td>
          <td>
            <span class="store-orders__status store-orders__status--<?php echo strtolower($order['item_status']); ?>">
              <?php echo $order['item_status']; ?>
            </span>
          </td>
          <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
          <td>
            <div class="store-orders__actions">
              <button class="store-orders__btn store-orders__btn--view" data-order-id="<?php echo $order['id']; ?>">
                <i class="fas fa-eye"></i> View
              </button>

              <?php if ($order['item_status'] == 'Processing'): ?>
                <button class="store-orders__btn store-orders__btn--update"
                  data-order-id="<?php echo $order['id']; ?>"
                  data-status="Picking">
                  Start Picking
                </button>
              <?php elseif ($order['item_status'] == 'Picking'): ?>
                <button class="store-orders__btn store-orders__btn--update"
                  data-order-id="<?php echo $order['id']; ?>"
                  data-status="Packing">
                  Start Packing
                </button>
              <?php elseif ($order['item_status'] == 'Packing'): ?>
                <button class="store-orders__btn store-orders__btn--update"
                  data-order-id="<?php echo $order['id']; ?>"
                  data-status="Shipping">
                  Ship Order
                </button>
              <?php endif; ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>