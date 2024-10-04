<?php
// Sử dụng đường dẫn tuyệt đối
require_once __DIR__ . '/../includes/db_connect.php';

$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : null;
$set_id = isset($_GET['set_id']) ? intval($_GET['set_id']) : null;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Thiết lập phân trang
$items_per_page = 24;
$offset = ($page - 1) * $items_per_page;

// Chuẩn bị truy vấn SQL
if ($set_id) {
  $query = "SELECT c.*, s.name AS set_name, 
              MIN(cl.price) AS min_price,
              AVG(cl.price) AS avg_price,
              COUNT(DISTINCT cl.store_id) AS total_stores
              FROM cards c
              JOIN sets s ON c.set_id = s.id 
              LEFT JOIN card_listings cl ON c.id = cl.card_id
              WHERE c.set_id = :set_id
              GROUP BY c.id
              LIMIT :offset, :items_per_page";
  $params = [':set_id' => $set_id];
} elseif ($game_id) {
  $query = "SELECT c.*, s.name AS set_name, 
              MIN(cl.price) AS min_price,
              AVG(cl.price) AS avg_price,
              COUNT(DISTINCT cl.store_id) AS total_stores
              FROM cards c
              JOIN sets s ON c.set_id = s.id 
              LEFT JOIN card_listings cl ON c.id = cl.card_id
              WHERE s.game_id = :game_id
              GROUP BY c.id
              LIMIT :offset, :items_per_page";
  $params = [':game_id' => $game_id];
} else {
  die("No game or set specified");
}

try {
  $stmt = $conn->prepare($query);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
  foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_INT);
  }
  $stmt->execute();
  $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Query failed: " . $e->getMessage());
}

// Chỉ trả về HTML cho product-grid
foreach ($cards as $card): ?>
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
        <?php if ($card['total_stores'] > 0): ?>
          <p class="product__listing"><?php echo $card['total_stores']; ?> listings from</p>
          <p class="product__price">
            $<?php echo number_format($card['min_price'], 2); ?>
          </p>
          <p class="product__market-price">Market Price: <span style="color: #07772D;">$<?php echo number_format($card['avg_price'], 2); ?></span></p>
        <?php else: ?>
          <p class="product__listing">Out of Stock</p>
        <?php endif; ?>
      </div>
    </a>
  </div>
<?php endforeach;
