<?php
require_once '../../../includes/db_connect.php';

$game_id = isset($_GET['game_id']) ? $_GET['game_id'] : null;

if ($game_id) {
  $query = "SELECT id, name FROM sets WHERE game_id = ? ORDER BY release_date DESC";
  $stmt = $conn->prepare($query);
  $stmt->execute([$game_id]);
  $sets = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($sets as $set) {
    echo "<option value='" . $set['id'] . "'>" . $set['id'] . " - " . $set['name'] . "</option>";
  }
}
