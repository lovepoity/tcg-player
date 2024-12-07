document.addEventListener('DOMContentLoaded', function() {
  // Handle specific forms
  const editCardForm = document.getElementById('edit-card-form');
  if (editCardForm) {
    handleFormSubmit(editCardForm, '/admin/pages/edit_card.php', function(response) {
      if (response.success) {
        showToast('Card updated successfully', 'success');
      } else {
        showToast('Error updating card: ' + (response.error || 'Unknown error'), 'error');
      }
    });
  }

  const addCardForm = document.querySelector('form[action*="add_card.php"]');
  if (addCardForm) {
    handleFormSubmit(addCardForm, '/admin/pages/add_card.php', function(response) {
      if (response.success) {
        showToast('Card added successfully', 'success');
        setTimeout(() => {
          window.location.href = '/admin/index.php?page=products';
        }, 2000);
      } else {
        showToast('Error adding card: ' + (response.error || 'Unknown error'), 'error');
      }
    });
  }

  const updatePriceForm = document.getElementById('update-price-form');
  if (updatePriceForm) {
    handleFormSubmit(updatePriceForm, updatePriceForm.action, function(response) {
      if (response.success) {
        showToast('Price updated successfully', 'success');
      } else {
        showToast('Error updating price: ' + (response.error || 'Unknown error'), 'error');
      }
    });
  }
});
