<?php
require_once '../../includes/db_connect.php';
require_once '../actions/banner_actions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  handleUpdateBanner($conn);
  exit;
}

$banners = getBanners($conn);
$main_banners = filterBanners($banners, 'main');
$sub_banners = filterBanners($banners, 'sub');
$sub_page_banners = filterBanners($banners, 'sub_page');
?>

<div class="admin-products">
  <div class="content__detail">
    <div class="banner-row">
      <?php echo createBannerTable($main_banners); ?>
    </div>

    <div class="banner-row" style="display: flex; justify-content: space-between;">
      <div style="width: 49%;">
        <?php echo createBannerTable($sub_banners); ?>
      </div>
      <div style="width: 49%;">
        <?php echo createBannerTable($sub_page_banners); ?>
      </div>
    </div>
  </div>
</div>

<?php include 'banner_modal.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/js/common.js"></script>
<script src="/admin/assets/js/banner.js"></script>