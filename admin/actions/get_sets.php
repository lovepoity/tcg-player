<?php
require_once '../../includes/db_connect.php';

$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : null;

if ($game_id) {
  $query = "SELECT * FROM sets WHERE game_id = :game_id ORDER BY id ASC";
  $stmt = $conn->prepare($query);
  $stmt->execute([':game_id' => $game_id]);
  $sets = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo '<option value="">All Sets</option>';
  foreach ($sets as $set) {
    echo '<option value="' . $set['id'] . '">' . $set['id'] . ' - ' . htmlspecialchars($set['name']) . '</option>';
  }
} else {
  echo '<option value="">All Sets</option>';
}
