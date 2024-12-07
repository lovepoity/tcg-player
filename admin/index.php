<?php
session_start();
include '../includes/db_connect.php';
if (!isset($_SESSION['admin_id'])) {
  header("Location: auth/login.php");
  exit();
}
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- FONTAWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- FAVICON -->
  <link rel="icon" href="/public/images/favicon.ico">
  <!-- CSS -->
  <link rel="stylesheet" href="/admin/assets/css/styles.css">
  <link rel="stylesheet" href="/admin/assets/css/web_setting.css">
  <link rel="stylesheet" href="/admin/assets/css/product.css">
  <link rel="stylesheet" href="/admin/assets/css/stores.css">
  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>Admin Control Panel</title>
</head>

<body>
  <div class="container">
    <aside class="sidebar">
      <div class="logo">
        <img src="/public/images/logo.png" alt="Logo">
      </div>
      <nav>
        <ul>
          <li><a href="#" data-page="dashboard"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
          <li><a href="#" data-page="website_settings"><i class="fa-solid fa-cog"></i> Website Settings</a></li>
          <li><a href="#" data-page="products"><i class="fa-solid fa-box"></i> Products Management</a></li>
          <li><a href="#" data-page="stores"><i class="fa-solid fa-store"></i> Stores Configuration</a></li>
          <li><a href="#" data-page="users"><i class="fa-solid fa-user"></i> Users</a></li>
          <li><a href="#" data-page="orders"><i class="fa-solid fa-box"></i> Orders Management</a></li>
          <li><a href="#" data-page="faqs"><i class="fa-solid fa-question"></i> FAQs</a></li>
        </ul>
      </nav>
      <div class="logout">
        <a href="/admin/auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </div>
    </aside>
    <div class="content">
      <header class="header">
        <div class="logo">Admin Control Panel</div>
        <div class="user-info">
          <span>Welcome, Admin</span>
          <img src="/public/images/admin.png" alt="Admin">
        </div>
      </header>
      <main class="main" id="main-content">
      </main>
    </div>
  </div>
  <script>
    $(document).ready(function() {
      function loadPage(pageName) {
        $.ajax({
          url: 'pages/' + pageName + '.php',
          method: 'GET',
          success: function(response) {
            $('#main-content').html(response);
            $('.sidebar nav ul li a').removeClass('active');
            $('.sidebar nav ul li a[data-page="' + pageName + '"]').addClass('active');
            history.pushState(null, '', 'index.php?page=' + pageName);
          },
          error: function() {
            $('#main-content').html('<p>Error loading page. Please try again.</p>');
          }
        });
      }

      $('.sidebar nav ul li a').on('click', function(e) {
        e.preventDefault();
        var pageName = $(this).data('page');
        loadPage(pageName);
      });

      var initialPage = '<?php echo $page; ?>';
      loadPage(initialPage);
    });
  </script>
  <script src="/admin/assets/js/scripts.js"></script>
</body>

</html>