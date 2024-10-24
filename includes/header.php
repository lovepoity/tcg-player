<?php
// Kết nối đến cơ sở dữ liệu
include __DIR__ . '/db_connect.php';

// Fetch website settings
$sql = "SELECT title, logo, favicon, footer_desc FROM website_info WHERE id = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$website_info = $stmt->fetch(PDO::FETCH_ASSOC);

// Set default values if not found
$logo = $website_info['logo'] ?? 'logo.png';
$favicon = $website_info['favicon'] ?? 'favicon.ico';
$title = $website_info['title'] ?? 'TCG Player';
$footer_desc = $website_info['footer_desc'] ?? '';

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

// Thay đổi truy vấn chính để sắp xếp games theo id và sets theo id giảm dần
$query = "SELECT g.id AS game_id, g.name AS game_name, s.id AS set_id, s.name AS set_name 
          FROM games g 
          LEFT JOIN sets s ON g.id = s.game_id 
          ORDER BY g.id ASC, s.id DESC";
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

// Truy vấn để lấy tất cả các trò chơi
$query_all_games = "SELECT * FROM games ORDER BY id ASC";
$stmt_all_games = $conn->prepare($query_all_games);
$stmt_all_games->execute();
$all_games = $stmt_all_games->fetchAll(PDO::FETCH_ASSOC);

// Thêm truy vấn để lấy set mới nhất cho mỗi game
$query_latest_sets = "SELECT s.id, s.name, s.image, s.game_id 
                      FROM sets s
                      INNER JOIN (
                          SELECT game_id, MAX(id) as max_id
                          FROM sets
                          GROUP BY game_id
                      ) latest ON s.game_id = latest.game_id AND s.id = latest.max_id";
$stmt_latest_sets = $conn->prepare($query_latest_sets);
$stmt_latest_sets->execute();
$latest_sets = $stmt_latest_sets->fetchAll(PDO::FETCH_ASSOC);

// Tạo một mảng với game_id là khóa
$latest_sets_by_game = array();
foreach ($latest_sets as $set) {
  $latest_sets_by_game[$set['game_id']] = $set;
}

// Đảm bảo session đã được bắt đầu
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

