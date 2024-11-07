function showToast(message, duration = 5000) {
  const toast = document.getElementById('store__toast');
  const toastMessage = document.getElementById('store__toast-message');
  
  if (toast && toastMessage) {
      toastMessage.textContent = message;
      toast.classList.remove('show');
      void toast.offsetWidth;
      toast.classList.add('show');
  }
}

function handleDropdownMenu() {
  const userDropdown = document.querySelector('.user-dropdown img');
  const dropdownContent = document.querySelector('.dropdown-content');
  
  if (userDropdown && dropdownContent) {
    userDropdown.addEventListener('click', () => {
      dropdownContent.classList.toggle('show');
    });

    document.addEventListener('click', (e) => {
      if (!e.target.matches('.user-dropdown img')) {
        if (dropdownContent.classList.contains('show')) {
          dropdownContent.classList.remove('show');
        }
      }
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  handleDropdownMenu();
}); 