<?php
include '../../includes/header.php';
include '../../includes/db_connect.php';

// Helper functions
function e($string)
{
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function formatNumber($number, $decimals = 2)
{
  return number_format($number, $decimals, '.', ',');
}

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
  header("Location: /views/cart/empty_cart.php");
  exit;
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin giỏ hàng
$stmt = $conn->prepare("
    SELECT c.id as cart_id, c.quantity, cl.id as listing_id, cl.price, cl.shipping, 
           cd.name, cd.image_filename, cd.set_id, s.name as set_name, g.name as game_name,
           cd.rarity, cd.card_number, st.id as store_id, st.name as store_name,
           cl.quantity as available_quantity
    FROM cart c
    JOIN card_listings cl ON c.card_listing_id = cl.id
    JOIN cards cd ON cl.card_id = cd.id
    JOIN sets s ON cd.set_id = s.id
    JOIN games g ON s.game_id = g.id
    JOIN stores st ON cl.store_id = st.id
    WHERE c.user_id = :user_id
    ORDER BY st.id, cd.name
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kiểm tra xem giỏ hàng có trống không
$cart_is_empty = empty($cart_items);

if ($cart_is_empty) {
  // Hiển thị nội dung giỏ hàng trống
?>
  <div class="cart">
    <div class="shopping__cart">
      <div class="cart__container">
        <h1>Shopping Cart</h1>
        <figure>
          <img src="/public/images/img__empty-cart.svg" alt="Empty Cart">
          <figcaption>Your cart is empty.</figcaption>
        </figure>
        <p>Sign in to see items from a previous visit.</p>
        <div class="btns__container">
          <button><a href="/views/card_all.php">Continue Shopping</a></button>
        </div>
      </div>
    </div>
  </div>
<?php
} else {
  // Hiển thị nội dung giỏ hàng có sản phẩm
  // Nhóm các mục theo cửa hàng
  $grouped_items = [];
  foreach ($cart_items as $item) {
    $store_id = $item['store_id'];
    if (!isset($grouped_items[$store_id])) {
      $grouped_items[$store_id] = [
        'store_name' => $item['store_name'],
        'items' => [],
        'subtotal' => 0,
        'shipping' => 0
      ];
    }
    $grouped_items[$store_id]['items'][] = $item;
    $grouped_items[$store_id]['subtotal'] += $item['price'] * $item['quantity'];
    $grouped_items[$store_id]['shipping'] = max($grouped_items[$store_id]['shipping'], $item['shipping']);
  }

  // Tính tổng
  $total_packages = count($grouped_items);
  $total_items = 0;
  $subtotal = 0;
  $total_shipping = 0;

  foreach ($grouped_items as $package) {
    $total_items += array_sum(array_column($package['items'], 'quantity'));
    $subtotal += $package['subtotal'];
    $total_shipping += $package['shipping'];
  }

  $grand_total = $subtotal + $total_shipping;
?>

  <div class="container">
    <div class="cart">
      <div class="shopping__cart">
        <div class="cart__container">
          <h1>Shopping Cart</h1>
          <?php $package_count = 0;
          foreach ($grouped_items as $store_id => $package): $package_count++; ?>
            <div class="package-tab" data-store-id="<?php echo $store_id; ?>">
              <div class="package-tab__title">
                <span>Package (<?php echo $package_count; ?> of <?php echo $total_packages; ?>)</span>
              </div>
              <div class="package-tab__content">
                <section class="package-tab__stacked-content">
                  <h1><a href="#"><?php echo e($package['store_name']); ?></a></h1>
                  <?php foreach ($package['items'] as $item): ?>
                    <div class="package-tab__content-wrapper">
                      <div class="package-tab__image-wrapper">
                        <img src="/public/images/product/<?php echo e($item['image_filename']); ?>" alt="<?php echo e($item['name']); ?>">
                      </div>
                      <div class="package-tab__item-details">
                        <div class="package-tab__item-details-wrapper beet--ween">
                          <div class="package-tab__expanded-details-container">
                            <p class="package-tab__name"><?php echo e($item['name']); ?></p>
                            <p class="package-tab__desc">
                              <span><?php echo e($item['set_name']); ?></span>,
                              <span><?php echo e($item['game_name']); ?></span>,
                              <span><?php echo e($item['rarity']); ?></span>,
                              <span><?php echo e($item['card_number']); ?></span>
                            </p>
                          </div>
                          <div class="package-tab__item-sales-info">
                            <p class="package-tab__item-sales-info-text">Near Mint Foil</p>
                            <p class="package-tab__item-sales-info-price">$<?php echo formatNumber($item['price']); ?></p>
                          </div>
                        </div>
                        <div class="package-tab__item-actions">
                          <select name="quantity" class="quantity-select" data-cart-id="<?php echo $item['cart_id']; ?>" data-price="<?php echo $item['price']; ?>" data-shipping="<?php echo $item['shipping']; ?>">
                            <?php for ($i = 1; $i <= $item['available_quantity']; $i++): ?>
                              <option value="<?php echo $i; ?>" <?php echo ($i == $item['quantity']) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                          </select>
                          <span class="item__listing">
                            of <?php echo $item['available_quantity']; ?>
                          </span>
                          <button class="button--cart remove-item" data-cart-id="<?php echo $item['cart_id']; ?>">Remove</button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </section>
                <section class="package-tab__summary">
                  <div class="package-tab__summary-wrapper beet--ween">
                    <span>Package Subtotal:</span>
                    <span class="package-subtotal">$<?php echo formatNumber($package['subtotal']); ?></span>
                  </div>
                  <div class="package-tab__summary-item beet--ween">
                    <span>Items</span>
                    <span class="package-item-count"><?php echo array_sum(array_column($package['items'], 'quantity')); ?></span>
                  </div>
                  <div class="package-tab__summary-item beet--ween">
                    <span>Total</span>
                    <span class="package-total">$<?php echo formatNumber($package['subtotal']); ?></span>
                  </div>
                  <div class="package-tab__summary-item beet--ween">
                    <span>Shipping</span>
                    <span class="package-shipping">$<?php echo formatNumber($package['shipping']); ?></span>
                  </div>
                  <div class="package-tab__summary-item-shipping-option">
                    <span style="display: flex; align-items: center;"><input type="radio" checked> Standard</span>
                    <span class="package-tab__summary-item-shipping-option-price">$<?php echo formatNumber($package['shipping']); ?></span>
                    <span class="package-tab__summary-item-shipping-option-days">7-10 business days</span>
                  </div>
                  <div class="package-tab__remove-button">
                    <button class="button--cart remove-package" data-store-id="<?php echo $store_id; ?>">Remove Package</button>
                  </div>
                </section>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- CART SUMMARY -->
        <div class="cart__summary">
          <span>Cart Summary</span>
          <section class="item__breakdown">
            <div class="item__breakdown-item">
              <p>Packages</p>
              <p><?php echo $total_packages; ?></p>
            </div>
            <div class="item__breakdown-item">
              <p>Items</p>
              <p><?php echo $total_items; ?></p>
            </div>
            <div class="item__breakdown-item">
              <p>Item Total</p>
              <p>$<?php echo formatNumber($subtotal); ?></p>
            </div>
            <div class="item__breakdown-item">
              <p>Estimated Shipping</p>
              <p>$<?php echo formatNumber($total_shipping); ?></p>
            </div>
          </section>
          <div class="cart__subtotal">
            <p>Subtotal <span>$<?php echo formatNumber($grand_total); ?></span></p>
            <span>Taxes calculated at checkout</span>
          </div>
          <button class="checkout__button">Check Out</button>
          <button class="paypal__button"><img src="/public/images/paypal.webp" alt="Paypal"></button>
          <!-- PAYPAL -->
          <div class="details-info__paypal">
            <img src="/public/images/paypal.webp" alt="Paypal">Pay in 4 interest-free
            payments on purchases of $30-$1,500. <a style="text-decoration: underline;" href="#">Learn More</a>
          </div>
          <!-- END PAYPAL -->
          <button class="clear__cart button--cart">Clear Cart</button>
        </div>
      </div>
    </div>
  </div>
<?php
}
?>

<div id="toast" class="toast">
  <div class="toast__content">
    <p id="toast-message"></p>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/public/js/cart.js"></script>

<?php include '../../includes/footer.php'; ?>