<?php
function handleAddGame($conn)
{
  try {
    $name = $_POST['name'];

    // Check if the game name already exists
    $check_sql = "SELECT COUNT(*) FROM games WHERE name = :name";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->execute([':name' => $name]);
    $game_exists = $check_stmt->fetchColumn();

    if ($game_exists > 0) {
      throw new Exception("A game with this name already exists.");
    }

    $sql = "INSERT INTO games (name) VALUES (:name)";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([':name' => $name]);

    if (!$result) {
      throw new Exception("Error adding new game.");
    }

    echo json_encode(['success' => true]);
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
  }
}
