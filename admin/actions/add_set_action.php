<?php
function handleAddSet($conn)
{
  try {
    $game_id = $_POST['game_id'];
    $name = $_POST['name'];

    // Check if the set name already exists for the selected game
    $check_sql = "SELECT COUNT(*) FROM sets WHERE game_id = :game_id AND name = :name";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->execute([':game_id' => $game_id, ':name' => $name]);
    $set_exists = $check_stmt->fetchColumn();

    if ($set_exists > 0) {
      throw new Exception("A set with this name already exists for the selected game.");
    }

    // Handle image upload
    $image_filename = null;
    if ($_FILES['image_upload']['name']) {
      $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/public/images/sets/";
      $image_filename = basename($_FILES["image_upload"]["name"]);
      $target_file = $target_dir . $image_filename;
      if (!move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
        throw new Exception("Unable to upload file.");
      }
    }

    $sql = "INSERT INTO sets (game_id, name, image) VALUES (:game_id, :name, :image)";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
      ':game_id' => $game_id,
      ':name' => $name,
      ':image' => $image_filename
    ]);

    if (!$result) {
      throw new Exception("Error adding new set.");
    }

    echo json_encode(['success' => true]);
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
  }
}
