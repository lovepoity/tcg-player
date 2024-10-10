$(document).ready(function() {
  let currentFocus = -1;
  let cardIdToDelete;

  function loadCards(gameId = '', setId = '', cardId = '') {
    $.ajax({
      url: '../actions/get_cards.php',
      method: 'GET',
      data: { game_id: gameId, set_id: setId, card_id: cardId },
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
        data: { game_id: gameId },
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
        data: { search: searchTerm },
        success: function(response) {
          var cards = JSON.parse(response);
          var resultsHtml = '';
          cards.forEach(function(card) {
            resultsHtml += `
              <div class="search-result" data-id="${card.id}">
                <img src="../../public/images/product/${card.image_filename}" alt="${card.name}">
                <div class="search-result-info">
                  <span class="card-name">${card.name}</span>
                  <span class="card-details">${card.game_name}</span>
                  <span class="card-details">${card.set_name}</span>
                </div>
              </div>`;
          });
          $('#search-results').html(resultsHtml).show();
          currentFocus = -1;
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
      data: { id: cardId },
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
    cardIdToDelete = $(this).data('id');
    $('#delete-popup').show();
  });

  $('#confirm-delete').click(function() {
    $.ajax({
      url: '../actions/delete_card.php',
      method: 'POST',
      data: { id: cardIdToDelete },
      success: function(response) {
        $('#delete-popup').hide();
        var result = JSON.parse(response);
        if (result.success) {
          showToast("Card deleted successfully", "success");
          loadCards();
        } else {
          showToast("Error: " + (result.error || "Cannot delete card"), "error");
        }
      },
      error: function(xhr, status, error) {
        console.error("Error deleting card:", error);
        showToast("An error occurred while deleting the card. Please try again.", "error");
      }
    });
  });

  $('#cancel-delete').click(function() {
    $('#delete-popup').hide();
  });

  $(document).on('click', '.nav-link', function(e) {
    e.preventDefault();
    var pageName = $(this).data('page');
    loadPage(pageName);
  });

  function loadPage(pageName) {
    $.ajax({
      url: '../pages/' + pageName + '.php',
      method: 'GET',
      success: function(response) {
        $('#main-content').html(response);
      },
      error: function() {
        $('#main-content').html('<p>Error loading page. Please try again.</p>');
      }
    });
  }
});