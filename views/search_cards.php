<?php
require_once '../includes/db_connect.php';

header('Content-Type: application/json');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : 0;

if (empty($search)) {
  echo json_encode([]);
  exit;
}

$query = "SELECT c.id, c.name, c.image_filename, g.name AS game_name, s.name AS set_name
          FROM cards c
          JOIN sets s ON c.set_id = s.id
          JOIN games g ON s.game_id = g.id
          WHERE c.name LIKE :search";

$params = [':search' => "$search%"]; // Thay đổi ở đây

if ($game_id > 0) {
  $query .= " AND g.id = :game_id";
  $params[':game_id'] = $game_id;
}

$query .= " ORDER BY c.name ASC LIMIT 15";

try {
  $stmt = $conn->prepare($query);
  $stmt->execute($params);
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($results);
} catch (PDOException $e) {
  error_log($e->getMessage());
  echo json_encode(['error' => 'An error occurred while searching']);
}
