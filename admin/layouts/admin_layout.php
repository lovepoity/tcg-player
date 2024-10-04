<?php
session_start();
include '../../includes/db_connect.php';
if (!isset($_SESSION['admin_id'])) {
  header("Location: ../auth/login.php");
  exit();
}
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
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
  <link rel="icon" href="/public/images/favicon.ico">
  <!-- CSS -->
  <link rel="stylesheet" href="/admin/assets/css/styles.css">
  <link rel="stylesheet" href="/admin/assets/css/content.css">
  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <title>Admin Panel</title>
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
          <li><a href="#" data-page="products"><i class="fa-solid fa-box"></i> Products</a></li>
          <li><a href="#" data-page="store"><i class="fa-solid fa-store"></i> Store</a></li>
          <li><a href="#" data-page="add_card"><i class="fa-solid fa-plus"></i> Add Card</a></li>
          <li><a href="#" data-page="orders"><i class="fa-solid fa-box"></i> Orders</a></li>
        </ul>
      </nav>
    </aside>
    <div class="content">
      <header class="header">
        <div class="logo">Admin Panel</div>
        <div class="user-info">
          <span>Welcome, Admin</span>
          <a href="/admin/auth/logout.php">Logout</a>
        </div>
      </header>
      <main class="main" id="main-content">
        <!-- Content will be loaded here via AJAX -->
      </main>
    </div>
  </div>
  <!-- SCRIPT -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      function loadPage(pageName) {
        $.ajax({
          url: '../pages/' + pageName + '.php',
          method: 'GET',
          success: function(response) {
            $('#main-content').html(response);
            $('.sidebar nav ul li a').removeClass('active');
            $('.sidebar nav ul li a[data-page="' + pageName + '"]').addClass('active');
            history.pushState(null, '', 'admin_layout.php?page=' + pageName);
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

      // Load initial page
      var initialPage = '<?php echo $page; ?>';
      loadPage(initialPage);
    });
  </script>
  <script src="/admin/assets/scripts.js"></script>
</body>

</html>