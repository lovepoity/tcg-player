<?php
session_start();
include 'includes/admin_header.php';
include '../includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

// Lấy danh sách card
$query = "SELECT * FROM cards ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-dashboard">
  <h1>Admin Dashboard</h1>
  <a href="add_card.php" class="btn btn-primary">Add New Card</a>

  <table class="card-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Image</th> <!-- Thêm cột hình ảnh -->
        <th>Rarity</th>
        <th>Price</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($cards as $card): ?>
        <tr>
          <td><?php echo $card['id']; ?></td>
          <td><?php echo htmlspecialchars($card['name']); ?></td>
          <td>
            <img src="../public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="Card Image" style="max-width: 100px; max-height: 100px;"> <!-- Hiển thị hình ảnh -->
          </td>
          <td><?php echo htmlspecialchars($card['rarity']); ?></td>
          <td>$<?php echo number_format($card['price'], 2); ?></td>
          <td>
            <a href="edit_card.php?id=<?php echo $card['id']; ?>" class="btn btn-secondary">Edit</a>
            <a href="delete_card.php?id=<?php echo $card['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this card?');">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>