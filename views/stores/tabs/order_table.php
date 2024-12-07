<?php
if (!isset($orders)) {
  require_once '../../../includes/db_connect.php';
  $store_id = $_SESSION['store_id'];
  $query = "SELECT DISTINCT o.*, oi.status as order_status,
            (oi.quantity * cl.price + cl.shipping) as total_amount
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN card_listings cl ON oi.card_listing_id = cl.id
            WHERE oi.store_id = :store_id
            GROUP BY o.id
            ORDER BY o.created_at DESC LIMIT 10";

  $stmt = $conn->prepare($query);
  $stmt->execute([':store_id' => $store_id]);
  $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<?php if (count($orders) > 0): ?>
  <table>
    <thead>
      <tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Total Amount</th>
        <th>Status</th>
        <th>Order Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($orders as $order): ?>
        <tr>
          <td>#<?php echo str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></td>
          <td><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></td>
          <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
          <td>
            <span class="store-orders__status store-orders__status--<?php echo strtolower($order['order_status']); ?>">
              <?php echo $order['order_status']; ?>
            </span>
          </td>
          <td><?php echo date('d M, Y H:i', strtotime($order['created_at'])); ?></td>
          <td class="store-orders__actions">
            <button type="button" class="store-orders__btn store-orders__btn--view" data-order-id="<?php echo $order['id']; ?>">
              <i class="fa-solid fa-eye"></i>
            </button>
            <div class="store-orders__status-group">
              <select class="store-orders__select" id="status-<?php echo $order['id']; ?>">
                <option value="Processing" <?php echo $order['order_status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                <option value="Picking" <?php echo $order['order_status'] == 'Picking' ? 'selected' : ''; ?>>Picking</option>
                <option value="Packing" <?php echo $order['order_status'] == 'Packing' ? 'selected' : ''; ?>>Packing</option>
                <option value="Shipping" <?php echo $order['order_status'] == 'Shipping' ? 'selected' : ''; ?>>Shipping</option>
                <option value="Delivered" <?php echo $order['order_status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                <option value="Completed" <?php echo $order['order_status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                <option value="Cancelled" <?php echo $order['order_status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
              </select>
              <button type="button" class="store-orders__btn store-orders__btn--update" onclick="updateOrderStatus(<?php echo $order['id']; ?>)">
                Update
              </button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <div class="store-orders__empty">No orders found.</div>
<?php endif; ?>