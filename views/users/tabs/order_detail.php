<?php
session_start();
require_once __DIR__ . '/../../../includes/db_connect.php';
require_once __DIR__ . '/../functions/orders.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
  exit('Invalid request');
}

$userId = $_SESSION['user_id'];
$orderId = $_GET['order_id'];
$orders = getOrdersByUserId($userId);
$order = array_filter($orders, function ($o) use ($orderId) {
  return $o['id'] == $orderId;
});
$order = reset($order);

if (!$order) {
  exit('Order not found');
}
?>
<link rel="stylesheet" href="/views/users/assets/css/order.css">
<div class="user-content">
  <h2 class="user-content__title">Order Details</h2>
  <div class="order-detail">
    <button class="order-detail__back-btn">
      <i class="fa-solid fa-arrow-left"></i> Back to Order History
    </button>

    <div class="confirm__block margin-bottom">
      <h2>Order Details</h2>
      <div class="confirm__box">
        <div class="order-details__content-item text--custom">
          <p>Order ID </p><span><?php echo $order['id']; ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Items Quantity </p><span><?php echo count($order['items']); ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Payment Method </p><span><?php echo $order['payment_method']; ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Status </p><span style="text-decoration: underline;"><?php echo ucfirst($order['status']); ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Shipping Fee </p><span>$<?php echo number_format($order['shipping_fee'], 2); ?></span>
        </div>
        <div class="order-details__content-item text--custom">
          <p>Total Amount </p><span>$<?php echo number_format($order['total_amount'], 2); ?></span>
        </div>
      </div>
    </div>
    <!-- Cancel Order Button -->
    <?php if ($order['status'] === 'Processing'): ?>
      <button class="order-detail__cancel-btn" data-order-id="<?php echo $order['id']; ?>">
        Cancel Order
      </button>
    <?php endif; ?>
    <!-- Modal Cancel Order -->
    <div class="modal" id="cancelOrderModal">
      <div class="modal__content">
        <h3>Cancel Order</h3>
        <p>Are you sure you want to cancel this order?</p>
        <div class="modal__actions">
          <button class="modal__btn modal__btn--confirm">Yes, Cancel Order</button>
          <button class="modal__btn modal__btn--cancel">No</button>
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
    <div class="confirm__table">
      <h2>Order Items</h2>
      <?php
      $grouped_items = [];
      foreach ($order['items'] as $item) {
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
                    <?php if (isset($item['image_filename'])): ?>
                      <img src="/public/images/product/<?php echo htmlspecialchars($item['image_filename']); ?>"
                        alt="<?php echo isset($item['name']) ? htmlspecialchars($item['name']) : 'Product Image'; ?>">
                    <?php endif; ?>
                    <div>
                      <?php if (isset($item['card_id'])): ?>
                        <a target="_blank" href="/views/card_details.php?id=<?php echo $item['card_id']; ?>">
                          <p style="color: var(--blue-color); font-weight: 500;">
                            <?php echo isset($item['card_name']) ? htmlspecialchars($item['card_name']) : 'Unknown Product'; ?>
                          </p>
                        </a>
                      <?php endif; ?>
                      <p>
                        <?php
                        $set_name = isset($item['set_name']) ? htmlspecialchars($item['set_name']) : 'Unknown Set';
                        $game_name = isset($item['game_name']) ? htmlspecialchars($item['game_name']) : 'Unknown Game';
                        echo $set_name . ' - ' . $game_name;
                        ?>
                      </p>
                    </div>
                  </div>
                </td>
                <td>
                  <div><?php echo isset($item['rarity']) ? htmlspecialchars($item['rarity']) : 'Unknown'; ?></div>
                  <div><?php echo isset($item['card_number']) ? htmlspecialchars($item['card_number']) : 'Unknown'; ?></div>
                  <div><?php echo isset($item['subtype']) ? htmlspecialchars($item['subtype']) : 'Unknown'; ?></div>
                </td>
                <td>$<?php echo isset($item['price']) ? number_format($item['price'], 2) : '0.00'; ?></td>
                <td>$<?php echo isset($item['shipping']) ? number_format($item['shipping'], 2) : '0.00'; ?></td>
                <td><?php echo isset($item['quantity']) ? $item['quantity'] : 0; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endforeach; ?>
    </div>
  </div>
</div>