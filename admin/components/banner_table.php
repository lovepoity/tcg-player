<table class="banner-table">
  <thead class="thead-dark">
    <tr>
      <th>ID</th>
      <th>Type</th>
      <th>Image</th>
      <?php if ($isMainBanner): ?>
        <th>Title</th>
        <th>Subtitle</th>
        <th>Release Date</th>
      <?php endif; ?>
      <th>URL</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($banners as $banner): ?>
      <tr data-id="<?php echo $banner['id']; ?>">
        <td><?php echo $banner['id']; ?></td>
        <td><?php echo $banner['banner_type']; ?></td>
        <td><img class="banner-img" src="/public/images/banner/<?php echo $banner['banner_img']; ?>"></td>
        <?php if ($isMainBanner): ?>
          <td class="banner-title"><?php echo $banner['title']; ?></td>
          <td class="banner-subtitle"><?php echo $banner['subtitle']; ?></td>
          <td class="banner-release-date"><?php echo $banner['release_date']; ?></td>
        <?php endif; ?>
        <td class="banner-url"><?php echo $banner['url']; ?></td>
        <td>
          <button class="btn btn-primary edit-banner" data-id="<?php echo $banner['id']; ?>">Edit</button>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>