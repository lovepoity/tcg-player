<?php
include '../includes/header.php';
include '../includes/db_connect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$query = "SELECT cards.*, sets.name AS set_name FROM cards 
          JOIN sets ON cards.set_id = sets.id 
          WHERE cards.id BETWEEN 1 AND 24";
try {
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>

<div class="sub__page">
  <!-- FILTER -->
  <div class="filter">
    <div style="display: flex; align-items: center;" class="filter__item">
      All Filters <p>2</p>
    </div>
    <div class="filter__item">
      One Piece Card Game <i class="fa-solid fa-xmark"></i>
    </div>
    <div class="filter__item">
      Two Legends
    </div>
    <div class="filter__item">
      Product Type <i class="fa-solid fa-angle-down"></i>
    </div>
    <div class="filter__item">
      Card Type <i class="fa-solid fa-angle-down"></i>
    </div>
    <div class="filter__item">
      Color <i class="fa-solid fa-angle-down"></i>
    </div>
    <div class="filter__item">
      Condition <i class="fa-solid fa-angle-down"></i>
    </div>
    <div class="filter__item">
      Printing <i class="fa-solid fa-angle-down"></i>
    </div>
    <div class="filter__item">
      Rarity <i class="fa-solid fa-angle-down"></i>
    </div>
    <div class="filter__item">
      Clear Filter
    </div>
  </div>
  <!-- END FILTER -->
  <div class="sub__banner">
    <a href="#">
      <img src="/public/images/banner/sub-banner.webp" alt="">
    </a>
  </div>
  <!-- PRODUCT -->
  <div class="product-grid">
    <?php
    $count = 0;
    foreach ($cards as $card):
      $count++;
    ?>
      <div class="product">
        <a href="/views/card_details.php?id=<?php echo $card['id']; ?>">
          <img loading="lazy" src="/public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
          <div class="product__info">
            <p class="product__name"><?php echo htmlspecialchars($card['name']); ?></p>
            <p class="product__set"><?php echo htmlspecialchars($card['set_name']); ?></p>
            <p class="product__rarity"><?php echo htmlspecialchars($card['rarity']); ?></p>
            <p class="product__card-number"><?php echo htmlspecialchars($card['card_number']); ?></p>
            <?php if (htmlspecialchars($card['listing']) === 'Out of Stock'): ?>
              <p class="product__listing"><?php echo htmlspecialchars($card['listing']); ?></p>
            <?php elseif (htmlspecialchars($card['listing']) !== 'Out of Stock'): ?>
              <p class="product__listing"><?php echo htmlspecialchars($card['listing']); ?> listings from</p>
            <?php endif; ?>
            <p class="product__price">$<?php echo number_format($card['price'], 2); ?></p>
            <p class="product__market-price">Market Price: <span style="color: #07772D;">$<?php echo number_format($card['market_price'], 2); ?></span></p>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="sub__banner">
    <a href="#">
      <img src="/public/images/banner/sub-banner.webp" alt="">
    </a>
  </div>
</div>
<?php
include '../includes/footer.php';
?>