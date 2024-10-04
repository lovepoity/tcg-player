<?php
require_once '../../includes/db_connect.php';

// Lấy danh sách cửa hàng
$store_query = "SELECT * FROM stores ORDER BY name";
$store_stmt = $conn->query($store_query);
$all_stores = $store_stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy danh sách game
$game_query = "SELECT * FROM games ORDER BY name";
$game_stmt = $conn->query($game_query);
$all_games = $game_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="admin-store">
  <h1 class="title">Quản lý cửa hàng</h1>

  <div class="store-filter-form">
    <div class="form-group store-select-container">
      <label for="store-select">Chọn cửa hàng:</label>
      <select id="store-select" name="stores[]" multiple required>
        <?php foreach ($all_stores as $store): ?>
          <option value="<?php echo $store['id']; ?>"><?php echo htmlspecialchars($store['name']); ?></option>
        <?php endforeach; ?>
      </select>
      <div class="custom-select">
        <?php foreach ($all_stores as $store): ?>
          <div class="custom-option">
            <input type="checkbox" id="store-<?php echo $store['id']; ?>" value="<?php echo $store['id']; ?>">
            <label for="store-<?php echo $store['id']; ?>"><?php echo htmlspecialchars($store['name']); ?></label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="selected-stores"></div>

    <div class="form-group">
      <label for="game-select">Trò chơi:</label>
      <select id="game-select" name="game_id" class="form-control" required>
        <option value="">Chọn trò chơi</option>
        <?php foreach ($all_games as $game): ?>
          <option value="<?php echo $game['id']; ?>"><?php echo htmlspecialchars($game['name']); ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="set-select">Bộ bài:</label>
      <select id="set-select" name="set_id" class="form-control" required disabled>
        <option value="">Chọn bộ bài</option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
  </div>
</div>

<div id="card-table-container" class="card-grid"></div>

<!-- Modal for updating price -->
<div id="update-price-modal" class="modal">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Cập nhật giá</h2>
    <form id="update-price-form">
      <input type="hidden" name="card_id" id="modal-card-id">
      <input type="hidden" name="store_id" id="modal-store-id">
      <div class="form-group">
        <label for="modal-price">Giá:</label>
        <input type="number" id="modal-price" name="price" step="0.01" min="0" required>
      </div>
      <div class="form-group">
        <label for="modal-quantity">Số lượng:</label>
        <input type="number" id="modal-quantity" name="quantity" min="0" required>
      </div>
      <div class="form-group">
        <label for="modal-shipping">Phí vận chuyển:</label>
        <input type="number" id="modal-shipping" name="shipping" step="0.01" min="0" required>
      </div>
      <button type="submit" class="btn btn-primary">Cập nhật</button>
    </form>
  </div>
</div>

