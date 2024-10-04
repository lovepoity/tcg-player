<?php
require_once '../../includes/db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

if (strlen($search) >= 1) {
  $query = "SELECT DISTINCT c.id, c.name, c.image_filename, g.name AS game_name, s.name AS set_name
              FROM cards c
              JOIN sets s ON c.set_id = s.id
              JOIN games g ON s.game_id = g.id
              WHERE c.name LIKE :search
              ORDER BY c.name
              LIMIT 10";

  $stmt = $conn->prepare($query);
  $stmt->execute([':search' => $search . '%']);
  $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($cards);
} else {
  echo json_encode([]);
}
