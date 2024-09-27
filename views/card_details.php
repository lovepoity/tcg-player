<?php
include '../includes/header.php';
include '../includes/db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
  $stmt = $conn->prepare("SELECT * FROM cards WHERE id = ?");
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

<div class="card-details">
  <h1><?php echo htmlspecialchars($card['name']); ?></h1>
  <div class="card-image">
    <img src="/public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
  </div>
  <div class="card-info">
    <p><strong>Rarity:</strong> <?php echo htmlspecialchars($card['rarity']); ?></p>
    <p><strong>Card Number:</strong> <?php echo htmlspecialchars($card['card_number']); ?></p>
    <p><strong>Color:</strong> <?php echo htmlspecialchars($card['color']); ?></p>
    <p><strong>Type:</strong> <?php echo htmlspecialchars($card['card_type']); ?></p>
    <p><strong>Cost:</strong> <?php echo htmlspecialchars($card['cost']); ?></p>
    <p><strong>Power:</strong> <?php echo htmlspecialchars($card['power']); ?></p>
    <p><strong>Subtype:</strong> <?php echo htmlspecialchars($card['subtype']); ?></p>
    <p><strong>Attribute:</strong> <?php echo htmlspecialchars($card['attribute']); ?></p>
    <p><strong>Artist:</strong> <?php echo htmlspecialchars($card['artist']); ?></p>
    <p><strong>Price:</strong> $<?php echo number_format($card['price'], 2); ?></p>
  </div>
  <div class="card-details">
    <h2>Product Details</h2>
    <p><?php echo nl2br(htmlspecialchars($card['product_details'])); ?></p>
  </div>
</div>

<?php
include '../includes/footer.php';
?>