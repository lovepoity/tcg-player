<?php
session_start();
include '../../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $query = "SELECT * FROM admins WHERE username = ? LIMIT 1";
  $stmt = $conn->prepare($query);
  $stmt->execute([$username]);
  $admin = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'error' => "Invalid username or password"]);
  }
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <!-- VIEWPORT -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- FONTAWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- FAVICON -->
  <link rel="icon" href="../../public/images/favicon.ico">
  <!-- CSS -->
  <link rel="stylesheet" href="/admin/assets/css/styles.css">
  <link rel="stylesheet" href="/admin/assets/css/content.css">
  <title>Admin Login</title>
</head>

<body>
  <div class="admin__login">
    <div class="admin__login__form">
      <i class="fa-regular fa-user"></i>
      <p class="admin__login__title">Admin Login</p>
      <p id="error-message" class="login__error"></p>
      <form id="login-form" action="" method="POST">
        <div class="form-group">
          <input autocomplete="off" placeholder="Username" type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
          <input autocomplete="off" placeholder="Password" type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('login-form').addEventListener('submit', function(e) {
      e.preventDefault();
      var xhr = new XMLHttpRequest();
      xhr.open('POST', '', true);
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function() {
        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.success) {
            window.location.href = '../layouts/admin_layout.php?page=dashboard';
          } else {
            document.getElementById('error-message').textContent = response.error;
            document.getElementById('error-message').style.display = 'block';
            // Clear username and password fields
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
          }
        } else {
          document.getElementById('error-message').textContent = 'An error occurred. Please try again.';
          document.getElementById('error-message').style.display = 'block';
        }
      };
      var formData = new FormData(this);
      formData.append('ajax', '1');
      xhr.send(new URLSearchParams(formData));
    });
  </script>
</body>

</html>