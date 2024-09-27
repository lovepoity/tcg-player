document.addEventListener('DOMContentLoaded', function() {
  const mobileMenuButton = document.querySelector('.header__mobile-menu');
  const headerList = document.querySelector('.header__list');

  mobileMenuButton.addEventListener('click', function() {
    headerList.style.display = headerList.style.display === 'flex' ? 'none' : 'flex';
  });
});

document.getElementById('searchInput').addEventListener('input', function() {
  const query = this.value;

  if (query.length > 0) {
    fetch(`/views/search_suggestions.php?query=${query}`)
      .then(response => response.json())
      .then(data => {
        const suggestions = document.getElementById('suggestions');
        suggestions.innerHTML = '';

        data.forEach(item => {
          const div = document.createElement('div');
          div.className = 'suggestion-item';
          div.textContent = item.name;
          div.onclick = function() {
            document.getElementById('searchInput').value = item.name;
            suggestions.innerHTML = '';
          };
          suggestions.appendChild(div);
        });
      });
  } else {
    document.getElementById('suggestions').innerHTML = '';
  }
});