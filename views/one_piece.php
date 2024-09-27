<?php
include '../includes/header.php';
include '../includes/db_connect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$query = "SELECT * FROM cards WHERE id BETWEEN 1 AND 24";
try {
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo "Số sản phẩm được lấy: " . count($cards);
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

  <!-- PRODUCT -->
  <div class="product-grid">
    <?php
    $count = 0;
    foreach ($cards as $card):
      $count++;
    ?>
      <div class="product">
        <img src="/public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
        <div class="product__info">
          <p class="product__name"><?php echo htmlspecialchars($card['name']); ?></p>
          <p class="product__rarity"><?php echo htmlspecialchars($card['rarity']); ?></p>
          <p class="product__card-number"><?php echo htmlspecialchars($card['card_number']); ?></p>
          <p class="product__price">$<?php echo number_format($card['price'], 2); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
    <?php echo "<p>Số sản phẩm được hiển thị: $count</p>"; // Kiểm tra số lượng sản phẩm được hiển thị 
    ?>
  </div>
</div>

<?php
include '../includes/footer.php';
?>