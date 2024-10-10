<?php
require_once '../../includes/db_connect.php';
require_once '../actions/website_settings_action.php';

$website_info = getWebsiteInfo($conn);
?>

<h1 class="title">Website Settings</h1>
<div class="content__detail">
  <nav class="settings-nav">
    <ul>
      <li><a href="#" data-target="logo-form" class="active">Logo</a></li>
      <li><a href="#" data-target="favicon-form">Favicon</a></li>
      <li><a href="#" data-target="title-form">Title</a></li>
      <li><a href="#" data-target="banner-form">Banner</a></li>
      <li><a style="border-right: none;" href="#" data-target="desc-form">Footer Description</a></li>
    </ul>
  </nav>

  <div id="logo-form" class="setting-form active">
    <h2>Existing Logo</h2>
    <form enctype="multipart/form-data">
      <div class="current-image">
        <?php if ($website_info['logo']): ?>
          <img src="../../public/images/<?php echo htmlspecialchars($website_info['logo']); ?>" alt="Current Logo" style="max-width: 200px;">
        <?php else: ?>
          <p>No logo uploaded</p>
        <?php endif; ?>
      </div>
      <input type="file" name="logo" id="logo" accept="image/*">
      <button type="button" class="btn btn-primary update-btn" data-type="logo">Update Logo</button>
    </form>
  </div>

  <div id="favicon-form" class="setting-form">
    <h2>Existing Favicon</h2>
    <form enctype="multipart/form-data">
      <div class="current-image">
        <?php if ($website_info['favicon']): ?>
          <img src="../../public/images/<?php echo htmlspecialchars($website_info['favicon']); ?>" alt="Current Favicon" style="max-width: 64px;">
        <?php else: ?>
          <p>No favicon uploaded</p>
        <?php endif; ?>
      </div>
      <input type="file" name="favicon" id="favicon" accept="image/*">
      <button type="button" class="btn btn-primary update-btn" data-type="favicon">Update Favicon</button>
    </form>
  </div>

  <div id="title-form" class="setting-form">
    <h2>Existing Head Title</h2>
    <form>
      <input type="text" name="head_title" id="head_title" value="<?php echo htmlspecialchars($website_info['title'] ?? ''); ?>">
      <button type="button" class="btn btn-primary update-btn" data-type="head_title">Update Title</button>
    </form>
  </div>

  <div id="desc-form" class="setting-form">
    <h2>Existing Footer Description</h2>
    <form>
      <textarea name="footer_desc" id="footer_desc" rows="4"><?php echo htmlspecialchars($website_info['footer_desc']); ?></textarea>
      <button type="button" class="btn btn-primary update-btn" data-type="footer_desc">Update Footer Description</button>
    </form>
  </div>

  <div id="banner-form" class="setting-form">
    <h2>Existing Banner</h2>
    <div id="banner-content"></div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/admin/assets/js/common.js"></script>
<script>
  $(document).ready(function() {
    initializeSettingsPage();
  });

  function initializeSettingsPage() {
    $('.setting-form').not('#logo-form').hide();

    $('.settings-nav a').click(function(e) {
      e.preventDefault();
      var targetForm = $(this).data('target');
      $('.setting-form').hide();
      $('#' + targetForm).show();

      $('.settings-nav a').removeClass('active');
      $(this).addClass('active');

      if (targetForm === 'banner-form') {
        loadBannerContent();
      }
    });

    $(".update-btn").on("click", function() {
      var type = $(this).data('type');
      var formData = new FormData();

      if (type === 'logo' || type === 'favicon') {
        var file = $('#' + type)[0].files[0];
        if (!file) {
          showToast("Please select a file for " + type, "error");
          return;
        }
        formData.append(type, file);
      } else if (type === 'head_title') {
        var headTitle = $('#head_title').val().trim();
        if (!headTitle) {
          showToast("Head Title cannot be empty", "error");
          return;
        }
        formData.append('head_title', headTitle);
      } else if (type === 'footer_desc') {
        var footerDesc = $('#footer_desc').val().trim();
        formData.append('footer_desc', footerDesc);
      }

      formData.append('update_type', type);

      handleAjax('/admin/pages/website_settings.php', 'POST', formData, function(response) {
        if (type === 'logo' || type === 'favicon') {
          location.reload();
        }
      });
    });

    handleImagePreview('#logo', '.current-image');
    handleImagePreview('#favicon', '.current-image');
  }

  function loadBannerContent() {
    $.ajax({
      url: '/admin/pages/banners.php',
      method: 'GET',
      success: function(response) {
        $('#banner-content').html(response);
      },
      error: function() {
        showToast("Error loading banner content", "error");
      }
    });
  }
</script>