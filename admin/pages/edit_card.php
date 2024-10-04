<?php
require_once '../../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Xử lý cập nhật card
  $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
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

  // Xử lý upload ảnh nếu có
  if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] == 0) {
    $image_upload = $_FILES['image_upload'];
    $image_filename = basename($image_upload['name']);
    $target_directory = '../../public/images/product/';
    $target_file = $target_directory . $image_filename;

    if (move_uploaded_file($image_upload['tmp_name'], $target_file)) {
      // Tệp đã được tải lên thành công
    } else {
      echo json_encode(['error' => 'Failed to upload image']);
      exit();
    }
  } else {
    $image_filename = $_POST['current_image'];
  }

  // Cập nhật card trong cơ sở dữ liệu
  $query = "UPDATE cards SET name = ?, image_filename = ?, rarity = ?, product_details = ?, card_number = ?, color = ?, card_type = ?, cost = ?, power = ?, subtype = ?, attribute = ?, artist = ?, set_id = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $result = $stmt->execute([$name, $image_filename, $rarity, $product_details, $card_number, $color, $card_type, $cost, $power, $subtype, $attribute, $artist, $set_id, $id]);

  if ($result) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['error' => 'Failed to update card']);
  }
  exit();
}

// Xử lý GET request
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin card hiện tại
$query = "SELECT * FROM cards WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$id]);
$card = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$card) {
  echo '<p>Card not found.</p>';
  exit();
}

// Lấy danh sách các bộ sưu tập
$set_query = "SELECT id, name FROM sets ORDER BY name ASC";
$set_stmt = $conn->prepare($set_query);
$set_stmt->execute();
$sets = $set_stmt->fetchAll(PDO::FETCH_ASSOC);

// Hiển thị form chỉnh sửa
?>
<h1 class="title">Edit Card</h1>
<div class="content__detail">
  <form id="edit-card-form" enctype="multipart/form-data" class="two-column-form">
    <input type="hidden" name="id" value="<?php echo $card['id']; ?>">
    <div class="form-row">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($card['name']); ?>" required>
      </div>
      <div class="form-group">
        <label for="rarity">Rarity:</label>
        <input type="text" id="rarity" name="rarity" value="<?php echo htmlspecialchars($card['rarity']); ?>" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="card_number">Card Number:</label>
        <input type="text" id="card_number" name="card_number" value="<?php echo htmlspecialchars($card['card_number']); ?>" required>
      </div>
      <div class="form-group">
        <label for="color">Color:</label>
        <input type="text" id="color" name="color" value="<?php echo htmlspecialchars($card['color']); ?>" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="card_type">Card Type:</label>
        <input type="text" id="card_type" name="card_type" value="<?php echo htmlspecialchars($card['card_type']); ?>" required>
      </div>
      <div class="form-group">
        <label for="cost">Cost:</label>
        <input type="text" id="cost" name="cost" value="<?php echo htmlspecialchars($card['cost']); ?>" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="power">Power:</label>
        <input type="text" id="power" name="power" value="<?php echo htmlspecialchars($card['power']); ?>" required>
      </div>
      <div class="form-group">
        <label for="subtype">Subtype:</label>
        <input type="text" id="subtype" name="subtype" value="<?php echo htmlspecialchars($card['subtype']); ?>" required>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group">
        <label for="attribute">Attribute:</label>
        <input type="text" id="attribute" name="attribute" value="<?php echo htmlspecialchars($card['attribute']); ?>" required>
      </div>
      <div class="form-group">
        <label for="artist">Artist:</label>
        <input type="text" id="artist" name="artist" value="<?php echo htmlspecialchars($card['artist']); ?>" required>
      </div>
    </div>
    <div class="form-row full-width">
      <div class="form-group">
        <label for="set_id">Set:</label>
        <select id="set_id" name="set_id" required>
          <?php foreach ($sets as $set): ?>
            <option value="<?php echo $set['id']; ?>" <?php echo ($set['id'] == $card['set_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($set['name']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-row full-width">
      <div class="form-group">
        <label for="product_details">Product Details:</label>
        <textarea id="product_details" name="product_details" required><?php echo htmlspecialchars($card['product_details']); ?></textarea>
      </div>
    </div>
    <div class="form-row full-width">
      <div class="form-group">
        <label for="image_upload">Image:</label>
        <input type="file" id="image_upload" name="image_upload">
        <p>Current image: <?php echo htmlspecialchars($card['image_filename']); ?></p>
      </div>
    </div>
    <div class="form-row full-width">
      <button type="submit" class="btn btn-primary">Update Card</button>
    </div>
  </form>
</div>