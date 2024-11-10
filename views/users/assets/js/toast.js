function showToast(message, duration = 3000) {
    const toast = document.getElementById('user__toast');
    const toastMessage = document.getElementById('user__toast-message');
    
    if (toast && toastMessage) {
        toastMessage.textContent = message;
        toast.classList.remove('show');
        void toast.offsetWidth;
        toast.classList.add('show');
    }
} 