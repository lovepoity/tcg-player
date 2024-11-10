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