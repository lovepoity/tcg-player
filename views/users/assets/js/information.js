document.addEventListener('DOMContentLoaded', function() {
    initInformationForm();
});

document.addEventListener('contentLoaded', initInformationForm);

function initInformationForm() {
    const informationForm = document.getElementById('informationForm');

    if (informationForm) {
        informationForm.removeEventListener('submit', handleSubmit);
        informationForm.addEventListener('submit', handleSubmit);
    }
}

function handleSubmit(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const formData = new FormData(this);
    
    fetch('./functions/information.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Information updated successfully.');
        } else {
            showToast(data.message || 'Failed to update information.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating information.');
    });
}
