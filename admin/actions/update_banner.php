<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  try {
    // Log dữ liệu nhận được
    error_log("Received POST data: " . print_r($_POST, true));
    error_log("Received FILES data: " . print_r($_FILES, true));

    $id = $_POST['id'];
    $banner_type = $_POST['banner_type'];
    $url = $_POST['url'];

    // Xử lý upload ảnh nếu có
    if ($_FILES['banner_img']['name']) {
      $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/public/images/banner/";
      $banner_img = basename($_FILES["banner_img"]["name"]);
      $target_file = $target_dir . $banner_img;
      if (!move_uploaded_file($_FILES["banner_img"]["tmp_name"], $target_file)) {
        throw new Exception("Không thể upload file.");
      }
    } else {
      $banner_img = $_POST['current_banner_img'];
    }

    if ($banner_type === 'main') {
      $title = $_POST['title'];
      $subtitle = $_POST['subtitle'];
      $release_date = $_POST['release_date'];

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
    } else {
      $sql = "UPDATE banners SET banner_img=:banner_img, url=:url WHERE id=:id";
      $stmt = $conn->prepare($sql);
      $result = $stmt->execute([
        ':banner_img' => $banner_img,
        ':url' => $url,
        ':id' => $id
      ]);
    }

    if ($result) {
      echo json_encode([
        'success' => true,
        'banner_img' => $banner_img,
        'banner_type' => $banner_type,
        'url' => $url,
        'title' => $title ?? null,
        'subtitle' => $subtitle ?? null,
        'release_date' => $release_date ?? null
      ]);
    } else {
      echo json_encode(['success' => false, 'error' => 'Không thể cập nhật banner.']);
    }
  } catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
  }
  exit;
}
