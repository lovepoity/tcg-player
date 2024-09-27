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

<h1><?php echo htmlspecialchars($card['name']); ?></h1>
<img src="/public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
<p>Rarity: <?php echo htmlspecialchars($card['rarity']); ?></p>
<p>Card Number: <?php echo htmlspecialchars($card['card_number']); ?></p>
<p>Color: <?php echo htmlspecialchars($card['color']); ?></p>
<p>Type: <?php echo htmlspecialchars($card['card_type']); ?></p>
<p>Cost: <?php echo htmlspecialchars($card['cost']); ?></p>
<p>Power: <?php echo htmlspecialchars($card['power']); ?></p>
<p>Subtype: <?php echo htmlspecialchars($card['subtype']); ?></p>
<p>Attribute: <?php echo htmlspecialchars($card['attribute']); ?></p>
<p>Artist: <?php echo htmlspecialchars($card['artist']); ?></p>
<p>Price: $<?php echo htmlspecialchars($card['price']); ?></p>
<p>Product Details: <?php echo nl2br(htmlspecialchars($card['product_details'])); ?></p>

<?php
include '../includes/footer.php';
?>