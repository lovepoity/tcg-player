<?php
session_start();
include 'includes/admin_header.php';
include '../includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Xử lý cập nhật card
  $name = $_POST['name'];
  $product_details = $_POST['product_details'];
  $rarity = $_POST['rarity'];
  $card_number = $_POST['card_number'];
  $color = $_POST['color'];
  $card_type = $_POST['card_type'];
  $cost = $_POST['cost'];
  $power = $_POST['power'];
  $subtype = $_POST['subtype'];
  $attribute = $_POST['attribute'];
  $artist = $_POST['artist'];
  $set_id = $_POST['set_id'];
  // Đã loại bỏ $listing = $_POST['listing'];

  // Kiểm tra xem có tệp hình ảnh mới không
  if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == 0) {
    $image_upload = $_FILES['image_upload'];
    $image_filename = basename($image_upload['name']);
    $target_directory = '../public/images/product/';
    $target_file = $target_directory . $image_filename;

    // Di chuyển tệp hình ảnh vào thư mục
    if (move_uploaded_file($image_upload['tmp_name'], $target_file)) {
      // Tệp đã được tải lên thành công
    } else {
      // Xử lý lỗi tải lên
      echo "Sorry, there was an error uploading your file.";
    }
  } else {
    // Nếu không có tệp mới, giữ nguyên tên ảnh cũ
    $image_filename = $_POST['current_image']; // Lưu tên ảnh hiện tại
  }

  // Cập nhật câu lệnh UPDATE (đã loại bỏ price, quantity, market_price, và listing)
  $query = "UPDATE cards SET name = ?, image_filename = ?, product_details = ?, rarity = ?, card_number = ?, color = ?, card_type = ?, cost = ?, power = ?, subtype = ?, attribute = ?, artist = ?, set_id = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$name, $image_filename, $product_details, $rarity, $card_number, $color, $card_type, $cost, $power, $subtype, $attribute, $artist, $set_id, $id]);

  header("Location: index.php");
  exit();
} else {
  // Lấy thông tin card hiện tại
  $query = "SELECT * FROM cards WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$id]);
  $card = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$card) {
    header("Location: index.php");
    exit();
  }
}

// Lấy danh sách các bộ sưu tập
$set_query = "SELECT id, name FROM sets ORDER BY name ASC";
$set_stmt = $conn->prepare($set_query);
$set_stmt->execute();
$sets = $set_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-form">
  <h1>Edit Card</h1>
  <form action="" method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($card['name']); ?>" required>
    </div>
    <div class="form-group">
      <label for="image_upload">Current Image:</label>
      <div>
        <img src="../public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="Current Image" style="max-width: 150px; max-height: 150px; display: block; margin-bottom: 10px;">
        <input type="file" id="image_upload" name="image_upload" accept="image/*"> <!-- Bỏ thuộc tính required -->
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($card['image_filename']); ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="product_details">Product Details:</label>
      <textarea id="product_details" name="product_details" required><?php echo htmlspecialchars($card['product_details']); ?></textarea>
    </div>
    <div class="form-group">
      <label for="rarity">Rarity:</label>
      <input type="text" id="rarity" name="rarity" value="<?php echo htmlspecialchars($card['rarity']); ?>" required>
    </div>
    <div class="form-group">
      <label for="card_number">Card Number:</label>
      <input type="text" id="card_number" name="card_number" value="<?php echo htmlspecialchars($card['card_number']); ?>" required>
    </div>
    <div class="form-group">
      <label for="color">Color:</label>
      <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($card['color']); ?>" required>
    </div>
    <div class="form-group">
      <label for="card_type">Card Type:</label>
      <input type="text" id="card_type" name="card_type" value="<?php echo htmlspecialchars($card['card_type']); ?>" required>
    </div>
    <div class="form-group">
      <label for="cost">Cost:</label>
      <input type="text" id="cost" name="cost" value="<?php echo htmlspecialchars($card['cost']); ?>" required>
    </div>
    <div class="form-group">
      <label for="power">Power:</label>
      <input type="text" id="power" name="power" value="<?php echo htmlspecialchars($card['power']); ?>" required>
    </div>
    <div class="form-group">
      <label for="subtype">Subtype:</label>
      <input type="text" id="subtype" name="subtype" value="<?php echo htmlspecialchars($card['subtype']); ?>" required>
    </div>
    <div class="form-group">
      <label for="attribute">Attribute:</label>
      <input type="text" id="attribute" name="attribute" value="<?php echo htmlspecialchars($card['attribute']); ?>" required>
    </div>
    <div class="form-group">
      <label for="artist">Artist:</label>
      <input type="text" id="artist" name="artist" value="<?php echo htmlspecialchars($card['artist']); ?>" required>
    </div>
    <div class="form-group">
      <label for="set_id">Set:</label>
      <select id="set_id" name="set_id" required>
        <option value="">Select a set</option>
        <?php foreach ($sets as $set): ?>
          <option value="<?php echo $set['id']; ?>" <?php echo ($card['set_id'] == $set['id']) ? 'selected' : ''; ?>>
            <?php echo htmlspecialchars($set['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Update Card</button>
  </form>
</div>