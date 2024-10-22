document.addEventListener('DOMContentLoaded', function() {
  const slideshowContainer = document.querySelector('.slideshow-container');
  const slideshowWrapper = document.querySelector('.slideshow-wrapper');
  const slideshowPages = document.querySelectorAll('.slideshow-page');
  const navDots = document.querySelectorAll('.nav-dot');
  const productGrid = document.querySelector('.product-grid');
  const pagination = document.querySelector('.pagination');
  const subLocation = document.querySelector('.sub__location');
  const listingsContainer = document.querySelector('.card__detail-listing');
  const listingsHeader = listingsContainer.querySelector('.listing__header');
  const sortDropdown = document.getElementById('sort');

  let currentSlide = 0;
  let startX, currentX, isDragging = false, dragStartTime, dragDistance = 0;

  function preloadImages() {
    slideshowPages.forEach(page => {
      page.querySelectorAll('img').forEach(img => {
        const src = img.getAttribute('src');
        if (src) new Image().src = src;
      });
    });
  }

  function showSlide(index, animate = true) {
    if (index < 0) index = 0;
    if (index >= slideshowPages.length) index = slideshowPages.length - 1;

    const offset = -index * 100;
    slideshowWrapper.style.transition = animate ? 'transform 0.3s ease' : 'none';
    slideshowWrapper.style.transform = `translateX(${offset}%)`;

    navDots.forEach((dot, i) => dot.classList.toggle('active', i === index));
    currentSlide = index;
  }

  function handleDragStart(e) {
    e.preventDefault();
    isDragging = true;
    startX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
    currentX = startX;
    dragStartTime = Date.now();
    dragDistance = 0;
    slideshowContainer.style.cursor = 'grabbing';
    slideshowWrapper.style.transition = 'none';
  }

  function handleDragMove(e) {
    if (!isDragging) return;
    e.preventDefault();
    currentX = e.type.includes('mouse') ? e.pageX : e.touches[0].pageX;
    const diff = startX - currentX;
    dragDistance = Math.abs(diff);
    const offset = -currentSlide * 100 - (diff / slideshowContainer.offsetWidth * 100);
    slideshowWrapper.style.transform = `translateX(${offset}%)`;
  }

  function handleDragEnd(e) {
    e.preventDefault();
    if (!isDragging) return;
    isDragging = false;
    slideshowContainer.style.cursor = 'grab';

    const dragDuration = Date.now() - dragStartTime;
    const diff = startX - currentX;
    const threshold = slideshowContainer.offsetWidth * 0.2;

    if (dragDistance > 10 && dragDuration > 100) {
      if (Math.abs(diff) > threshold) {
        currentSlide += (diff > 0 && currentSlide < slideshowPages.length - 1) ? 1 : (diff < 0 && currentSlide > 0) ? -1 : 0;
      }
      showSlide(currentSlide, true);
    } else {
      showSlide(currentSlide, true);
    }
  }

  function handleClick(e) {
    if (dragDistance > 10) {
      e.preventDefault();
      e.stopPropagation();
    }
  }

  navDots.forEach((dot, index) => dot.addEventListener('click', () => showSlide(index)));

  slideshowContainer.addEventListener('mousedown', handleDragStart);
  slideshowContainer.addEventListener('touchstart', handleDragStart, { passive: false });
  slideshowContainer.addEventListener('mousemove', handleDragMove);
  slideshowContainer.addEventListener('touchmove', handleDragMove, { passive: false });
  slideshowContainer.addEventListener('mouseup', handleDragEnd);
  slideshowContainer.addEventListener('touchend', handleDragEnd);
  slideshowContainer.addEventListener('mouseleave', handleDragEnd);
  slideshowContainer.addEventListener('dragstart', (e) => e.preventDefault());
  slideshowContainer.addEventListener('click', handleClick, true);

  function setupPagination() {
    document.querySelectorAll('.pagination a').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        loadPage(this.getAttribute('href').split('=')[1]);
      });
    });
  }

  function loadPage(page) {
    fetch(`/views/one_piece.php?page=${page}`)
      .then(response => response.text())
      .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        productGrid.innerHTML = doc.querySelector('.product-grid').innerHTML;
        pagination.innerHTML = doc.querySelector('.pagination').innerHTML;
        subLocation.innerHTML = doc.querySelector('.sub__location').innerHTML;
        history.pushState(null, '', `?page=${page}`);
        setupPagination();
        window.scrollTo(0, 0);
      });
  }

  setupPagination();

  window.addEventListener('popstate', function() {
    const page = new URLSearchParams(window.location.search).get('page') || '1';
    loadPage(page);
  });

  function sortListings(order) {
    const listings = Array.from(listingsContainer.querySelectorAll('.listing__store'));

    listings.sort((a, b) => {
      const priceA = parseFloat(a.querySelector('.listing__store-info-price').textContent.replace(/[^0-9.-]+/g, ""));
      const priceB = parseFloat(b.querySelector('.listing__store-info-price').textContent.replace(/[^0-9.-]+/g, ""));
      return order === 'price_asc' ? priceA - priceB : priceB - priceA;
    });

    listingsContainer.innerHTML = ''; // Xóa nội dung hiện tại
    listingsContainer.appendChild(listingsHeader); // Thêm lại tiêu đề
    listings.forEach(listing => listingsContainer.appendChild(listing)); // Thêm lại các listing đã sắp xếp
  }

  sortDropdown.addEventListener('change', function() {
    sortListings(this.value);
  });

  preloadImages();
  showSlide(0, false);
});
