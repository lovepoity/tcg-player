document.addEventListener('DOMContentLoaded', function() {
  const menuItems = document.querySelectorAll('.header__list > li');
  const overlay = document.getElementById('overlay');
  const searchInput = document.getElementById('search-input');
  const searchResults = document.getElementById('search-results');
  const gameSelect = document.getElementById('game-select');
  let currentFocus = -1;

  function closeAllDropdowns() {
    menuItems.forEach(item => {
      item.classList.remove('active');
      const submenu = item.querySelector('.header__submenu');
      if (submenu) submenu.style.display = 'none';
    });
    searchResults.style.display = 'none';
    if (userMenu) userMenu.style.display = 'none';
    overlay.style.display = 'none';
  }

  function showOverlay() {
    overlay.style.display = 'block';
  }

  function handleMenuItemClick(e) {
    e.stopPropagation();
    const submenu = this.querySelector('.header__submenu');
    if (submenu) {
      closeAllDropdowns();
      if (submenu.style.display !== 'block') {
        submenu.style.display = 'block';
        this.classList.add('active');
        showOverlay();
      }
    }
  }

  function handleSearchInput() {
    const searchTerm = this.value.trim();
    const selectedGame = gameSelect.value;

    if (searchTerm.length >= 1) {
      fetch(`/views/search_cards.php?search=${encodeURIComponent(searchTerm)}&game_id=${selectedGame}`)
        .then(response => response.json())
        .then(cards => {
          const resultsHtml = cards.map(card => `
            <div class="search-result" data-id="${card.id}">
              <img src="/public/images/product/${card.image_filename}" alt="${card.name}">
              <div class="search-result-info">
                <span class="card-name">${card.name}</span>
                <span class="card-details">${card.game_name}</span>
                <span class="card-details">${card.set_name}</span>
              </div>
            </div>
          `).join('');

          searchResults.innerHTML = resultsHtml;
          searchResults.style.display = cards.length > 0 ? 'block' : 'none';
          if (cards.length > 0) showOverlay();

          // Thêm sự kiện click cho mỗi kết quả tìm kiếm
          searchResults.querySelectorAll('.search-result').forEach(item => {
            item.addEventListener('click', function(e) {
              e.preventDefault();
              e.stopPropagation();
              const cardId = this.dataset.id;
              window.location.href = `/views/card_details.php?id=${cardId}`;
            });
          });
        })
        .catch(error => console.error('Error:', error));
    } else {
      closeAllDropdowns();
    }
  }

  function handleSearchKeydown(e) {
    const results = searchResults.getElementsByClassName('search-result');
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
      e.preventDefault();
      currentFocus += e.key === 'ArrowDown' ? 1 : -1;
      addActive(results);
    } else if (e.key === 'Enter') {
      e.preventDefault();
      if (currentFocus > -1 && results[currentFocus]) {
        results[currentFocus].click();
      }
    }
  }

  function addActive(results) {
    if (!results.length) return;
    removeActive(results);
    currentFocus = (currentFocus + results.length) % results.length;
    results[currentFocus].classList.add('active');
    results[currentFocus].scrollIntoView({
      behavior: 'smooth',
      block: 'nearest'
    });
  }

  function removeActive(results) {
    Array.from(results).forEach(result => result.classList.remove('active'));
  }

  menuItems.forEach(item => item.addEventListener('click', handleMenuItemClick));
  overlay.addEventListener('click', closeAllDropdowns);
  document.addEventListener('click', closeAllDropdowns);
  searchResults.addEventListener('click', e => e.stopPropagation());
  searchInput.addEventListener('click', e => e.stopPropagation());
  searchInput.addEventListener('input', handleSearchInput);
  searchInput.addEventListener('keydown', handleSearchKeydown);

  // Xử lý menu user
  const userIcon = document.querySelector('.header__user');
  const userMenu = document.querySelector('.header__user-menu');

  if (userIcon && userMenu) {
    userIcon.addEventListener('click', function(e) {
      e.stopPropagation();
      closeAllDropdowns();
      if (userMenu.style.display !== 'block') {
        userMenu.style.display = 'block';
        showOverlay();
      }
    });
  }

  // Đóng menu khi click bên ngoài
  document.addEventListener('click', function(e) {
    if (userIcon && userMenu && !userIcon.contains(e.target) && !userMenu.contains(e.target)) {
      userMenu.style.display = 'none';
    }
  });
});
