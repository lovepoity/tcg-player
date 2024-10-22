<?php
session_start();
require_once '../../includes/login.php';
handleForgotPassword($conn);
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
  <title>TCG Player Forgot Password</title>
</head>

<body>
  <div class="login__img login__img--signin">
    <a href="/index.php"><img src="/public/images/logo.png" alt="logo"></a>
    <div class="login__img__svg">
      <img src="/public/images/forgot-password.svg" alt="Forgot Password">
    </div>
  </div>
  <div class="login__container">
    <div class="login__form">
      <h1><a href="/views/login/sign_in.php"><i class="fa-solid fa-angle-left"></i></a>Forgot Password</h1>
      <form id="forgot-password-form" action="" method="post">
        <div class="form__input">
          <label for="email">Email</label>
          <input autocomplete="off" type="email" name="email" id="email" required>
        </div>
        <button style="margin-top: 0;" id="submit-btn" class="login__btn login__btn--signin" type="submit">Continue</button>
      </form>
      <p id="message" class="message"></p>
    </div>
  </div>

  <script src="/public/js/login.js"></script>
  <script>
    const form = document.getElementById('forgot-password-form');
    const submitBtn = document.getElementById('submit-btn');
    const messageElement = document.getElementById('message');
    let isEmailSent = false;

    handleFormSubmit(form,
      (data) => {
        submitBtn.textContent = 'Back to Login';
        isEmailSent = true;
        messageElement.className = 'message message--success';
        messageElement.textContent = data.message;
      },
      (data) => {
        messageElement.className = 'message message--error';
        messageElement.textContent = data.message;
      }
    );

    clearMessageOnInput([document.getElementById('email')], messageElement);

    // Thêm xử lý sự kiện click cho nút submit
    submitBtn.addEventListener('click', function(e) {
      if (isEmailSent) {
        e.preventDefault(); // Ngăn form submit
        window.location.href = '/views/login/sign_in.php'; // Chuyển hướng đến trang đăng nhập
      }
    });
  </script>

</body>

</html>