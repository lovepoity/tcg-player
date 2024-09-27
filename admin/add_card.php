```php:admin/add_card.php
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
  $price = $_POST['price'];
  $product_details = $_POST['product_details'];
  $card_number = $_POST['card_number'];
  $color = $_POST['color'];
  $card_type = $_POST['card_type'];
  $cost = $_POST['cost'];
  $power = $_POST['power'];
  $subtype = $_POST['subtype'];
  $attribute = $_POST['attribute'];
  $artist = $_POST['artist'];

  // Thêm các trường mới
  $listing = $_POST['listing'];
  $market_price = $_POST['market_price'];

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
    }
  } else {
    // Nếu không có tệp mới, có thể xử lý theo cách khác (ví dụ: thông báo lỗi)
    echo "No image uploaded.";
    exit();
  }

  // Thêm card vào cơ sở dữ liệu
  $query = "INSERT INTO cards (name, image_filename, rarity, price, product_details, card_number, color, card_type, cost, power, subtype, attribute, artist, listing, market_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($query);
  $stmt->execute([$name, $image_filename, $rarity, $price, $product_details, $card_number, $color, $card_type, $cost, $power, $subtype, $attribute, $artist, $listing, $market_price]);

  header("Location: index.php");
  exit();
}
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
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" step="0.01" required>
  </div>
  <!-- Thêm các trường mới -->
  <div class="form-group">
    <label for="listing">Listing:</label>
    <input type="text" id="listing" name="listing" required>
  </div>
  <div class="form-group">
    <label for="market_price">Market Price:</label>
    <input type="number" id="market_price" name="market_price" step="0.01" required>
  </div>
  <button type="submit" class="btn btn-primary">Add Card</button>
</form>