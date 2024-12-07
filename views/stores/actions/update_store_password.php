<?php
session_start();
require_once '../../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['store_id'])) {
  echo json_encode(['success' => false, 'error' => 'Unauthorized']);
  exit();
}

$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';

try {
  // Verify current password
  $query = "SELECT password FROM stores WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$_SESSION['store_id']]);
  $store = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$store || !password_verify($current_password, $store['password'])) {
    echo json_encode(['success' => false, 'error' => 'Current password is incorrect']);
    exit();
  }

  // Update password
  $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
  $query = "UPDATE stores SET password = ? WHERE id = ?";
  $stmt = $conn->prepare($query);
  $result = $stmt->execute([$hashed_password, $_SESSION['store_id']]);

  if ($result) {
    echo json_encode(['success' => true]);
  } else {
    echo json_encode(['success' => false, 'error' => 'Failed to update password']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'Database error']);
}
