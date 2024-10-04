<?php
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

session_start();
if (!isset($_SESSION['store_id'])) {
  json_response(['error' => 'Unauthorized'], 401);
}

if (!isset($_GET['card_id']) || !is_numeric($_GET['card_id'])) {
  json_response(['error' => 'Invalid card ID']);
}

$card_id = intval($_GET['card_id']);

try {
  $query = "SELECT image_filename FROM cards WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$card_id]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($result) {
    json_response($result);
  } else {
    json_response(['error' => 'Image not found']);
  }
} catch (PDOException $e) {
  error_log($e->getMessage());
  json_response(['error' => 'An error occurred. Please try again later.'], 500);
}
