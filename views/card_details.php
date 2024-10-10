<?php
include '../includes/header.php';
include '../includes/db_connect.php';
include '../includes/card_queries.php';

// Hàm helper để hiển thị HTML an toàn
function e($string)
{
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Hàm helper để định dạng số
function formatNumber($number, $decimals = 2)
{
  return number_format($number, $decimals);
}

// Lấy id từ tham số URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
  echo "Invalid card ID.";
  include '../includes/footer.php';
  exit;
}

// Lấy thông tin card, set và game
$card = get_card_details($conn, $id);

if (!$card) {
  echo "Card not found.";
  include '../includes/footer.php';
  exit;
}

// Lấy danh sách các listing cho card này
$listings = get_card_listings($conn, $id, 5);

// Lấy 12 sản phẩm ngẫu nhiên từ cùng set, ngoại trừ sản phẩm hiện tại
$recommended_cards = get_recommended_cards($conn, $card['set_id'], $id, 12);

// Lấy thông tin về listing có giá cao nhất
$highest_price_listing = get_highest_price_listing($conn, $id);

?>
<!-- HTML -->
<div class="card__details">
  <div class="navbar__sub-page">
    <!-- FILTER -->
    <div class="filter">
      <div style="display: flex; align-items: center; background-color: #F7F7F8;" class="filter__item">
        All Filters <span>1</span>
      </div>
      <div class="filter__item">
        Condition <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item">
        Printing <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item filter__style">
        English <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item">
        Clear Filter
      </div>
    </div>
    <!-- END FILTER -->
    <div class="listing--price">
      <?php if ($card['total_stores'] > 0): ?>
        <p style="font-weight: bold; text-decoration: underline;" class="product__listing"><?php echo $card['total_stores']; ?> Listings</p>
        <p>As low as $<?php echo formatNumber($card['market_price'], 2); ?></p>
      <?php else: ?>
        <p style="font-weight: bold; text-decoration: underline;" class="product__listing">Out of Stock</p>
      <?php endif; ?>
    </div>
  </div>
  <!-- END NAVBAR SUB PAGE -->
  <!-- LOCATION -->
  <div class="sub__location">
    <a href="#">All Categories</a>
    <i class="fa-solid fa-chevron-right"></i>
    <a href="/views/card_all.php?game_id=<?php echo $card['game_id']; ?>"><?php echo e($card['game_name']); ?></a>
    <i class="fa-solid fa-chevron-right"></i>
    <a href="/views/card_all.php?set_id=<?php echo $card['set_id']; ?>"><?php echo e($card['set_name']); ?></a>
    <i class="fa-solid fa-chevron-right"></i>
    <p><?php echo e($card['name']); ?></p>
  </div>
  <!-- END LOCATION -->
  <!-- CARD DETAILS -->
  <div class="card__details-info">
    <div class="details-info">
      <!-- DETAIL INFO LEFT -->
      <div class="details-info__left">
        <div class="details-info__image">
          <img src="/public/images/product/<?php echo e($card['image_filename']); ?>" alt="<?php echo e($card['name']); ?>">
        </div>
      </div>
      <!-- DETAIL INFO RIGHT -->
      <div class="details-info__right">
        <h1><?php echo e($card['name']); ?> - <?php echo e($card['set_name']); ?></h1>
        <p class="details-info__set"><?php echo e($card['set_name']); ?></p>
        <!-- DETAIL INFO -->
        <div class="detail-info__breakdown">
          <div class="detail-info__breakdown-item">
            <p class="details-info__all"><b>Product Details</b><br><?php echo e($card['product_details']); ?></p>
            <p class="details-info__all"><b>Rarity: </b><?php echo e($card['rarity']); ?></p>
            <p class="details-info__all"><b>Card Number: </b><?php echo e($card['card_number']); ?></p>
            <p class="details-info__all"><b>Color: </b><?php echo e($card['color']); ?></p>
            <p class="details-info__all"><b>Card Type: </b><?php echo e($card['card_type']); ?></p>
            <p class="details-info__all"><b>Cost: </b><?php echo e($card['cost']); ?></p>
            <p class="details-info__all"><b>Power: </b><?php echo e($card['power']); ?></p>
            <p class="details-info__all"><b>Subtype(s): </b><?php echo e($card['subtype']); ?></p>
            <p class="details-info__all"><b>Attribute: </b><?php echo e($card['attribute']); ?></p>
            <p class="details-info__all"><b>Artist: </b><a href="#"><?php echo e($card['artist']); ?></a></p>
          </div>
          <div class="detail-info__breakdown-item">
            <div class="details-info__spotlight">
              <p class="details-info__spotlight-title">Near Mint Foil</p>
              <?php if ($highest_price_listing): ?>
                <p class="details-info__spotlight-price">$<?php echo formatNumber($highest_price_listing['price'], 2); ?>
                  <span style="font-size: 1.2rem; font-weight: 400;">shipping:
                    <?php echo ($highest_price_listing['shipping'] > 0) ? '$' . formatNumber($highest_price_listing['shipping'], 2) : 'included'; ?>
                  </span>
                </p>
                <p class="details-info__spotlight-sold">Sold by <a href="#"><?php echo e($highest_price_listing['store_name']); ?></a></p>
                <!-- QUANTITY -->
                <div class="details-info__spotlight-btn">
                  <?php
                  $quantity = $highest_price_listing['quantity'];
                  ?>
                  <select name="quantity" id="quantity" <?php echo ($quantity === 0) ? 'disabled' : ''; ?>>
                    <?php if ($quantity === 0): ?>
                      <option value="0">0</option>
                    <?php else: ?>
                      <?php
                      $max_quantity = min($quantity, 100); // Giới hạn tối đa 100 hoặc số lượng có sẵn
                      for ($i = 1; $i <= $max_quantity; $i++):
                      ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                      <?php endfor; ?>
                    <?php endif; ?>
                  </select>
                  <span style="font-weight: bold; text-decoration: underline; color: #0f0f0f;" class="product__listing">
                    of <?php echo $quantity; ?>
                  </span>
                  <button <?php echo ($quantity === 0) ? 'disabled' : ''; ?>>
                    <?php echo ($quantity === 0) ? 'Out of Stock' : 'Add to Cart'; ?>
                  </button>
                </div>
              <?php else: ?>
                <p class="details-info__spotlight-price">Currently Unavailable</p>
                <p class="details-info__spotlight-sold">No sellers available at this time</p>
                <div class="details-info__spotlight-btn">
                  <button disabled>Out of Stock</button>
                </div>
              <?php endif; ?>
              <!-- END QUANTITY -->
              <!-- PAYPAL -->
              <div class="details-info__paypal">
                <img src="/public/images/paypal.webp" alt="Paypal">Pay in 4 interest-free
                payments on purchases of $30-$1,500. <a style="text-decoration: underline;" href="#">Learn More</a>
              </div>
              <!-- END PAYPAL -->
            </div>
            <!-- ALL LISTING -->
            <div class="details-info__all-listing">
              <a class="details-info__all-listing-link" href="#listing">View <?php echo $card['total_stores']; ?> Other Listings</a>
              <p class="details-info__all-listing-price">As low as $<?php echo formatNumber($card['market_price'], 2); ?></p>
            </div>
            <!-- END ALL LISTING -->
            <!-- USER ACTION -->
            <div class="details-info__user-action">
              <a style="color: black;" href="#">Sell this <i class="fa-regular fa-share-from-square"></i></a>
              <div style="color: #5E616C; cursor: pointer;" href="#">Report a problem <i style="filter: invert(100%);" class="fa-solid fa-circle-exclamation"></i></div>
            </div>
            <!-- END USER ACTION -->
          </div>
        </div>
        <!-- END DETAIL INFO -->
      </div>
    </div>
    <!-- END CARD DETAILS -->
    <div class="card__details-recommend">
      <!-- LISTING -->
      <div id="listing" class="card__detail-listing">
        <?php if ($card['total_stores'] > 0 && $card['market_price'] > 0): ?>
          <div class="listing__quantity"><?php echo $card['total_stores']; ?> Listings</div>
          <div class="listing__price">
            As low as $<?php echo formatNumber($card['market_price'], 2); ?>
          </div>
        <?php else: ?>
          <div class="listing__quantity">No Listings Available</div>
          <div class="listing__price">
            This card is currently out of stock
          </div>
        <?php endif; ?>
        <?php foreach ($listings as $listing): ?>
          <div class="listing__store">
            <div class="listing__store-name listing__store-item"><?php echo e($listing['store_name']); ?></div>
            <div class="listing__store-info listing__store-item">
              <p><?php echo e($listing['condition'] ?? 'Near Mint Foil'); ?></p>
              <div class="listing__store-info-price">$<?php echo formatNumber($listing['price'], 2); ?></div>
              <div class="listing__store-info-shipping">
                <?php if ($listing['shipping_cost'] > 0): ?>
                  + $<?php echo formatNumber($listing['shipping_cost'], 2); ?> Shipping
                <?php else: ?>
                  Free Shipping
                <?php endif; ?>
              </div>
            </div>
            <div class="listing__store-quantity listing__store-item">
              <select name="quantity" id="quantity_<?php echo $listing['id']; ?>" <?php echo ($listing['quantity'] === 0) ? 'disabled' : ''; ?>>
                <?php if ($listing['quantity'] === 0): ?>
                  <option value="0">0</option>
                <?php else: ?>
                  <?php
                  $max_quantity = min($listing['quantity'], 100); // Giới hạn tối đa 100 hoặc số lượng có sẵn
                  for ($i = 1; $i <= $max_quantity; $i++):
                  ?>
                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                  <?php endfor; ?>
                <?php endif; ?>
              </select>
              <span style="font-weight: bold; text-decoration: underline; color: #0f0f0f;" class="product__listing">
                of <?php echo $listing['quantity']; ?>
              </span>
              <button <?php echo ($listing['quantity'] === 0) ? 'disabled' : ''; ?>>
                <?php echo ($listing['quantity'] === 0) ? 'Out of Stock' : 'Add to Cart'; ?>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <!-- END LISTING -->
      <!-- RECOMMEND -->
      <div class="card__recommend">
        <h2>Customers Also Purchased</h2>
        <div class="slideshow-container">
          <div class="slideshow-wrapper">
            <?php for ($i = 0; $i < 3; $i++): ?>
              <div class="slideshow-page">
                <div class="product-grid">
                  <?php for ($j = $i * 4; $j < ($i + 1) * 4 && $j < count($recommended_cards); $j++):
                    $rec_card = $recommended_cards[$j];
                  ?>
                    <div class="product">
                      <a href="/views/card_details.php?id=<?php echo $rec_card['id']; ?>">
                        <div class="product__img">
                          <img loading="lazy" src="/public/images/product/<?php echo e($rec_card['image_filename']); ?>" alt="<?php echo e($rec_card['name']); ?>">
                        </div>
                        <div class="product__info">
                          <p class="product__name"><?php echo e($rec_card['name']); ?></p>
                          <p class="product__set"><?php echo e($rec_card['set_name']); ?></p>
                          <p class="product__rarity"><?php echo e($rec_card['rarity']); ?></p>
                          <p class="product__card-number"><?php echo e($rec_card['card_number']); ?></p>
                          <?php if ($rec_card['total_quantity'] > 0): ?>
                            <p class="product__listing"><?php echo $card['total_stores']; ?> listings from</p>
                            <p class="product__price">
                              $<?php echo formatNumber($rec_card['market_price'], 2); ?>
                            </p>
                            <p class="product__market-price">Market Price: <span style="color: #07772D;">$<?php echo formatNumber($rec_card['avg_price'], 2); ?></span></p>
                          <?php else: ?>
                            <p class="product__listing">Out of Stock</p>
                          <?php endif; ?>
                        </div>
                      </a>
                    </div>
                  <?php endfor; ?>
                </div>
              </div>
            <?php endfor; ?>
          </div>
        </div>
        <div class="slideshow-nav">
          <span class="nav-dot active" data-slide="0"></span>
          <span class="nav-dot" data-slide="1"></span>
          <span class="nav-dot" data-slide="2"></span>
        </div>
      </div>
    </div>
    <script src="/public/js/sub_page.js"></script>
    <!-- Phần recommend giữ nguyên như cũ -->
    <?php
    include '../includes/footer.php';
    ?>