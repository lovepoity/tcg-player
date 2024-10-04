<?php
// Kết nối đến cơ sở dữ liệu
include __DIR__ . '/db_connect.php';

// Kiểm tra bảng games
$query_games = "SELECT * FROM games";
$stmt_games = $conn->prepare($query_games);
$stmt_games->execute();
$games_result = $stmt_games->fetchAll(PDO::FETCH_ASSOC);

// Kiểm tra bảng sets
$query_sets = "SELECT * FROM sets";
$stmt_sets = $conn->prepare($query_sets);
$stmt_sets->execute();
$sets_result = $stmt_sets->fetchAll(PDO::FETCH_ASSOC);

// Tiếp tục với truy vấn chính
$query = "SELECT g.id AS game_id, g.name AS game_name, s.id AS set_id, s.name AS set_name 
          FROM games g 
          LEFT JOIN sets s ON g.id = s.game_id 
          ORDER BY g.id ASC, s.release_date ASC";
try {
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Query failed: " . $e->getMessage());
}

// Tổ chức dữ liệu thành cấu trúc game và các bộ
$games = [];
foreach ($result as $row) {
  if (!isset($games[$row['game_id']])) {
    $games[$row['game_id']] = [
      'name' => $row['game_name'],
      'sets' => []
    ];
  }
  if ($row['set_id']) {
    $games[$row['game_id']]['sets'][] = [
      'id' => $row['set_id'],
      'name' => $row['set_name']
    ];
  }
}

// Đếm số lượng game
$count = count($games);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="google-site-verification" content="X250-zh1LNLHHNZgUOHLhOYXGqjLq7x3zibdSh2Uj5w" />
  <meta
    name="description"
    content="TCG Player is an online retail website specializing in TCG gear, desk, chair, keyboard, mouse and headset.">

  <!-- FONTAWESOME -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="icon" href="/public/images/favicon.ico">

  <!-- END FONTAWESOME -->
  <!-- ----------------------------------------------------------------------------- -->
  <!-- CSS -->
  <link rel="stylesheet" href="/public/css/main.css">
  <link rel="stylesheet" href="/public/css/style.css">
  <link rel="stylesheet" href="/public/css/card_all.css">
  <link rel="stylesheet" href="/public/css/card_detail.css">
  <!-- END CSS -->
  <!-- ----------------------------------------------------------------------------- -->
  <title>TCG Player</title>
</head>

<body>
  <!-- HEADER -->
  <header id="header">
    <div class="header__mobile-menu">
      <i class="fa-solid fa-bars"></i>
    </div>
    <div class="header__content">
      <!-- LOGO -->
      <div class="header__logo">
        <a href="/index.php"><img src="/public/images/logo.png" alt="TCG Player" /></a>
      </div>
      <!-- SEARCH -->
      <div class="header__search">
        <select name="" id="">
          <option value="1">All</option>
          <option value="2">Akora TCG</option>
          <option value="3">Alpha Clash</option>
          <option value="4">Alternate Souls</option>
          <option value="5">Argent Saga</option>
          <option value="6">Bakugan TCG</option>
          <option value="7">Battle Spirits</option>
          <option value="8">Books</option>
          <option value="9">Bulk Lots</option>
          <option value="10">Card Sleeves</option>
          <option value="11">Card Storage</option>
          <option value="12">Chrono Clash</option>
          <option value="13">Collectible</option>
          <option value="14">D & D Miniatures</option>
          <option value="15">Deck Boxes</option>
          <option value="16">Dice Masters</option>
          <option value="17">Dungeons</option>
          <option value="18">Digimon Card</option>
          <option value="19">Disney Lorcana</option>
          <option value="20">Dragoborne</option>
        </select>
        <input type="text" placeholder="Search your favorite products">
        <i class="fa-solid fa-magnifying-glass"></i>
      </div>
      <!-- END SEARCH -->
      <!-- ACTION -->
      <div class="header__action">
        <div class="header__signin">Sign In</div>
        <div class="header__user">
          <i class="fa-regular fa-user"></i>
        </div>
        <div class="header__sell">Sell With Us</div>
        <div class="header__cart">
          <i class="fa-solid fa-cart-shopping"></i>
        </div>
      </div>
      <!-- END ACTION -->
    </div>
    <!-- NAVBAR -->
    <div class="header__navbar">
      <ul class="header__list">
        <?php foreach ($games as $game): ?>
          <li>
            <?php echo htmlspecialchars($game['name']); ?> <i class='bx bxs-down-arrow'></i>
            <?php if (!empty($game['sets'])): ?>
              <ul class="header__submenu">
                <?php foreach ($game['sets'] as $set): ?>
                  <li><a href="/views/card_all.php?set_id=<?php echo $set['id']; ?>"><?php echo htmlspecialchars($set['name']); ?></a></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
        <li>More <i class='bx bxs-down-arrow'></i></li>
        <li>Subscribe to TCGplayer</li>
      </ul>
    </div>
  </header>
  <div id="overlay"></div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const menuItems = document.querySelectorAll('.header__list > li');
      const overlay = document.getElementById('overlay');

      menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
          e.stopPropagation();
          const submenu = this.querySelector('.header__submenu');
          if (submenu) {
            // Đóng tất cả các submenu khác và xóa class active
            menuItems.forEach(otherItem => {
              if (otherItem !== this) {
                otherItem.classList.remove('active');
                const otherSubmenu = otherItem.querySelector('.header__submenu');
                if (otherSubmenu) {
                  otherSubmenu.style.display = 'none';
                }
              }
            });

            // Chuyển đổi hiển thị của submenu hiện tại và thêm/xóa class active
            if (submenu.style.display === 'block') {
              submenu.style.display = 'none';
              this.classList.remove('active');
              overlay.style.display = 'none';
            } else {
              submenu.style.display = 'block';
              this.classList.add('active');
              overlay.style.display = 'block';
            }
          }
        });
      });

      // Đóng submenu và xóa class active khi click ra ngoài
      overlay.addEventListener('click', function() {
        menuItems.forEach(item => {
          item.classList.remove('active');
          const submenu = item.querySelector('.header__submenu');
          if (submenu) {
            submenu.style.display = 'none';
          }
        });
        overlay.style.display = 'none';
      });
    });
  </script>
</body>

</html>