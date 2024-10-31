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

    const password = formData.get('password');
    if (password) {
      if (password.length < 8) {
        errorCallback({ message: 'Password must be at least 8 characters long' });
        return;
      }
      if (!/[A-Z]/.test(password)) {
        errorCallback({ message: 'Password must contain at least one uppercase letter' });
        return;
      }
      if (!/[a-z]/.test(password)) {
        errorCallback({ message: 'Password must contain at least one lowercase letter' });
        return;
      }
      if (!/[0-9]/.test(password)) {
        errorCallback({ message: 'Password must contain at least one number' });
        return;
      }
    }

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

function initPasswordValidation() {
  const passwordInput = document.getElementById('password');
  if (!passwordInput) return;

  const requirements = {
    length: { element: document.getElementById('length'), regex: /.{8,}/ },
    lowercase: { element: document.getElementById('lowercase'), regex: /[a-z]/ },
    number: { element: document.getElementById('number'), regex: /[0-9]/ },
    uppercase: { element: document.getElementById('uppercase'), regex: /[A-Z]/ }
  };

  passwordInput.addEventListener('input', function() {
    const password = this.value;
    
    Object.keys(requirements).forEach(key => {
      const requirement = requirements[key];
      const isValid = requirement.regex.test(password);
      requirement.element.classList.toggle('valid', isValid);
    });
  });
}

