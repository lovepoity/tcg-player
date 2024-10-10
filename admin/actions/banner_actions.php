<?php
function handleUpdateBanner($conn)
{
  try {
    $id = $_POST['id'];
    $banner_type = $_POST['banner_type'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $release_date = $_POST['release_date'];
    $url = $_POST['url'];

    $banner_img = handleImageUpload($_FILES['banner_img'], $_POST['current_banner_img']);

    $sql = "UPDATE banners SET banner_img=:banner_img, title=:title, subtitle=:subtitle, release_date=:release_date, url=:url WHERE id=:id";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
      ':banner_img' => $banner_img,
      ':title' => $title,
      ':subtitle' => $subtitle,
      ':release_date' => $release_date,
      ':url' => $url,
      ':id' => $id
    ]);

    if (!$result) {
      throw new Exception("Error updating banner.");
    }

    echo json_encode(['success' => true, 'banner_img' => $banner_img, 'url' => $url, 'title' => $title, 'subtitle' => $subtitle, 'release_date' => $release_date, 'banner_type' => $banner_type]);
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
  }
}

function getBanners($conn)
{
  $sql = "SELECT * FROM banners ORDER BY banner_type";
  $stmt = $conn->query($sql);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function filterBanners($banners, $type)
{
  return array_values(array_filter($banners, function ($banner) use ($type) {
    return $banner['banner_type'] == $type;
  }));
}

function handleImageUpload($file, $current_img)
{
  if ($file['name']) {
    $target_dir = "../../public/images/banner/";
    $banner_img = basename($file["name"]);
    $target_file = $target_dir . $banner_img;
    if (!move_uploaded_file($file["tmp_name"], $target_file)) {
      throw new Exception("Unable to upload file.");
    }
    return $banner_img;
  }
  return $current_img;
}

function createBannerTable($banners)
{
  if (empty($banners)) {
    return "<p>No banners.</p>";
  }

  $isMainBanner = reset($banners)['banner_type'] == 'main';
  ob_start();
  include __DIR__ . '/../components/banner_table.php';
  return ob_get_clean();
}
