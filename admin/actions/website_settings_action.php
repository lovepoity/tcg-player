<?php
require_once '../../includes/db_connect.php';

// File upload handling function
function uploadFile($file, $target_dir)
{
  $target_file = $target_dir . basename($file["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

  // Check file size
  if ($file["size"] > 500000) {
    return false;
  }

  // Allow certain file formats
  if (
    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "ico"
  ) {
    return false;
  }

  if (move_uploaded_file($file["tmp_name"], $target_file)) {
    return basename($file["name"]);
  } else {
    return false;
  }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json');
  ob_start();

  try {
    $update_type = $_POST['update_type'];
    $sql = "UPDATE website_info SET ";
    $params = [];

    switch ($update_type) {
      case 'head_title':
        if (isset($_POST['head_title'])) {
          $title = trim($_POST['head_title']);
          if (empty($title)) {
            throw new Exception("Head Title cannot be empty.");
          }
          $sql .= "title = :title";
          $params[':title'] = $title;
        } else {
          throw new Exception("Missing head title.");
        }
        break;
      case 'logo':
        if (!empty($_FILES['logo']['name'])) {
          $logo = uploadFile($_FILES['logo'], '../../public/images/');
          if (!$logo) {
            throw new Exception("Error uploading logo.");
          }
          $sql .= "logo = :logo";
          $params[':logo'] = $logo;
        } else {
          throw new Exception("No logo file uploaded.");
        }
        break;
      case 'favicon':
        if (!empty($_FILES['favicon']['name'])) {
          $favicon = uploadFile($_FILES['favicon'], '../../public/images/');
          if (!$favicon) {
            throw new Exception("Error uploading favicon.");
          }
          $sql .= "favicon = :favicon";
          $params[':favicon'] = $favicon;
        } else {
          throw new Exception("No favicon file uploaded.");
        }
        break;
      case 'footer_desc':
        if (isset($_POST['footer_desc'])) {
          $footer_desc = trim($_POST['footer_desc']);
          $sql .= "footer_desc = :footer_desc";
          $params[':footer_desc'] = $footer_desc;
        } else {
          throw new Exception("Missing footer description.");
        }
        break;
      default:
        throw new Exception("Invalid update type.");
    }

    $sql .= " WHERE id = 1";

    $stmt = $conn->prepare($sql);
    foreach ($params as $key => &$val) {
      $stmt->bindParam($key, $val);
    }

    if (!$stmt->execute()) {
      throw new Exception("An error occurred while updating " . $update_type);
    }

    echo json_encode(['success' => true, 'message' => "Title has been updated successfully"]);
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
  }

  ob_end_flush();
  exit;
}

// Get current information
function getWebsiteInfo($conn)
{
  $sql = "SELECT * FROM website_info WHERE id = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
