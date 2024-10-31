<?php
require_once '../../includes/login.php';
handleSignUp($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- CSS -->
  <link rel="stylesheet" href="/public/css/main.css">
  <link rel="stylesheet" href="/public/css/login.css">
  <!-- FAVICON -->
  <link rel="icon" href="/public/images/favicon.ico">
  <!-- TITLE -->
  <title>TCG Player Account Registration</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
  <div class="login__img login__img--signup">
    <a href="/index.php"><img src="/public/images/logo.png" alt="logo"></a>
    <div class="login__img__svg">
      <img src="/public/images/sign-in.svg" alt="Sign In">
    </div>
  </div>
  <div class="login__container">
    <div class="login__form">
      <h1>Create an Account</h1>
      <p id="message" class="message"></p>
      <form id="signup-form" action="" method="post">
        <div class="form__input">
          <label for="email">Email</label>
          <input autocomplete="off" type="email" name="email" id="email" required>
        </div>
        <div class="form__input">
          <label for="password">Password</label>
          <div class="password__input">
            <input autocomplete="off" type="password" name="password" id="password" required>
            <i class="fas fa-eye-slash password__toggle"></i>
          </div>
          <div class="password-requirements">
            <span>At least:</span>
            <span id="length" class="requirement">8 characters &nbsp;- </span>
            <span id="lowercase" class="requirement">1 lowercase &nbsp;- </span>
            <span id="number" class="requirement">1 number &nbsp;- </span>
            <span id="uppercase" class="requirement">1 uppercase</span>
          </div>
        </div>
        <button id="submit-btn" class="login__btn login__btn--signup" type="submit">Create Account</button>
      </form>
      <span class="login__captcha">This site is protected by hCaptcha and its <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a> apply.</span>
      <div class="login__section">
        Already have an Account?
        <a href="/views/login/sign_in.php">Sign in</a>
      </div>
    </div>
  </div>

  <script src="/public/js/login.js"></script>
  <script>
    const form = document.getElementById('signup-form');
    const submitBtn = document.getElementById('submit-btn');
    const messageElement = document.getElementById('message');
    let isRegistered = false;

    // Khởi tạo password validation
    initPasswordValidation();

    handleFormSubmit(form,
      (data) => {
        submitBtn.textContent = 'Back to Login';
        isRegistered = true;
        messageElement.textContent = data.message;
        messageElement.className = 'message message--success';
      },
      (data) => {
        messageElement.textContent = data.message;
        messageElement.className = 'message message--error';
      }
    );

    clearMessageOnInput(document.querySelectorAll('input'), messageElement);

    document.querySelectorAll('.password__toggle').forEach(icon => {
      icon.addEventListener('click', () => {
        togglePasswordVisibility(icon.previousElementSibling, icon);
      });
    });

    submitBtn.addEventListener('click', function(e) {
      if (isRegistered) {
        e.preventDefault();
        window.location.href = '/views/login/sign_in.php';
      }
    });
  </script>
</body>

</html>