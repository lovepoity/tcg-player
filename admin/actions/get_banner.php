<?php
require_once '../../includes/db_connect.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $conn->prepare("SELECT * FROM banners WHERE id = :id");
  $stmt->execute([':id' => $id]);
  $banner = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($banner) {
    echo json_encode($banner);
  } else {
    echo json_encode(['error' => 'Banner not found']);
  }
} else {
  echo json_encode(['error' => 'No ID provided']);
}
