function showToast(message, duration = 4000) {
  const toast = document.getElementById('store__toast');
  const toastMessage = document.getElementById('store__toast-message');

  if (toast && toastMessage) {
    toast.classList.remove('show');
    clearTimeout(toast.hideTimeout);

    setTimeout(() => {
      toastMessage.textContent = message;
      toast.classList.add('show');

      toast.hideTimeout = setTimeout(() => {
        toast.classList.remove('show');
      }, duration);
    }, 300);
  }
}
class StoreSettings {
  constructor() {
    this.infoForm = document.getElementById('store-info-form');
    this.passwordForm = document.getElementById('password-form');
    this.init();
  }

  init() {
    if (this.infoForm) {
      this.infoForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(this.infoForm);

        try {
          const response = await fetch('./actions/update_store_info.php', {
            method: 'POST',
            body: formData
          });
          const data = await response.json();

          if (data.success) {
            showToast('Store information updated successfully');
          } else {
            showToast(data.error || 'Error updating store information');
          }
        } catch (error) {
          console.error('Error:', error);
          showToast('Error updating store information');
        }
      });
    }

    if (this.passwordForm) {
      this.passwordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(this.passwordForm);

        if (formData.get('new_password') !== formData.get('confirm_password')) {
          showToast('New passwords do not match');
          return;
        }

        try {
          const response = await fetch('./actions/update_store_password.php', {
            method: 'POST',
            body: formData
          });
          const data = await response.json();

          if (data.success) {
            showToast('Password updated successfully');
            this.passwordForm.reset();
          } else {
            showToast(data.error || 'Error updating password');
          }
        } catch (error) {
          console.error('Error:', error);
          showToast('Error updating password');
        }
      });
    }
  }
}