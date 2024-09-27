document.addEventListener('DOMContentLoaded', function() {
  const mobileMenuButton = document.querySelector('.header__mobile-menu');
  const headerList = document.querySelector('.header__list');

  mobileMenuButton.addEventListener('click', function() {
    headerList.style.display = headerList.style.display === 'flex' ? 'none' : 'flex';
  });
});