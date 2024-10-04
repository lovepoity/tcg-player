<?php
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

session_start();
if (!isset($_SESSION['store_id'])) {
  json_response(['error' => 'Unauthorized'], 401);
}

if (!isset($_GET['set_id']) || !is_numeric($_GET['set_id'])) {
  json_response(['error' => 'Invalid set ID']);
}

$set_id = intval($_GET['set_id']);

try {
  $cards_query = "SELECT id, name, image_filename FROM cards WHERE set_id = ?";
  $cards_stmt = $conn->prepare($cards_query);
  $cards_stmt->execute([$set_id]);
  $cards = $cards_stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($cards)) {
    json_response(['error' => 'No cards found for this set']);
  }

  json_response($cards);
} catch (PDOException $e) {
  error_log($e->getMessage());
  json_response(['error' => 'An error occurred. Please try again later.'], 500);
}
