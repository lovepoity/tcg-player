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
<script>
  $(document).ready(function() {
    let currentFocus = -1;

    function loadCards(gameId = '', setId = '', cardId = '') {
      $.ajax({
        url: '../actions/get_cards.php',
        method: 'GET',
        data: {
          game_id: gameId,
          set_id: setId,
          card_id: cardId
        },
        success: function(response) {
          $('#card-table-container').html(response);
        },
        error: function(xhr, status, error) {
          console.error("Error loading cards:", error);
          $('#card-table-container').html('<div class="alert alert-danger">An error occurred while loading card data.</div>');
        }
      });
    }

    $('#game-select').change(function() {
      var gameId = $(this).val();
      if (gameId) {
        $.ajax({
          url: '../actions/get_sets.php',
          method: 'GET',
          data: {
            game_id: gameId
          },
          success: function(response) {
            $('#set-select').html(response).prop('disabled', false);
          },
          error: function(xhr, status, error) {
            console.error("Error loading sets:", error);
            $('#set-select').html('<option value="">Error loading sets</option>').prop('disabled', true);
          }
        });
      } else {
        $('#set-select').html('<option value="">All Sets</option>').prop('disabled', true);
      }
    });

    $('#get-cards-btn').click(function() {
      var gameId = $('#game-select').val();
      var setId = $('#set-select').val();
      loadCards(gameId, setId);
    });

    $('#search-input').on('input', function() {
      var searchTerm = $(this).val();
      if (searchTerm.length >= 1) {
        $.ajax({
          url: '../actions/search_cards.php',
          method: 'GET',
          data: {
            search: searchTerm
          },
          success: function(response) {
            var cards = JSON.parse(response);
            var resultsHtml = '';
            cards.forEach(function(card) {
              resultsHtml += '<div class="search-result" data-id="' + card.id + '">' +
                '<img src="../../public/images/product/' + card.image_filename + '" alt="' + card.name + '">' +
                '<div class="search-result-info">' +
                '<span class="card-name">' + card.name + '</span>' +
                '<span class="card-details">' + card.game_name + '</span>' +
                '<span class="card-details">' + card.set_name + '</span>' +
                '</div>' +
                '</div>';
            });
            $('#search-results').html(resultsHtml).show();
            currentFocus = -1; // Reset focus when new results are loaded
          },
          error: function(xhr, status, error) {
            console.error("Error searching cards:", error);
          }
        });
      } else {
        $('#search-results').hide();
      }
    });

    $('#search-input').on('keydown', function(e) {
      var results = $('.search-result');
      if (e.keyCode == 40) { // Down arrow
        e.preventDefault();
        currentFocus++;
        addActive(results);
      } else if (e.keyCode == 38) { // Up arrow
        e.preventDefault();
        currentFocus--;
        addActive(results);
      } else if (e.keyCode == 13) { // Enter
        e.preventDefault();
        if (currentFocus > -1) {
          if (results.length) results[currentFocus].click();
        }
      }
    });

    function addActive(results) {
      if (!results) return false;
      removeActive(results);
      if (currentFocus >= results.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (results.length - 1);
      $(results[currentFocus]).addClass('active');
      $(results[currentFocus])[0].scrollIntoView({
        behavior: 'smooth',
        block: 'nearest'
      });
    }

    function removeActive(results) {
      for (var i = 0; i < results.length; i++) {
        $(results[i]).removeClass('active');
      }
    }

    $(document).on('click', '.search-result', function() {
      var cardId = $(this).data('id');
      var cardName = $(this).find('.card-name').text();
      $('#search-input').val(cardName);
      $('#search-results').hide();
      loadCards('', '', cardId);
      currentFocus = -1;
    });

    $(document).on('click', '.edit-card', function(e) {
      e.preventDefault();
      var cardId = $(this).data('id');
      $.ajax({
        url: '../pages/edit_card.php',
        method: 'GET',
        data: {
          id: cardId
        },
        success: function(response) {
          $('#main-content').html(response);
        },
        error: function(xhr, status, error) {
          console.error("Error loading edit form:", error);
          $('#main-content').html('<p>Error loading edit form. Please try again.</p>');
        }
      });
    });

    $(document).on('click', '.delete-card', function(e) {
      e.preventDefault();
      var cardId = $(this).data('id');
      if (confirm('Are you sure you want to delete this card?')) {
        $.ajax({
          url: '../actions/delete_card.php',
          method: 'POST',
          data: {
            id: cardId
          },
          success: function(response) {
            loadCards();
          },
          error: function(xhr, status, error) {
            console.error("Error deleting card:", error);
            alert("Error deleting card. Please try again.");
          }
        });
      }
    });
  });
</script>