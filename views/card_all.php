<?php
include '../includes/header.php';
include '../includes/banners.php';
include '../includes/db_connect.php';
include '../includes/card_queries.php';

$subPageBanner = get_sub_page_banner();

// Lấy game_id và set_id từ URL
$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : null;
$set_id = isset($_GET['set_id']) ? intval($_GET['set_id']) : null;

// Thiết lập phân trang
$items_per_page = 24;
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($current_page - 1) * $items_per_page;

$total_items = get_total_cards($conn, $game_id, $set_id);
$total_pages = ceil($total_items / $items_per_page);

$cards = get_cards_for_page($conn, $game_id, $set_id, $offset, $items_per_page);
$info = get_game_set_info($conn, $game_id, $set_id);

$game_name = $info['game_name'];
$set_name = isset($info['set_name']) ? $info['set_name'] : null;
$game_id = $info['game_id'];
?>

<div class="sub__page">
  <div class="navbar__sub-page">
    <!-- FILTER -->
    <div class="filter">
      <div style="display: flex; align-items: center; background-color: #F7F7F8;" class="filter__item">
        All Filters <span>2</span>
      </div>
      <div class="filter__item filter__style">
        <?php echo htmlspecialchars($game_name); ?> <i class="fa-solid fa-xmark"></i>
      </div>
      <?php if ($set_name): ?>
        <div class="filter__item filter__style">
          <?php echo htmlspecialchars($set_name); ?> <i class="fa-solid fa-angle-down"></i>
        </div>
      <?php endif; ?>
      <div class="filter__item">
        Product Type <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item">
        Card Type <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item">
        Color <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item">
        Condition <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item">
        Printing <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item">
        Rarity <i class="fa-solid fa-angle-down"></i>
      </div>
      <div class="filter__item">
        Clear Filter
      </div>
    </div>
    <!-- END FILTER -->
    <p class="sort__view">Sort & View</p>
    <select name="sort" id="sort">
      <option value="best-match">Best Match</option>
      <option value="newest">Newest</option>
      <option value="oldest">Oldest</option>
      <option value="price-asc">Price: Low to High</option>
      <option value="price-desc">Price: High to Low</option>
    </select>
    <svg data-v-7c2d0612="" class="svg-inline--fa fa-grid-2 active" aria-hidden="true" focusable="false" data-prefix="far" data-icon="grid-2" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" alt="A grid of results">
      <path class="" fill="currentColor" d="M0 80C0 53.49 21.49 32 48 32H144C170.5 32 192 53.49 192 80V176C192 202.5 170.5 224 144 224H48C21.49 224 0 202.5 0 176V80zM48 176H144V80H48V176zM0 336C0 309.5 21.49 288 48 288H144C170.5 288 192 309.5 192 336V432C192 458.5 170.5 480 144 480H48C21.49 480 0 458.5 0 432V336zM48 432H144V336H48V432zM400 32C426.5 32 448 53.49 448 80V176C448 202.5 426.5 224 400 224H304C277.5 224 256 202.5 256 176V80C256 53.49 277.5 32 304 32H400zM400 80H304V176H400V80zM256 336C256 309.5 277.5 288 304 288H400C426.5 288 448 309.5 448 336V432C448 458.5 426.5 480 400 480H304C277.5 480 256 458.5 256 432V336zM304 432H400V336H304V432z"></path>
    </svg>
    <i class='bx bx-menu-alt-right'></i>
  </div>
  <div class="sub__location">
    <a href="#">All Categories</a>
    <i class="fa-solid fa-chevron-right"></i>
    <a href="/views/card_all.php?game_id=<?php echo $game_id; ?>"><?php echo htmlspecialchars($game_name); ?></a>
    <?php if ($set_name): ?>
      <i class="fa-solid fa-chevron-right"></i>
      <p><?php echo htmlspecialchars($set_name); ?></p>
    <?php endif; ?>
  </div>
  <!-- BANNER -->
  <?php if ($subPageBanner): ?>
    <!-- BANNER -->
    <div class="sub__banner sub__banner--1">
      <a href="<?php echo htmlspecialchars($subPageBanner['url']); ?>">
        <img src="<?php echo htmlspecialchars('/public/images/banner/' . $subPageBanner['banner_img']); ?>" alt="<?php echo htmlspecialchars($subPageBanner['title']); ?>">
      </a>
    </div>
    <!-- END BANNER -->
  <?php endif; ?>
  <!-- END BANNER -->
  <!-- PRODUCT -->
  <div class="product-grid">
    <?php foreach ($cards as $card): ?>
      <div class="product">
        <a href="/views/card_details.php?id=<?php echo $card['id']; ?>">
          <div class="product__img">
            <img loading="lazy" src="/public/images/product/<?php echo htmlspecialchars($card['image_filename']); ?>" alt="<?php echo htmlspecialchars($card['name']); ?>">
          </div>
          <div class="product__info">
            <p class="product__name"><?php echo htmlspecialchars($card['name']); ?></p>
            <p class="product__set"><?php echo htmlspecialchars($card['set_name']); ?></p>
            <p class="product__rarity"><?php echo htmlspecialchars($card['rarity']); ?></p>
            <p class="product__card-number"><?php echo htmlspecialchars($card['card_number']); ?></p>
            <?php if ($card['total_stores'] > 0): ?>
              <p class="product__listing"><?php echo $card['total_stores']; ?> listings from</p>
              <p class="product__price">
                $<?php echo number_format($card['min_price'], 2); ?>
              </p>
              <p class="product__market-price">Market Price: <span style="color: var(--green-color);">$<?php echo number_format($card['avg_price'], 2); ?></span></p>
            <?php else: ?>
              <p class="product__listing">Out of Stock</p>
            <?php endif; ?>
          </div>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
  <!-- END PRODUCT -->

  <!-- PAGINATION -->
  <?php if ($total_pages > 1): ?>
    <div class="pagination">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="?<?php echo $set_id ? 'set_id=' . $set_id : 'game_id=' . $game_id; ?>&page=<?php echo $i; ?>" <?php echo ($i == $current_page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
      <?php endfor; ?>
    </div>
  <?php endif; ?>
  <!-- END PAGINATION -->

  <!-- BANNER -->
  <?php if ($subPageBanner): ?>
    <!-- BANNER -->
    <div class="sub__banner sub__banner--2">
      <a href="<?php echo htmlspecialchars($subPageBanner['url']); ?>">
        <img src="<?php echo htmlspecialchars('/public/images/banner/' . $subPageBanner['banner_img']); ?>" alt="<?php echo htmlspecialchars($subPageBanner['title']); ?>">
      </a>
    </div>
    <!-- END BANNER -->
  <?php endif; ?>
</div>
<!-- Thêm vào cuối file, trước </body> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('.pagination a').on('click', function(e) {
      e.preventDefault();
      var url = $(this).attr('href');

      $.ajax({
        url: '/views/load_cards.php' + url.substring(url.indexOf('?')),
        type: 'GET',
        success: function(data) {
          $('.product-grid').html(data);

          // Cập nhật URL mà không tải lại trang
          history.pushState(null, '', url);

          // Cập nhật trạng thái active của nút phân trang
          $('.pagination a').removeClass('active');
          $('.pagination a[href="' + url + '"]').addClass('active');

          // Cuộn lên đầu danh sách sản phẩm
          $('html, body').animate({
            scrollTop: $(".product-grid").offset().top
          }, 500);
        }
      });
    });
  });
</script>
<?php
include '../includes/footer.php';
?>