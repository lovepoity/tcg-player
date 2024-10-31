document.addEventListener('DOMContentLoaded', function() {
    initDataForms();
    initPasswordValidation();
});

document.addEventListener('contentLoaded', function() {
    initDataForms();
    initPasswordValidation();
});

function initDataForms() {
    const emailForm = document.getElementById('emailForm');
    const passwordForm = document.getElementById('passwordForm');

    if (emailForm) {
        emailForm.removeEventListener('submit', handleEmailSubmit);
        emailForm.addEventListener('submit', handleEmailSubmit);
    }

    if (passwordForm) {
        passwordForm.removeEventListener('submit', handlePasswordSubmit);
        passwordForm.addEventListener('submit', handlePasswordSubmit);
    }
}

function initPasswordValidation() {
    const passwordInput = document.getElementById('newPassword');
    if (!passwordInput) return;

    const requirements = {
        length: { element: document.getElementById('length'), regex: /.{8,}/ },
        lowercase: { element: document.getElementById('lowercase'), regex: /[a-z]/ },
        number: { element: document.getElementById('number'), regex: /[0-9]/ },
        uppercase: { element: document.getElementById('uppercase'), regex: /[A-Z]/ }
    };

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        // Kiểm tra từng điều kiện và cập nhật class
        Object.keys(requirements).forEach(key => {
            const requirement = requirements[key];
            const isValid = requirement.regex.test(password);
            requirement.element.classList.toggle('valid', isValid);
        });
    });
}

function handleEmailSubmit(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const formData = new FormData(this);
    const newEmail = formData.get('email');
    const confirmEmail = formData.get('confirm_email');
    const password = formData.get('password');

    // Validate email
    if (!newEmail) {
        showToast('Please enter new email.');
        return;
    }

    // Validate confirm email
    if (!confirmEmail) {
        showToast('Please confirm your email.');
        return;
    }

    // Check if emails match
    if (newEmail !== confirmEmail) {
        showToast('Emails do not match.');
        return;
    }

    // Validate password
    if (!password) {
        showToast('Please enter your password');
        return;
    }
    
    fetch('./functions/update_email.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Email updated successfully.');
            this.reset();
        } else {
            showToast(data.message || 'Failed to update email.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating email.');
    });
}

function handlePasswordSubmit(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const formData = new FormData(this);
    const newPassword = formData.get('password');
    
    // Kiểm tra current password có được nhập không
    if (!formData.get('current_password')) {
        showToast('Please enter your current password.');
        return;
    }

    // Kiểm tra new password có được nhập không
    if (!newPassword) {
        showToast('Please enter new password');
        return;
    }

    // Kiểm tra độ dài password
    if (newPassword.length < 8) {
        showToast('Password must be at least 8 characters long.');
        return;
    }

    // Kiểm tra có chữ hoa không
    if (!/[A-Z]/.test(newPassword)) {
        showToast('Password must contain at least one uppercase letter.');
        return;
    }

    // Kiểm tra có số không
    if (!/[0-9]/.test(newPassword)) {
        showToast('Password must contain at least one number.');
        return;
    }

    // Kiểm tra có chữ thường không
    if (!/[a-z]/.test(newPassword)) {
        showToast('Password must contain at least one lowercase letter.');
        return;
    }

    // Kiểm tra confirm password có khớp không
    if (newPassword !== formData.get('confirm_password')) {
        showToast('New passwords do not match.');
        return;
    }
    
    fetch('./functions/update_password.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message || 'Password updated successfully.');
            this.reset();
            // Reset validation indicators
            document.querySelectorAll('.requirement').forEach(el => {
                el.classList.remove('valid');
            });
        } else {
            showToast(data.message || 'Failed to update password.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred while updating password.');
    });
}
