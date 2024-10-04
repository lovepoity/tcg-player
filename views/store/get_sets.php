<?php
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

session_start();
if (!isset($_SESSION['store_id'])) {
  json_response(['error' => 'Unauthorized'], 401);
}

if (!isset($_GET['game_id']) || !is_numeric($_GET['game_id'])) {
  json_response(['error' => 'Invalid game ID']);
}

$game_id = intval($_GET['game_id']);

try {
  $sets_query = "SELECT id, name FROM sets WHERE game_id = ?";
  $sets_stmt = $conn->prepare($sets_query);
  $sets_stmt->execute([$game_id]);
  $sets = $sets_stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($sets)) {
    json_response(['error' => 'No sets found for this game']);
  }

  json_response($sets);
} catch (PDOException $e) {
  error_log($e->getMessage());
  json_response(['error' => 'An error occurred. Please try again later.'], 500);
}
