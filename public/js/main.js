document.addEventListener('DOMContentLoaded', function() {
  const slideshowContainer = document.querySelector('.slideshow-container');
  const slideshowWrapper = document.querySelector('.slideshow-wrapper');
  const slideshowPages = document.querySelectorAll('.slideshow-page');
  const navDots = document.querySelectorAll('.nav-dot');
  let currentSlide = 0;
  let startX;
  let currentX;
  let isDragging = false;
  let dragStartTime;
  let dragDistance = 0;

  function preloadImages() {
    slideshowPages.forEach(page => {
      const images = page.querySelectorAll('img');
      images.forEach(img => {
        const src = img.getAttribute('src');
        if (src) {
          const image = new Image();
          image.src = src;
        }
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
    dragStartTime = new Date().getTime();
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

    const dragEndTime = new Date().getTime();
    const dragDuration = dragEndTime - dragStartTime;

    const diff = startX - currentX;
    const threshold = slideshowContainer.offsetWidth * 0.2;

    if (dragDistance > 10 && dragDuration > 100) {
      // Người dùng đã kéo đủ xa và đủ lâu, xử lý như một hành động kéo
      if (Math.abs(diff) > threshold) {
        if (diff > 0 && currentSlide < slideshowPages.length - 1) {
          currentSlide++;
        } else if (diff < 0 && currentSlide > 0) {
          currentSlide--;
        }
      }
      showSlide(currentSlide, true);
    } else {
      // Người dùng chỉ nhấp chuột, không ngăn chặn hành vi mặc định
      showSlide(currentSlide, true);
    }
  }

  function handleClick(e) {
    if (dragDistance > 10) {
      e.preventDefault();
      e.stopPropagation();
    }
  }

  navDots.forEach((dot, index) => {
    dot.addEventListener('click', () => showSlide(index));
  });

  slideshowContainer.addEventListener('mousedown', handleDragStart);
  slideshowContainer.addEventListener('touchstart', handleDragStart, { passive: false });

  slideshowContainer.addEventListener('mousemove', handleDragMove);
  slideshowContainer.addEventListener('touchmove', handleDragMove, { passive: false });

  slideshowContainer.addEventListener('mouseup', handleDragEnd);
  slideshowContainer.addEventListener('touchend', handleDragEnd);
  slideshowContainer.addEventListener('mouseleave', handleDragEnd);

  slideshowContainer.addEventListener('dragstart', (e) => e.preventDefault());

  // Thêm sự kiện click cho các phần tử bên trong slideshow
  slideshowContainer.addEventListener('click', handleClick, true);

  preloadImages();
  showSlide(0, false);
});