<?php
session_start();
include 'includes/admin_header.php';
include '../includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Xử lý thêm card mới
  $name = $_POST['name'];
  $rarity = $_POST['rarity'];
  $product_details = $_POST['product_details'];
  $card_number = $_POST['card_number'];
  $color = $_POST['color'];
  $card_type = $_POST['card_type'];
  $cost = $_POST['cost'];
  $power = $_POST['power'];
  $subtype = $_POST['subtype'];
  $attribute = $_POST['attribute'];
  $artist = $_POST['artist'];
  $set_id = $_POST['set_id'];

  // Kiểm tra xem có tệp hình ảnh không
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
      exit();
    }
  } else {
    // Nếu không có tệp mới, có thể xử lý theo cách khác (ví dụ: thông báo lỗi)
    echo "No image uploaded.";
    exit();
  }

  // Thêm card vào cơ sở dữ liệu (đã loại bỏ price, market_price, và quantity)
  $query = "INSERT INTO cards (name, image_filename, rarity, product_details, card_number, color, card_type, cost, power, subtype, attribute, artist, set_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->execute([$name, $image_filename, $rarity, $product_details, $card_number, $color, $card_type, $cost, $power, $subtype, $attribute, $artist, $set_id]);

  header("Location: index.php");
  exit();
}

// Lấy danh sách các bộ sưu tập
$set_query = "SELECT id, name FROM sets ORDER BY name ASC";
$set_stmt = $conn->prepare($set_query);
$set_stmt->execute();
$sets = $set_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Add New Card</h1>
<form action="" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
  </div>
  <div class="form-group">
    <label for="image_upload">Image:</label>
    <input type="file" id="image_upload" name="image_upload" accept="image/*" required>
  </div>
  <div class="form-group">
    <label for="product_details">Product Details:</label>
    <textarea id="product_details" name="product_details" required></textarea>
  </div>
  <div class="form-group">
    <label for="rarity">Rarity:</label>
    <input type="text" id="rarity" name="rarity" required>
  </div>
  <div class="form-group">
    <label for="card_number">Card Number:</label>
    <input type="text" id="card_number" name="card_number" required>
  </div>
  <div class="form-group">
    <label for="color">Color:</label>
    <input type="text" id="color" name="color" required>
  </div>
  <div class="form-group">
    <label for="card_type">Card Type:</label>
    <input type="text" id="card_type" name="card_type" required>
  </div>
  <div class="form-group">
    <label for="cost">Cost:</label>
    <input type="text" id="cost" name="cost" required>
  </div>
  <div class="form-group">
    <label for="power">Power:</label>
    <input type="text" id="power" name="power" required>
  </div>
  <div class="form-group">
    <label for="subtype">Subtype:</label>
    <input type="text" id="subtype" name="subtype" required>
  </div>
  <div class="form-group">
    <label for="attribute">Attribute:</label>
    <input type="text" id="attribute" name="attribute" required>
  </div>
  <div class="form-group">
    <label for="artist">Artist:</label>
    <input type="text" id="artist" name="artist" required>
  </div>
  <div class="form-group">
    <label for="set_id">Set:</label>
    <select id="set_id" name="set_id" required>
      <option value="">Select a set</option>
      <?php foreach ($sets as $set): ?>
        <option value="<?php echo $set['id']; ?>"><?php echo htmlspecialchars($set['name']); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" class="btn btn-primary">Add Card</button>
</form>