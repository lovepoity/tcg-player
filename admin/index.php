<?php
session_start();
include 'includes/admin_header.php';
include '../includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

// Lấy danh sách tất cả các cửa hàng
$store_query = "SELECT id, name FROM stores ORDER BY name ASC";
$store_stmt = $conn->prepare($store_query);
$store_stmt->execute();
$all_stores = $store_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách card và thông tin giá từ các cửa hàng
$query = "SELECT c.*, cl.store_id, cl.price, s.name as store_name 
          FROM cards c
          CROSS JOIN stores s
          LEFT JOIN card_listings cl ON c.id = cl.card_id AND s.id = cl.store_id
          ORDER BY c.id DESC, s.name ASC";
$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tổ chức dữ liệu theo card
$cards = [];
foreach ($results as $row) {
  if (!isset($cards[$row['id']])) {
    $cards[$row['id']] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'image_filename' => $row['image_filename'],
      'stores' => array_fill_keys(array_column($all_stores, 'name'), null)
    ];
  }
  $cards[$row['id']]['stores'][$row['store_name']] = $row['price'];
}

?>

<div class="admin-dashboard">
  <h1>Admin Dashboard</h1>
  <a href="add_card.php" class="btn btn-primary">Add New Card</a>

  <table class="card-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Image</th>
        <?php foreach ($all_stores as $store): ?>
          <th><?php echo htmlspecialchars($store['name']); ?></th>
        <?php endforeach; ?>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cards as $card): ?>
        <tr>
          <td><?php echo $card['id']; ?></td>
          <td><?php echo htmlspecialchars($card['name']); ?></td>
          <td>
            <img src="../public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="Card Image" style="max-width: 100px; max-height: 100px;">
          </td>
          <?php foreach ($all_stores as $store): ?>
            <td>
              <?php
              if ($card['stores'][$store['name']] !== null) {
                echo '$' . $card['stores'][$store['name']];
              } else {
                echo 'N/A';
              }
              ?>
            </td>
          <?php endforeach; ?>
          <td>
            <a href="edit_card.php?id=<?php echo $card['id']; ?>" class="btn btn-secondary">Edit</a>
            <a href="delete_card.php?id=<?php echo $card['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this card?');">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>