<script>
  $(document).ready(function() {
    const storeSelect = $('#store-select');
    const customSelect = $('.custom-select');
    const selectedStores = $('.selected-stores');

    storeSelect.on('click', function(e) {
      e.preventDefault();
      customSelect.toggleClass('active');
    });

    $('.custom-option input[type="checkbox"]').on('change', function() {
      const storeId = $(this).val();
      const storeName = $(this).next('label').text();

      if (this.checked) {
        storeSelect.find(`option[value="${storeId}"]`).prop('selected', true);
        selectedStores.append(`<span class="selected-store" data-id="${storeId}">${storeName} <span class="remove-store">&times;</span></span>`);
      } else {
        storeSelect.find(`option[value="${storeId}"]`).prop('selected', false);
        selectedStores.find(`[data-id="${storeId}"]`).remove();
      }
    });

    selectedStores.on('click', '.remove-store', function() {
      const storeId = $(this).parent().data('id');
      $(this).parent().remove();
      storeSelect.find(`option[value="${storeId}"]`).prop('selected', false);
      $(`.custom-option input[value="${storeId}"]`).prop('checked', false);
    });

    $(document).on('click', function(e) {
      if (!$(e.target).closest('.store-select-container').length) {
        customSelect.removeClass('active');
      }
    });

    // Xử lý chọn game và load danh sách bộ bài
    $('#game-select').change(function() {
      var gameId = $(this).val();
      var setSelect = $('#set-select');

      if (gameId) {
        $.ajax({
          url: '../actions/get_sets.php',
          method: 'GET',
          data: {
            game_id: gameId
          },
          success: function(response) {
            setSelect.html(response);
            setSelect.prop('disabled', false);
          },
          error: function(xhr, status, error) {
            console.error("Lỗi khi tải danh sách bộ bài:", error);
            setSelect.html('<option value="">Lỗi khi tải bộ bài</option>');
            setSelect.prop('disabled', true);
          }
        });
      } else {
        setSelect.html('<option value="">Chọn bộ bài</option>');
        setSelect.prop('disabled', true);
      }
    });

    // Xử lý submit form
    $('button[type="submit"]').click(function(e) {
      e.preventDefault();
      var stores = $('#store-select').val();
      var gameId = $('#game-select').val();
      var setId = $('#set-select').val();

      if (!stores || stores.length === 0 || !gameId || !setId) {
        alert('Vui lòng chọn cửa hàng, trò chơi và bộ bài');
        return;
      }

      $.ajax({
        url: '../actions/get_store_cards.php',
        method: 'GET',
        data: {
          stores: stores,
          game_id: gameId,
          set_id: setId
        },
        dataType: 'json',
        success: function(response) {
          if (response.error) {
            $('#card-table-container').html('<p>' + response.error + '</p>');
          } else {
            var gridHtml = createCardGrid(response.cards, response.stores);
            $('#card-table-container').html(gridHtml);
          }
        },
        error: function(xhr, status, error) {
          console.error("Lỗi khi tải danh sách thẻ:", error);
          $('#card-table-container').html('<p>Lỗi khi tải danh sách thẻ. Vui lòng thử lại.</p>');
        }
      });
    });

    function createCardGrid(cards, stores) {
      var html = '';
      cards.forEach(function(card) {
        html += '<div class="card-item">';
        html += '<img src="../../public/images/product/' + card.image_filename + '" alt="' + card.name + '">';
        html += '<h3>' + card.name + '</h3>';

        for (var storeId in stores) {
          html += '<div class="store-price">';
          html += '<span>' + stores[storeId].name + ': ';
          if (card.prices[storeId]) {
            html += '$' + card.prices[storeId].price + ' (SL: ' + card.prices[storeId].quantity + ')';
          } else {
            html += 'N/A';
          }
          html += '</span>';
          html += '<button class="update-price-btn" data-card-id="' + card.id + '" data-store-id="' + storeId + '">Cập nhật</button>';
          html += '</div>';
        }

        html += '</div>';
      });
      return html;
    }

    // Handle update price button click
    $(document).on('click', '.update-price-btn', function() {
      var cardId = $(this).data('card-id');
      var storeId = $(this).data('store-id');

      // Populate modal with current values
      $('#modal-card-id').val(cardId);
      $('#modal-store-id').val(storeId);

      // Show modal
      $('#update-price-modal').show();
    });

    // Handle modal close
    $('.close').click(function() {
      $('#update-price-modal').hide();
    });

    // Handle update price form submission
    $('#update-price-form').submit(function(e) {
      e.preventDefault();

      $.ajax({
        url: '../actions/update_card_price.php',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            alert('Cập nhật thành công!');
            $('#update-price-modal').hide();
            // Refresh the card grid
            $('button[type="submit"]').click();
          } else {
            alert('Cập nhật thất bại: ' + response.error);
          }
        },
        error: function(xhr, status, error) {
          console.error("Lỗi khi cập nhật giá:", error);
          alert('Lỗi khi cập nhật giá. Vui lòng thử lại.');
        }
      });
    });
  });
</script>