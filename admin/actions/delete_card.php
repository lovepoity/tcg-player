<?php
session_start();
include '../../includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
  http_response_code(403);
  echo json_encode(['error' => 'Unauthorized']);
  exit();
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id) {
  $query = "DELETE FROM cards WHERE id = ?";
  $stmt = $conn->prepare($query);
  $result = $stmt->execute([$id]);

  if ($result) {
    echo json_encode(['success' => true]);
  } else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to delete card']);
  }
} else {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid card ID']);
}
