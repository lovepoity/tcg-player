<?php
session_start();
require_once '../../../includes/db_connect.php';

if (!isset($_SESSION['store_id'])) {
  exit('Unauthorized access');
}

$store_id = $_SESSION['store_id'];
$game_id = isset($_GET['game_id']) ? $_GET['game_id'] : null;
$set_id = isset($_GET['set_id']) ? $_GET['set_id'] : null;

$query = "SELECT c.*, cl.quantity, cl.price, cl.shipping, s.name as set_name, g.name as game_name 
          FROM cards c
          INNER JOIN sets s ON c.set_id = s.id 
          INNER JOIN games g ON s.game_id = g.id
          LEFT JOIN card_listings cl ON c.id = cl.card_id AND cl.store_id = ?
          WHERE 1=1";

$params = [$store_id];

if ($game_id) {
  $query .= " AND s.game_id = ?";
  $params[] = $game_id;
}

if ($set_id) {
  $query .= " AND c.set_id = ?";
  $params[] = $set_id;
}

if (!empty($_GET['search'])) {
  $query .= " AND c.name LIKE ?";
  $params[] = $_GET['search'] . '%';
}

$query .= " ORDER BY s.id ASC, c.id ASC";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cards)) {
  echo '<div class="store-products__message">No card information available. Please select another Game and Set, or try searching again.</div>';
  return;
}
?>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Image</th>
      <th>Name</th>
      <th>Rarity</th>
      <th>Card Number</th>
      <th>Color</th>
      <th>Type</th>
      <th>Game - Set</th>
      <th>Quantity</th>
      <th>Price</th>
      <th>Shipping</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cards as $card): ?>
      <tr>
        <td><?php echo $card['id']; ?></td>
        <td>
          <img src="/public/images/product/<?php echo $card['image_filename']; ?>"
            alt="<?php echo $card['name']; ?>"
            class="store-products__img">
        </td>
        <td><?php echo $card['name']; ?></td>
        <td><?php echo $card['rarity']; ?></td>
        <td><?php echo $card['card_number']; ?></td>
        <td><?php echo $card['color']; ?></td>
        <td><?php echo $card['card_type']; ?></td>
        <td><?php echo $card['game_name'] . ' - ' . $card['set_name']; ?></td>
        <td>
          <input type="number" class="store-products__quantity"
            value="<?php echo $card['quantity'] ?? 0; ?>"
            data-card-id="<?php echo $card['id']; ?>">
        </td>
        <td>
          <input type="number" step="0.01" class="store-products__price"
            value="<?php echo $card['price'] ?? 0; ?>"
            data-card-id="<?php echo $card['id']; ?>">
        </td>
        <td>
          <input type="number" step="0.01" class="store-products__shipping"
            value="<?php echo $card['shipping'] ?? 0; ?>"
            data-card-id="<?php echo $card['id']; ?>">
        </td>
        <td>
          <button class="store-products__btn-save" data-card-id="<?php echo $card['id']; ?>">Save</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>