function getCartItemCount($conn, $user_id)
{
  if (!$user_id) return 0;

  $stmt = $conn->prepare("SELECT COUNT(*) as total FROM cart WHERE user_id = :user_id");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  return $result['total'] ?? 0;
}

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
  <link rel="icon" href="/public/images/<?php echo htmlspecialchars($favicon); ?>">

  <!-- END FONTAWESOME -->
  <!-- ----------------------------------------------------------------------------- -->
  <!-- CSS -->
  <link rel="stylesheet" href="/public/css/main.css">
  <link rel="stylesheet" href="/public/css/styles.css">
  <link rel="stylesheet" href="/public/css/card_all.css">
  <link rel="stylesheet" href="/public/css/card_detail.css">
  <link rel="stylesheet" href="/public/css/cart.css">
  <!-- END CSS -->
  <!-- ----------------------------------------------------------------------------- -->
  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="/public/js/main.js"></script>
  <script src="/public/js/cart.js"></script>
  <!-- END JS -->
  <title><?php echo htmlspecialchars($title); ?></title>
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
        <a href="/index.php"><img src="/public/images/<?php echo htmlspecialchars($logo); ?>" alt="TCG Player" /></a>
      </div>
      <!-- SEARCH -->
      <div class="header__search">
        <select name="game" id="game-select">
          <option value="">All</option>
          <?php foreach ($all_games as $game): ?>
            <option value="<?php echo htmlspecialchars($game['id']); ?>">
              <?php echo htmlspecialchars($game['name']); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input autocomplete="off" type="text" id="search-input" placeholder="Search your favorite products">
        <i class="fa-solid fa-magnifying-glass"></i>
        <div id="search-results" class="search-results"></div>
      </div>
      <!-- END SEARCH -->
      <!-- ACTION -->
      <div class="header__action">
        <?php if (isset($_SESSION['user_email'])): ?>
          <span class="header__welcome">Welcome Back, <p><?php echo htmlspecialchars($_SESSION['user_email']); ?></p></span>
          <div class="header__user">
            <i class="fa-regular fa-user"></i>
            <div class="header__user-menu">
              <span class="header__welcome user__menu__welcome">Welcome Back, <p><?php echo htmlspecialchars($_SESSION['user_email']); ?></p></span>
              <div class="user__menu__list">
                <div class="user__menu__left">
                  <h4><a href="#">Your Account</a></h4>
                  <ul>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Order History</a></li>
                    <li><a href="#">Account & Data</a></li>
                    <li><a href="#">Messages</a></li>
                    <li><a href="#">Your Collection</a></li>
                    <li><a href="#">Manage Payment Methods</a></li>
                    <li><a href="#">TCGplayer Subscription</a></li>
                    <li><a href="#">Manage Addresses</a></li>
                    <li><a href="#">Store Credit</a></li>
                    <li><a href="#">Email Preferences</a></li>
                  </ul>
                </div>
                <div class="user__menu__right">
                  <h4><a href="#">Sell</a></h4>
                  <ul>
                    <li><a href="#">Account</a></li>
                    <li><a href="#">Seller Portal</a></li>
                    <li><a href="#">Marketplace Seller Resources</a></li>
                    <li><a href="#">Pro Seller Resources</a></li>
                  </ul>
                  <h4><a href="#">Help</a></h4>
                  <ul>
                    <li><a href="#">Contact Customer Support</a></li>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Refund and Return Policy</a></li>
                    <li><a href="#">TCGplayer Safeguard</a></li>
                  </ul>
                  <h4><a href="#">Gift Cards</a></h4>
                  <ul>
                    <li><a href="#">Buy a Gift Card</a></li>
                    <li><a href="#">Redeem a Gift Card</a></li>
                  </ul>
                  <a style="font-weight: normal;" href="/views/login/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</a>
                </div>
              </div>
            </div>
          </div>
        <?php else: ?>
          <a class="header__signin" href="/views/login/sign_in.php">Sign In</a>
          <div class="header__user">
            <i class="fa-regular fa-user"></i>
          </div>
        <?php endif; ?>
        <div class="header__sell">Sell With Us</div>
        <div class="header__cart">
          <a href="/views/cart/cart.php"><i class="fa-solid fa-cart-shopping"></i>
            <p id="cart-count"><?php echo getCartItemCount($conn, $_SESSION['user_id'] ?? null); ?></p>
          </a>
        </div>
      </div>
      <!-- END ACTION -->
    </div>
    <!-- NAVBAR -->
    <div class="header__navbar">
      <ul class="header__list">
        <?php
        $game_count = 0;
        $more_games = [];
        foreach ($games as $game_id => $game):
          if ($game_count < 7): // Thay đổi từ 7 thành 8
            $menu_class = ($game_count >= 4 && $game_count < 7) ? 'custom-position' : '';
        ?>
            <li class="<?php echo $menu_class; ?>">
              <?php echo htmlspecialchars($game['name']); ?> <i class='bx bxs-down-arrow'></i>
              <?php if (!empty($game['sets'])): ?>
                <ul class="header__submenu">
                  <div class="header__sub__title">
                    <h3><?php echo htmlspecialchars($game['name']); ?></h3>
                    <h5><a href="/views/card_all.php?game_id=<?php echo $game_id; ?>">Shop All</a></h5>
                  </div>
                  <div class="header__sub__cnt">
                    <div class="header__sub__cnt__left">
                      <h4>All Sets</h4>
                      <?php foreach ($game['sets'] as $set): ?>
                        <li><a href="/views/card_all.php?set_id=<?php echo $set['id']; ?>">
                            <?php echo htmlspecialchars($set['name']); ?></a></li>
                      <?php endforeach; ?>
                    </div>
                    <div class="header__sub__cnt__mid">
                      <h4><a href="#">More</a></h4>
                      <li><a href="#">Articles</a></li>
                      <li><a href="#">Mass Entry</a></li>
                      <li><a href="#">Gift Cards</a></li>
                      <li><a href="#">Help</a></li>
                    </div>
                    <div class="header__sub__cnt__right">
                      <div class="header__sub__cnt__right--top">
                        <div class="header__sub__cnt__right--img">
                          <?php if ($latest_set = $latest_sets_by_game[$game_id] ?? null && $latest_set['image']): ?>
                            <img src="/public/images/sets/<?php echo htmlspecialchars($latest_set['image']); ?>" alt="<?php echo htmlspecialchars($latest_set['name']); ?>">
                          <?php else: ?>
                            <img src="/public/images/placeholder.jpg" alt="No image available">
                          <?php endif; ?>
                        </div>
                        <h4><?php echo $latest_set ? htmlspecialchars($latest_set['name']) : 'No set available'; ?></h4>
                        <button>Order Now</button>
                      </div>
                      <div class="header__sub__cnt__right--bottom">
                        <button class="header__sub__cnt__right--bottom--price"><i class="fa-solid fa-coins"></i> Price Guide</button>
                        <button class="header__sub__cnt__right--bottom--search"><i class="fa-solid fa-search"></i> Advanced Search</button>
                      </div>
                    </div>
                  </div>
                </ul>
              <?php endif; ?>
            </li>
        <?php
          else:
            $more_games[$game_id] = $game;
          endif;
          $game_count++;
        endforeach;
        ?>
        <li>
          More <i class='bx bxs-down-arrow'></i>
          <?php if (!empty($more_games)): ?>
            <ul class="header__submenu">
              <?php foreach ($more_games as $game_id => $game): ?>
                <li>
                  <?php echo htmlspecialchars($game['name']); ?>
                  <?php if (!empty($game['sets'])): ?>
                    <ul class="header__submenu">
                      <?php foreach ($game['sets'] as $set): ?>
                        <li><a href="/views/card_all.php?set_id=<?php echo $set['id']; ?>"><?php echo htmlspecialchars($set['name']); ?></a></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </li>
        <li>Subscribe to TCGplayer</li>
      </ul>
    </div>
  </header>
  <div id="overlay"></div>
  <script src="/public/js/main.js"></script>
</body>

</html>