<?php
require_once '../../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  require_once '../actions/add_game_action.php';
  handleAddGame($conn);
  exit;
}
?>

<h1 class="title">Add New Game</h1>
<ul class="add-product">
  <li><a href="#" class="nav-link" data-page="add_card"><i class="fa-solid fa-plus"></i> Add Card</a></li>
  <li><a href="#" class="nav-link" data-page="add_set"><i class="fa-solid fa-plus"></i> Add Set</a></li>
  <li><a href="#" class="nav-link" data-page="add_game"><i class="fa-solid fa-plus"></i> Add Game</a></li>
</ul>
<div class="content__detail">
  <form id="addGameForm" method="POST" class="two-column-form">
    <div class="form-row">
      <div class="form-group">
        <label for="name">Game Name:</label>
        <input autocomplete="off" type="text" id="name" name="name" required>
      </div>
    </div>

    <div class="form-row full-width">
      <button type="submit" class="btn btn-primary" id="submit-btn">Add Game</button>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/js/common.js"></script>
<script>
  $(document).ready(function() {
    initializeAddGamePage();
  });

  function initializeAddGamePage() {
    handleFormSubmit("#addGameForm", '/admin/pages/add_game.php', function(response) {
      if (response.success) {
        showToast("Game added successfully", "success");
        $("#addGameForm")[0].reset();
      }
    });
  }
</script>