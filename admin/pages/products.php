<?php
require_once '../../includes/db_connect.php';


$game_query = "SELECT * FROM games ORDER BY id ASC";
$game_stmt = $conn->query($game_query);
$all_games = $game_stmt->fetchAll(PDO::FETCH_ASSOC);

$store_query = "SELECT * FROM stores ORDER BY name";
$store_stmt = $conn->query($store_query);
$all_stores = $store_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-products">
  <h1 class="title">Product List</h1>
  <ul class="add-product">
    <li><a href="#" class="nav-link" data-page="add_card"><i class="fa-solid fa-plus"></i> Add Card</a></li>
    <li><a href="#" class="nav-link" data-page="add_set"><i class="fa-solid fa-plus"></i> Add Set</a></li>
    <li><a href="#" class="nav-link" data-page="add_game"><i class="fa-solid fa-plus"></i> Add Game</a></li>
  </ul>
  <div class="filters">
    <form id="filter-form">
      <label for="game-select">Game:</label>
      <select id="game-select" name="game_id">
        <option value="">All Games</option>
        <?php foreach ($all_games as $game): ?>
          <option value="<?php echo $game['id']; ?>">
            <?php echo $game['id'] . ' - ' . htmlspecialchars($game['name']); ?>
          </option>
        <?php endforeach; ?>
      </select>
      <label for="set-select">Set:</label>
      <select id="set-select" name="set_id" disabled>
        <option value="">All Sets</option>
      </select>
      <button type="button" id="get-cards-btn">Get</button>
    </form>
    <div class="search-container">
      <input autocomplete="off" type="text" id="search-input" placeholder="Search card by name">
      <div id="search-results" class="search-results"></div>
    </div>
  </div>
  <div class="content__detail">
    <div id="card-table-container">
      <div class="alert alert-info">
        Please select Game and Set, or search to display cards.
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/js/common.js"></script>
<script src="/admin/assets/js/products.js"></script>

<!-- Add this HTML code at the end of the file, just before the final closing </div> tag -->
<div id="delete-popup" class="popup" style="display: none;">
  <div class="popup-content">
    <h2>Confirm Deletion</h2>
    <p>Are you sure you want to delete this card?</p>
    <div class="popup-buttons">
      <button class="btn btn-danger" id="confirm-delete">Delete</button>
      <button id="cancel-delete">Cancel</button>
    </div>
  </div>
</div>

</div>