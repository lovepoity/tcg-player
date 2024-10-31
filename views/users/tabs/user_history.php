<?php
session_start();
require_once __DIR__ . '/../../../includes/db_connect.php';
require_once __DIR__ . '/../functions/orders.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: /login');
  exit;
}

$userId = $_SESSION['user_id'];
$orders = getOrdersByUserId($userId);

// Tính tổng số orders và tổng tiền
$totalOrders = count($orders);
$totalSpent = array_sum(array_column($orders, 'total_amount'));
?>
<link rel="stylesheet" href="/views/users/assets/css/order.css">
<div class="user-content">
  <h2 class="user-content__title">Order History</h2>
  <div class="user-content__orders">
    <!-- ORDER CONS -->
    <div class="order__cons">
      <div class="order__cons-item">
        <p><?php echo $totalOrders; ?></p>
        <p>Orders</p>
      </div>
      <div class="order__cons-item">
        <p>$<?php echo number_format($totalSpent, 2); ?></p>
        <p>Total Spent</p>
      </div>
    </div>

    <!-- ORDER ITEMS -->
    <?php foreach ($orders as $order): ?>
      <div class="order__items">
        <div class="order__item--left">
          <?php if (!empty($order['items'])): ?>
            <img src="/public/images/product/<?php echo $order['items'][0]['image_filename']; ?>"
              alt="<?php echo $order['items'][0]['card_name']; ?>">
          <?php endif; ?>
        </div>
        <div class="order__item--right">
          <div class="order__title">
            <p>
              <?php
              $totalItems = count($order['items']);
              if ($totalItems == 1) {
                $firstItem = $order['items'][0];
                echo $firstItem['card_name'] . ' (' . $firstItem['card_number'] . ')';
              } else {
                $items = [];
                foreach ($order['items'] as $item) {
                  $items[] = $item['card_name'] . ' (' . $item['card_number'] . ')';
                }
                echo implode(', ', $items);
              }
              ?>
            </p>
            <p><?php echo date('Y-m-d H:i:s', strtotime($order['created_at'])); ?></p>
          </div>
          <div class="order__status">
            <p>Order ID: <?php echo $order['id']; ?> - &nbsp;</p>
            <p> Cards: <?php echo array_sum(array_column($order['items'], 'quantity')); ?></p>
          </div>
          <span class="order__status--active" data-status="<?php echo ucfirst($order['status']); ?>">
            <?php echo ucfirst($order['status']); ?>
          </span>
          <div class="order__total">
            <p>Total: <span>$<?php echo number_format($order['total_amount'], 2); ?></span></p>
            <button class="order__total--btn-item" data-order-id="<?php echo $order['id']; ?>">
              View Details
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>