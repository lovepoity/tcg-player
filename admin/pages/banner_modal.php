<div id="editBannerModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close">&times;</span>
    <h2>Edit Banner</h2>
    <form id="editBannerForm" method="post" enctype="multipart/form-data">
      <input type="hidden" id="banner_id" name="id">
      <input type="hidden" id="banner_type" name="banner_type">
      <input type="hidden" id="current_banner_img" name="current_banner_img">

      <div class="form-group">
        <label for="banner_img">Image:</label>
        <input type="file" id="banner_img" name="banner_img" accept="image/*">
      </div>

      <div class="form-group main-banner-field">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title">
      </div>

      <div class="form-group main-banner-field">
        <label for="subtitle">Subtitle:</label>
        <input type="text" id="subtitle" name="subtitle">
      </div>

      <div class="form-group main-banner-field">
        <label for="release_date">Release Date:</label>
        <input type="text" id="release_date" name="release_date">
      </div>

      <div class="form-group">
        <label for="url">URL:</label>
        <input type="text" id="url" name="url">
      </div>

      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>
</div>