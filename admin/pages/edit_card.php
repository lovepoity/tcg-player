<?php
require_once '../../includes/db_connect.php';
require_once '../actions/edit_card_action.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  handleEditCard($conn);
  exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$card = getCardById($conn, $id);

if (!$card) {
  echo '<p>Card not found.</p>';
  exit();
}
?>

<h1 class="title">Edit Card</h1>
<ul class="add-product">
  <li><a href="#" class="nav-link" data-page="add_card"><i class="fa-solid fa-plus"></i> Add Card</a></li>
  <li><a href="#" class="nav-link" data-page="add_set"><i class="fa-solid fa-plus"></i> Add Set</a></li>
  <li><a href="#" class="nav-link" data-page="add_game"><i class="fa-solid fa-plus"></i> Add Game</a></li>
</ul>
<div class="content__detail">
  <form id="editCardForm" method="POST" enctype="multipart/form-data" class="two-column-form">
    <input type="hidden" name="id" value="<?php echo $card['id']; ?>">
    <input type="hidden" name="set_id" value="<?php echo $card['set_id']; ?>">

    <!-- Row 1: name, rarity -->
    <div class="form-row">
      <div class="form-group">
        <label for="name">Name:</label>
        <input autocomplete="off" type="text" id="name" name="name" value="<?php echo htmlspecialchars($card['name']); ?>" required>
      </div>
      <div class="form-group">
        <label for="rarity">Rarity:</label>
        <input autocomplete="off" type="text" id="rarity" name="rarity" value="<?php echo htmlspecialchars($card['rarity']); ?>" required>
      </div>
    </div>

    <!-- Row 2: image, product_details -->
    <div class="form-row">
      <div class="form-group">
        <label for="product_details">Product Details:</label>
        <textarea id="product_details" name="product_details" required><?php echo htmlspecialchars($card['product_details']); ?></textarea>
      </div>
      <div class="form-group">
        <label for="image_upload">Image:</label>
        <div class="image-upload-container">
          <input type="file" id="image_upload" name="image_upload" accept="image/*">
          <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($card['image_filename']); ?>">
          <div id="image_preview" class="image-preview">
            <?php if ($card['image_filename']): ?>
              <img src="/public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="Current image">
            <?php else: ?>
              IMG
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Row 3: card_number, color -->
    <div class="form-row">
      <div class="form-group">
        <label for="card_number">Card Number:</label>
        <input autocomplete="off" type="text" id="card_number" name="card_number" value="<?php echo htmlspecialchars($card['card_number']); ?>" required>
      </div>
      <div class="form-group">
        <label for="color">Color:</label>
        <input autocomplete="off" type="text" id="color" name="color" value="<?php echo htmlspecialchars($card['color']); ?>" required>
      </div>
    </div>

    <!-- Row 4: card_type, subtype -->
    <div class="form-row">
      <div class="form-group">
        <label for="card_type">Card Type:</label>
        <input autocomplete="off" type="text" id="card_type" name="card_type" value="<?php echo htmlspecialchars($card['card_type']); ?>" required>
      </div>
      <div class="form-group">
        <label for="subtype">Subtype:</label>
        <input autocomplete="off" type="text" id="subtype" name="subtype" value="<?php echo htmlspecialchars($card['subtype']); ?>" required>
      </div>
    </div>

    <!-- Row 5: cost, power -->
    <div class="form-row">
      <div class="form-group">
        <label for="cost">Cost:</label>
        <input autocomplete="off" type="text" id="cost" name="cost" value="<?php echo htmlspecialchars($card['cost']); ?>" required>
      </div>
      <div class="form-group">
        <label for="power">Power:</label>
        <input autocomplete="off" type="text" id="power" name="power" value="<?php echo htmlspecialchars($card['power']); ?>" required>
      </div>
    </div>

    <!-- Row 6: attribute, artist -->
    <div class="form-row">
      <div class="form-group">
        <label for="attribute">Attribute:</label>
        <input autocomplete="off" type="text" id="attribute" name="attribute" value="<?php echo htmlspecialchars($card['attribute']); ?>" required>
      </div>
      <div class="form-group">
        <label for="artist">Artist:</label>
        <input autocomplete="off" type="text" id="artist" name="artist" value="<?php echo htmlspecialchars($card['artist']); ?>" required>
      </div>
    </div>

    <div class="form-row full-width">
      <button type="submit" class="btn btn-primary" id="submit-btn">Update Card</button>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/js/common.js"></script>
<script>
  $(document).ready(function() {
    initializeEditCardPage();
  });

  function initializeEditCardPage() {
    handleFormSubmit("#editCardForm", '/admin/pages/edit_card.php', function(response) {
      if (response.success) {
        showToast("Card updated successfully", "success");
      } else {
        showToast("Error: " + (response.error || "Cannot update card"), "error");
      }
    });

    handleImagePreview('#image_upload', '#image_preview');
  }
</script>