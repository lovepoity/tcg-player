<?php
require_once '../../includes/login.php';
handleLogin($conn);
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
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- TITLE -->
  <title>TCG Player Account Login</title>
</head>

<body>
  <div class="login__img login__img--signin">
    <a href="/index.php"><img src="/public/images/logo.png" alt="logo"></a>
    <div class="login__img__svg">
      <img src="/public/images/sign-in.svg" alt="Sign In">
    </div>
  </div>
  <div class="login__container">
    <div class="login__form">
      <form id="login-form" action="" method="post">
        <h1>Sign in to TCGplayer</h1>
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
        </div>
        <a class="forgot__password" href="/views/login/forgot_password.php">Forgot your password?</a>
        <button class="login__btn login__btn--signin" type="submit">Sign in</button>
      </form>
      <p id="error-message" class="error" style="display: none;"></p>
      <span class="login__captcha">This site is protected by hCaptcha and its <a href="#">Privacy Policy</a> and <a href="#">Terms of Service</a> apply.</span>
      <div class="login__section">
        Don't have an account yet?
        <a href="/views/login/sign_up.php">Make one here</a>
      </div>
    </div>
  </div>

  <script src="/public/js/login.js"></script>
  <script>
    const form = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');

    handleFormSubmit(form,
      (data) => {
        window.location.href = '/index.php';
      },
      (data) => {
        errorMessage.textContent = data.message;
        errorMessage.style.display = 'block';
      }
    );

    document.querySelector('.password__toggle').addEventListener('click', function() {
      togglePasswordVisibility(document.getElementById('password'), this);
    });
  </script>
</body>

</html>