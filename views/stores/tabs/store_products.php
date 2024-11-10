<?php
session_start();
if (!isset($_SESSION['store_id'])) {
  header("Location: ../auth/login.php");
  exit();
}

// Lấy giá trị từ URL parameters (nếu có)
$selected_game = isset($_GET['game_id']) ? $_GET['game_id'] : '';
$selected_set = isset($_GET['set_id']) ? $_GET['set_id'] : '';

// Lấy danh sách games
require_once '../../../includes/db_connect.php';
$query = "SELECT id, name FROM games ORDER BY id ASC";
$stmt = $conn->prepare($query);
$stmt->execute();
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nếu có game_id, lấy danh sách sets
$sets = [];
if ($selected_game) {
  $query = "SELECT id, name FROM sets WHERE game_id = ? ORDER BY release_date DESC";
  $stmt = $conn->prepare($query);
  $stmt->execute([$selected_game]);
  $sets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="store-products">
  <h1 class="title">Products Management</h1>
  <div class="store-products__actions">
    <label for="gameSelect">Game:</label>
    <select class="store-products__select" id="gameSelect">
      <option value="">All Games</option>
      <?php foreach ($games as $game): ?>
        <option value="<?php echo $game['id']; ?>">
          <?php echo $game['id']; ?> - <?php echo $game['name']; ?>
        </option>
      <?php endforeach; ?>
    </select>
    <label for="setSelect">Set:</label>
    <select class="store-products__select" id="setSelect" disabled>
      <option value="">All Sets</option>
      <?php foreach ($sets as $set): ?>
        <option value="<?php echo $set['id']; ?>">
          <?php echo $set['id']; ?> - <?php echo $set['name']; ?>
        </option>
      <?php endforeach; ?>
    </select>
    <button type="button" id="get-cards-btn">Get</button>
    <div class="store-products__search">
      <input autocomplete="off" type="text" id="search-input" placeholder="Search card by name">
    </div>
  </div>
  <div class="store-products__table" id="cardTableContainer">
  </div>
</div>


<script>
  $(document).ready(function() {
    let searchTimeout;

    // Thêm event listener cho input search
    $('#search-input').on('input', function() {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        const searchTerm = $(this).val();
        const gameId = $('#gameSelect').val();
        const setId = $('#setSelect').val();
        loadCardTable(gameId, setId, searchTerm);
      }, 100);
    });

    // Cập nhật hàm loadCardTable để nhận thêm tham số searchTerm
    function loadCardTable(gameId, setId, searchTerm = '') {
      $.ajax({
        url: '/views/stores/tabs/card_table.php',
        method: 'GET',
        data: {
          game_id: gameId,
          set_id: setId,
          search: searchTerm
        },
        success: function(response) {
          if (!response.trim()) {
            $('#cardTableContainer').html(`
              <div class="store-products__message">
                No cards found for selected criteria.
              </div>
            `);
          } else {
            $('#cardTableContainer').html(response);
          }
        }
      });
    }

    // Cập nhật các lời gọi loadCardTable khác để thêm searchTerm
    $('#get-cards-btn').click(function() {
      const gameId = $('#gameSelect').val();
      const setId = $('#setSelect').val();
      const searchTerm = $('#search-input').val();
      loadCardTable(gameId, setId, searchTerm);
    });

    // Tự động load tất cả cards khi trang được load
    loadCardTable('', '');
    // Tự động select game và set từ URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const gameId = urlParams.get('game_id');
    const setId = urlParams.get('set_id');

    if (gameId) {
      $('#gameSelect').val(gameId);
      if (gameId) {
        $.ajax({
          url: '/views/stores/actions/get_sets.php',
          method: 'GET',
          data: {
            game_id: gameId
          },
          success: function(response) {
            const setSelect = $('#setSelect');
            setSelect.html('<option value="">All Sets</option>' + response);
            setSelect.prop('disabled', false);
            if (setId) {
              setSelect.val(setId);
              loadCardTable(gameId, setId);
            }
          }
        });
      }
    }

    // Xử lý khi chọn game
    $('#gameSelect').change(function() {
      const gameId = $(this).val();
      const setSelect = $('#setSelect');

      if (gameId) {
        $.ajax({
          url: '/views/stores/actions/get_sets.php',
          method: 'GET',
          data: {
            game_id: gameId
          },
          success: function(response) {
            setSelect.html('<option value="">All Sets</option>' + response);
            setSelect.prop('disabled', false);

          },

        });
      } else {
        setSelect.html('<option value="">All Sets</option>');
        setSelect.prop('disabled', true);
      }
    });
  });
</script>