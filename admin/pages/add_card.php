<?php
require_once '../../includes/db_connect.php';

// Get list of games
$game_query = "SELECT id, name FROM games ORDER BY id ASC";
$game_stmt = $conn->prepare($game_query);
$game_stmt->execute();
$games = $game_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once '../actions/add_card_action.php';
  handleAddCard($conn);
  exit;
}
?>

<h1 class="title">Add New Card</h1>
<ul class="add-product">
  <li><a href="#" class="nav-link" data-page="add_card"><i class="fa-solid fa-plus"></i> Add Card</a></li>
  <li><a href="#" class="nav-link" data-page="add_set"><i class="fa-solid fa-plus"></i> Add Set</a></li>
  <li><a href="#" class="nav-link" data-page="add_game"><i class="fa-solid fa-plus"></i> Add Game</a></li>
</ul>
<div class="filters">
  <form id="filter-form">
    <label for="game_id">Game:</label>
    <select id="game_id" name="game_id" required>
      <option value="">Select Game</option>
      <?php foreach ($games as $game): ?>
        <option value="<?php echo $game['id']; ?>"><?php echo htmlspecialchars($game['name']); ?></option>
      <?php endforeach; ?>
    </select>
    <label for="set_id">Set:</label>
    <select id="set_id" name="set_id" required disabled>
      <option value="">Select Set</option>
    </select>
  </form>
</div>
<div class="content__detail">
  <form id="addCardForm" method="POST" enctype="multipart/form-data" class="two-column-form">
    <input type="hidden" id="selected_set_id" name="set_id" value="">

    <!-- Row 1: name, rarity -->
    <div class="form-row">
      <div class="form-group">
        <label for="name">Name:</label>
        <input autocomplete="off" type="text" id="name" name="name" required>
      </div>
      <div class="form-group">
        <label for="rarity">Rarity:</label>
        <input autocomplete="off" type="text" id="rarity" name="rarity" required>
      </div>
    </div>

    <!-- Row 2: image, product_details -->
    <div class="form-row">
      <div class="form-group">
        <label for="product_details">Product Details:</label>
        <textarea id="product_details" name="product_details" required></textarea>
      </div>
      <div class="form-group">
        <label for="image_upload">Image:</label>
        <div class="image-upload-container">
          <input type="file" id="image_upload" name="image_upload" required accept="image/*">
          <div id="image_preview" class="image-preview">IMG</div>
        </div>
      </div>
    </div>
    <!-- Row 3: card_number, color -->
    <div class="form-row">
      <div class="form-group">
        <label for="card_number">Card Number:</label>
        <input autocomplete="off" type="text" id="card_number" name="card_number" required>
      </div>
      <div class="form-group">
        <label for="color">Color:</label>
        <input autocomplete="off" type="text" id="color" name="color" required>
      </div>
    </div>

    <!-- Row 4: card_type, subtype -->
    <div class="form-row">
      <div class="form-group">
        <label for="card_type">Card Type:</label>
        <input autocomplete="off" type="text" id="card_type" name="card_type" required>
      </div>
      <div class="form-group">
        <label for="subtype">Subtype:</label>
        <input autocomplete="off" type="text" id="subtype" name="subtype" required>
      </div>
    </div>

    <!-- Row 5: cost, power -->
    <div class="form-row">
      <div class="form-group">
        <label for="cost">Cost:</label>
        <input autocomplete="off" type="text" id="cost" name="cost" required>
      </div>
      <div class="form-group">
        <label for="power">Power:</label>
        <input autocomplete="off" type="text" id="power" name="power" required>
      </div>
    </div>

    <!-- Row 6: attribute, artist -->
    <div class="form-row">
      <div class="form-group">
        <label for="attribute">Attribute:</label>
        <input autocomplete="off" type="text" id="attribute" name="attribute" required>
      </div>
      <div class="form-group">
        <label for="artist">Artist:</label>
        <input autocomplete="off" type="text" id="artist" name="artist" required>
      </div>
    </div>

    <div class="form-row full-width">
      <button type="submit" class="btn btn-primary" id="submit-btn" disabled>Add Card</button>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/js/common.js"></script>
<script>
  $(document).ready(function() {
    initializeAddCardPage();
  });

  function initializeAddCardPage() {
    $('#game_id').change(function() {
      var gameId = $(this).val();
      if (gameId) {
        $.ajax({
          url: '/admin/actions/get_sets.php',
          method: 'GET',
          data: {
            game_id: gameId
          },
          success: function(response) {
            $('#set_id').html(response).prop('disabled', false);
          }
        });
      } else {
        $('#set_id').html('<option value="">Select Set</option>').prop('disabled', true);
      }
      $('#submit-btn').prop('disabled', true);
    });

    $('#set_id').change(function() {
      var setId = $(this).val();
      $('#selected_set_id').val(setId);
      $('#submit-btn').prop('disabled', !setId);
    });

    handleFormSubmit("#addCardForm", '/admin/actions/add_card_action.php', function(response) {
      if (response.success) {
        showToast("Card added successfully", "success");
        $("#addCardForm")[0].reset();
        $("#set_id").prop('disabled', true);
        $("#submit-btn").prop('disabled', true);
        $("#image_preview").html('IMG');
      }
    });

    handleImagePreview('#image_upload', '#image_preview');
  }
</script>