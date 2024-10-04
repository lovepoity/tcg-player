// Custom Select Functionality
document.querySelectorAll('.custom-select-wrapper').forEach(select => {
  const selectTrigger = select.querySelector('.custom-select__trigger');
  const options = select.querySelectorAll('.custom-option');
  const originalSelect = select.querySelector('select');

  // Toggle open/close of custom select
  selectTrigger.addEventListener('click', () => {
    select.querySelector('.custom-select').classList.toggle('open');
  });

  // Close custom select when clicking outside
  document.addEventListener('click', (e) => {
    if (!select.contains(e.target)) {
      select.querySelector('.custom-select').classList.remove('open');
    }
  });

  // Handle option selection
  options.forEach(option => {
    option.addEventListener('click', () => {
      if (!option.classList.contains('selected')) {
        options.forEach(opt => opt.classList.remove('selected'));
        option.classList.add('selected');
        selectTrigger.textContent = option.textContent;
        originalSelect.value = option.getAttribute('data-value');
        originalSelect.dispatchEvent(new Event('change'));
      }
      select.querySelector('.custom-select').classList.remove('open');
    });
  });
});
