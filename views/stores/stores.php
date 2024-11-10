<?php
session_start();
include '../../includes/db_connect.php';
if (!isset($_SESSION['store_id'])) {
  header("Location: auth/login.php");
  exit();
}
$page = isset($_GET['page']) ? $_GET['page'] : 'store_dashboard';

$store_id = $_SESSION['store_id'];
$query = "SELECT name FROM stores WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$store_id]);
$store = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="icon" href="/public/images/favicon.ico">
  <link rel="stylesheet" href="./assets/css/stores.css">
  <link rel="stylesheet" href="./assets/css/product.css">
  <link rel="stylesheet" href="./assets/css/order.css">
  <title>Store Control Panel</title>
</head>

<body>
  <div class="container">
    <aside class="sidebar">
      <div class="logo">
        <img src="/public/images/logo.png" alt="Logo">
      </div>
      <nav>
        <ul>
          <li><a href="#" data-page="store_dashboard"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
          <li><a href="#" data-page="store_products"><i class="fa-solid fa-box"></i> Products Management</a></li>
          <li><a href="#" data-page="store_orders"><i class="fa-solid fa-shopping-cart"></i> Orders Management</a></li>
          <li><a href="#" data-page="store_financial"><i class="fa-solid fa-dollar-sign"></i> Financial Report</a></li>
          <li><a href="#" data-page="store_settings"><i class="fa-solid fa-cog"></i> Store Settings</a></li>
        </ul>
      </nav>
      <div class="logout">
        <a href="/views/stores/auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </div>
    </aside>
    <div class="content">
      <header class="header">
        <div class="logo">Store Control Panel</div>
        <div class="user-info">
          <span>Welcome, <?php echo htmlspecialchars($store['name']); ?></span>
          <img src="/public/images/admin.png" alt="Store">
        </div>
      </header>
      <main class="main" id="main-content">
      </main>
    </div>
  </div>
  <div id="store__toast" class="store__toast">
    <div class="store__toast-content">
      <p id="store__toast-message"></p>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="./assets/js/stores.js"></script>
  <script src="./assets/js/products.js"></script>
  <script src="./assets/js/order.js"></script>
  <script>
    $(document).ready(function() {
      function loadPage(pageName, params = {}) {
        const currentParams = new URLSearchParams(window.location.search);
        currentParams.forEach((value, key) => {
          if (key !== 'page' && !params[key]) {
            params[key] = value;
          }
        });

        let url = 'tabs/' + pageName + '.php';
        if (Object.keys(params).length > 0) {
          url += '?' + new URLSearchParams(params).toString();
        }

        $.ajax({
          url: url,
          method: 'GET',
          success: function(response) {
            $('#main-content').html(response);
            $('.sidebar nav ul li a').removeClass('active');
            $('.sidebar nav ul li a[data-page="' + pageName + '"]').addClass('active');

            let newUrl = '?page=' + pageName;
            if (Object.keys(params).length > 0) {
              newUrl += '&' + new URLSearchParams(params).toString();
            }
            history.pushState(null, '', newUrl);
          },
          error: function() {
            $('#main-content').html('<p>Error loading page. Please try again.</p>');
          }
        });
      }

      var urlParams = new URLSearchParams(window.location.search);
      var initialPage = urlParams.get('page') || 'store_dashboard';
      var params = {};
      urlParams.forEach((value, key) => {
        if (key !== 'page') {
          params[key] = value;
        }
      });
      loadPage(initialPage, params);

      $('.sidebar nav ul li a').on('click', function(e) {
        e.preventDefault();
        var pageName = $(this).data('page');
        loadPage(pageName);
      });

      window.addEventListener('popstate', function() {
        var urlParams = new URLSearchParams(window.location.search);
        var page = urlParams.get('page') || 'store_dashboard';
        var params = {};
        urlParams.forEach((value, key) => {
          if (key !== 'page') {
            params[key] = value;
          }
        });
        loadPage(page, params);
      });
    });
  </script>
</body>

</html>