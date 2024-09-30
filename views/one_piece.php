<?php
include '../includes/header.php';
include '../includes/db_connect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$query = "SELECT c.*, s.name AS set_name, 
          MIN(cl.price) AS market_price,
          MAX(cl.price) AS max_price,
          SUM(cl.quantity) AS total_quantity
          FROM cards c
          JOIN sets s ON c.set_id = s.id 
          LEFT JOIN card_listings cl ON c.id = cl.card_id
          WHERE c.id BETWEEN 1 AND 24  -- Đã thay đổi từ 24 thành 25
          GROUP BY c.id";
try {
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
?>

<div class="sub__page">
  <div class="navbar__sub-page">
    <!-- FILTER -->
    <div class="filter">
      <div style="display: flex; align-items: center; background-color: #F7F7F8;" class="filter__item">
        All Filters <span>2</span>
      </div>
      <div class="filter__item filter__style">
        One Piece Card Game <i class="fa-solid fa-xmark"></i>
      </div>
      <div class="filter__item filter__style">
        Two Legends <i class="fa-solid fa-angle-down"></i>
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
    <p class="sort__view">Sort & View</p>
    <select name="sort" id="sort">
      <option value="best-match">Best Match</option>
      <option value="newest">Newest</option>
      <option value="oldest">Oldest</option>
      <option value="price-asc">Price: Low to High</option>
      <option value="price-desc">Price: High to Low</option>
    </select>
    <svg data-v-7c2d0612="" class="svg-inline--fa fa-grid-2 active" aria-hidden="true" focusable="false" data-prefix="far" data-icon="grid-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" alt="A grid of results">
      <path class="" fill="currentColor" d="M0 80C0 53.49 21.49 32 48 32H144C170.5 32 192 53.49 192 80V176C192 202.5 170.5 224 144 224H48C21.49 224 0 202.5 0 176V80zM48 176H144V80H48V176zM0 336C0 309.5 21.49 288 48 288H144C170.5 288 192 309.5 192 336V432C192 458.5 170.5 480 144 480H48C21.49 480 0 458.5 0 432V336zM48 432H144V336H48V432zM400 32C426.5 32 448 53.49 448 80V176C448 202.5 426.5 224 400 224H304C277.5 224 256 202.5 256 176V80C256 53.49 277.5 32 304 32H400zM400 80H304V176H400V80zM256 336C256 309.5 277.5 288 304 288H400C426.5 288 448 309.5 448 336V432C448 458.5 426.5 480 400 480H304C277.5 480 256 458.5 256 432V336zM304 432H400V336H304V432z"></path>
    </svg>
    <i class='bx bx-menu-alt-right'></i>
  </div>
  <div class="sub__location">
    <a href="#">All Categories</a>
    <i class="fa-solid fa-chevron-right"></i>
    <a href="#">One Piece Card Game</a>
    <i class="fa-solid fa-chevron-right"></i>
    <p>Two Legends</p>
  </div>
  <!-- BANNER -->
  <div class="sub__banner">
    <a href="#">
      <img src="/public/images/banner/sub-banner.webp" alt="">
    </a>
  </div>
  <!-- END BANNER -->
  <!-- PRODUCT -->
  <div class="product-grid">
    <?php foreach ($cards as $card): ?>
      <div class="product">
        <a href="/views/card_details.php?id=<?php echo $card['id']; ?>">
          <div class="product__img">
            <img loading="lazy" src="/public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
          </div>
          <div class="product__info">
            <p class="product__name"><?php echo htmlspecialchars($card['name']); ?></p>
            <p class="product__set"><?php echo htmlspecialchars($card['set_name']); ?></p>
            <p class="product__rarity"><?php echo htmlspecialchars($card['rarity']); ?></p>
            <p class="product__card-number"><?php echo htmlspecialchars($card['card_number']); ?></p>
            <?php if ($card['total_quantity'] > 0): ?>
              <p class="product__listing"><?php echo $card['total_quantity']; ?> listings</p>
              <p class="product__price">
                $<?php echo number_format($card['max_price'], 2); ?>
              </p>
              <p class="product__market-price">Market Price: $<?php echo number_format($card['market_price'], 2); ?></p>
            <?php else: ?>
              <p class="product__listing">Out of Stock</p>
            <?php endif; ?>
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