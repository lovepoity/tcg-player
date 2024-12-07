<?php
session_start();
require_once '../../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['store_id'])) {
  echo json_encode(['success' => false, 'error' => 'Unauthorized']);
  exit();
}

$email = $_POST['store_email'] ?? '';
$phone = $_POST['store_phone'] ?? '';

try {
  $query = "UPDATE stores SET email = ?, phone = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $result = $stmt->execute([$email, $phone, $_SESSION['store_id']]);

  if ($result) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'error' => 'Failed to update store information']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'Database error']);
}
