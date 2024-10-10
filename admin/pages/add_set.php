<?php
require_once '../../includes/db_connect.php';

// Get list of games
$game_query = "SELECT id, name FROM games ORDER BY id ASC";
$game_stmt = $conn->prepare($game_query);
$game_stmt->execute();
$games = $game_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once '../actions/add_set_action.php';
  handleAddSet($conn);
  exit;
}
?>

<h1 class="title">Add New Set</h1>
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
  </form>
</div>
<div class="content__detail">
  <form id="addSetForm" method="POST" enctype="multipart/form-data" class="two-column-form">
    <input type="hidden" id="selected_game_id" name="game_id" value="">

    <div class="form-row">
      <div class="form-group">
        <label for="name">Set Name:</label>
        <input autocomplete="off" type="text" id="name" name="name" required>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label for="image_upload">Image:</label>
        <div class="image-upload-container">
          <input type="file" id="image_upload" name="image_upload" accept="image/*">
          <div id="image_preview" class="image-preview">IMG</div>
        </div>
      </div>
    </div>

    <div class="form-row full-width">
      <button type="submit" class="btn btn-primary" id="submit-btn" disabled>Add Set</button>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/js/common.js"></script>
<script>
  $(document).ready(function() {
    initializeAddSetPage();
  });

  function initializeAddSetPage() {
    $('#game_id').change(function() {
      var gameId = $(this).val();
      $('#selected_game_id').val(gameId);
      $('#submit-btn').prop('disabled', !gameId);
    });

    handleFormSubmit("#addSetForm", '/admin/pages/add_set.php', function(response) {
      if (response.success) {
        showToast("Set added successfully", "success");
        $("#addSetForm")[0].reset();
        $("#image_preview").html('IMG');
        $('#submit-btn').prop('disabled', true);
      }
    });

    handleImagePreview('#image_upload', '#image_preview');
  }
</script>