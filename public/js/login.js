
function togglePasswordVisibility(passwordInput, toggleIcon) {
  const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
  passwordInput.setAttribute('type', type);
  toggleIcon.classList.toggle('fa-eye-slash');
  toggleIcon.classList.toggle('fa-eye');
}

function handleFormSubmit(form, successCallback, errorCallback) {
  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        successCallback(data);
      } else {
        errorCallback(data);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      errorCallback({ message: 'An error occurred. Please try again.' });
    });
  });
}
function clearMessageOnInput(inputElements, messageElement) {
  inputElements.forEach(input => {
    input.addEventListener('input', function() {
      messageElement.textContent = '';
      messageElement.className = 'message';
    });
  });
}

