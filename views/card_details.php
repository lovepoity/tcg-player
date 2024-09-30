<?php
include '../includes/header.php';
include '../includes/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT c.*, s.name AS set_name, 
          MIN(cl.price) AS market_price,
          MAX(cl.price) AS max_price,
          SUM(cl.quantity) AS total_quantity
          FROM cards c
          JOIN sets s ON c.set_id = s.id 
          LEFT JOIN card_listings cl ON c.id = cl.card_id
          WHERE c.id = ?
          GROUP BY c.id";
try {
  $stmt = $conn->prepare($query);
  $stmt->execute([$id]);
  $card = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$card) {
    echo "Card not found.";
    include '../includes/footer.php';
    exit;
  }
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  exit;
}
?>
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
      <?php if ($card['total_quantity'] > 0): ?>
        <p style="font-weight: bold; text-decoration: underline;" class="product__listing"><?php echo $card['total_quantity']; ?> Listings</p>
        <p>As low as $<?php echo number_format($card['market_price'], 2); ?></p>
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
    <a href="#">One Piece Card Game</a>
    <i class="fa-solid fa-chevron-right"></i>
    <a href="#">Two Legends</a>
    <i class="fa-solid fa-chevron-right"></i>
    <p><?php echo htmlspecialchars($card['name']); ?></p>
  </div>
  <!-- END LOCATION -->
  <!-- CARD DETAILS -->
  <div class="card__details-info">
    <div class="details-info">
      <!-- DETAIL INFO LEFT -->
      <div class="details-info__left">
        <div class="details-info__image">
          <img src="/public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
        </div>
      </div>
      <!-- DETAIL INFO RIGHT -->
      <div class="details-info__right">
        <h1><?php echo htmlspecialchars($card['name']); ?> - <?php echo htmlspecialchars($card['set_name']); ?></h1>
        <p class="details-info__set"><?php echo htmlspecialchars($card['set_name']); ?></p>
        <!-- DETAIL INFO -->
        <div class="detail-info__breakdown">
          <div class="detail-info__breakdown-item">
            <p class="details-info__all"><b>Product Details</b><br><?php echo htmlspecialchars($card['product_details']); ?></p>
            <p class="details-info__all"><b>Rarity: </b><?php echo htmlspecialchars($card['rarity']); ?></p>
            <p class="details-info__all"><b>Card Number: </b><?php echo htmlspecialchars($card['card_number']); ?></p>
            <p class="details-info__all"><b>Color: </b><?php echo htmlspecialchars($card['color']); ?></p>
            <p class="details-info__all"><b>Card Type: </b><?php echo htmlspecialchars($card['card_type']); ?></p>
            <p class="details-info__all"><b>Cost: </b><?php echo htmlspecialchars($card['cost']); ?></p>
            <p class="details-info__all"><b>Power: </b><?php echo htmlspecialchars($card['power']); ?></p>
            <p class="details-info__all"><b>Subtype(s): </b><?php echo htmlspecialchars($card['subtype']); ?></p>
            <p class="details-info__all"><b>Attribute: </b><?php echo htmlspecialchars($card['attribute']); ?></p>
            <p class="details-info__all"><b>Artist: </b><a href="#"><?php echo htmlspecialchars($card['artist']); ?></a></p>
          </div>
          <div class="detail-info__breakdown-item">
            <div class="details-info__spotlight">
              <p class="details-info__spotlight-title">Near Mint Foil</p>
              <p class="details-info__spotlight-price">$<?php echo number_format($card['max_price'], 2); ?>
                <span style="font-size: 1.2rem; font-weight: 400;">shipping: <a href="#">included</a></span>
              </p>
              <p class="details-info__spotlight-sold">Sold by <a href="#">Sunao</a></p>
              <!-- QUANTITY -->
              <div class="details-info__spotlight-btn">
                <?php
                $quantity = $card['total_quantity'];
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
              <a class="details-info__all-listing-link" href="#">View <?php echo $card['total_quantity']; ?> Other Listings</a>
              <p class="details-info__all-listing-price">As low as $<?php echo number_format($card['market_price'], 2); ?></p>
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
    <!-- Thêm đoạn code này ngay trước phần recommend -->
    <?php
    // Lấy 12 sản phẩm ngẫu nhiên từ cùng set, ngoại trừ sản phẩm hiện tại
    $recommend_query = "SELECT cards.*, sets.name AS set_name FROM cards 
                        JOIN sets ON cards.set_id = sets.id 
                        WHERE cards.set_id = ? AND cards.id != ?
                        ORDER BY RAND() LIMIT 12";
    try {
      $stmt = $conn->prepare($recommend_query);
      $stmt->execute([$card['set_id'], $id]);
      $recommended_cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo "Error: " . $e->getMessage();
    }
    ?>

    <!-- Cập nhật phần recommend như sau -->
    <div class="card__details-recommend">
      <div class="card__recommend">
        <h2>Customers Also Purchased</h2>
        <?php
        // Lấy 12 sản phẩm ngẫu nhiên từ cùng set, ngoại trừ sản phẩm hiện tại
        $recommend_query = "SELECT c.*, s.name AS set_name, 
                        MIN(cl.price) AS market_price,
                        MAX(cl.price) AS max_price,
                        SUM(cl.quantity) AS total_quantity
                        FROM cards c
                        JOIN sets s ON c.set_id = s.id 
                        LEFT JOIN card_listings cl ON c.id = cl.card_id
                        WHERE c.set_id = ? AND c.id != ?
                        GROUP BY c.id
                        ORDER BY RAND() LIMIT 12";
        try {
          $stmt = $conn->prepare($recommend_query);
          $stmt->execute([$card['set_id'], $id]);
          $recommended_cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
        }
        ?>
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
                          <img loading="lazy" src="/public/images/product/<?php echo htmlspecialchars($rec_card['image_filename']); ?>" alt="<?php echo htmlspecialchars($rec_card['name']); ?>">
                        </div>
                        <div class="product__info">
                          <p class="product__name"><?php echo htmlspecialchars($rec_card['name']); ?></p>
                          <p class="product__set"><?php echo htmlspecialchars($rec_card['set_name']); ?></p>
                          <p class="product__rarity"><?php echo htmlspecialchars($rec_card['rarity']); ?></p>
                          <p class="product__card-number"><?php echo htmlspecialchars($rec_card['card_number']); ?></p>
                          <?php if ($rec_card['total_quantity'] > 0): ?>
                            <p class="product__listing"><?php echo $rec_card['total_quantity']; ?> listings</p>
                            <p class="product__price">
                              $<?php echo number_format($rec_card['max_price'], 2); ?>
                            </p>
                            <p class="product__market-price">Market Price: $<?php echo number_format($rec_card['market_price'], 2); ?></p>
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

    <!-- Phần recommend giữ nguyên như cũ -->
    <?php
    include '../includes/footer.php';
    ?>