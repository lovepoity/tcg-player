<?php
session_start();
require_once '../../../includes/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['store_id'])) {
  echo json_encode(['success' => false, 'error' => 'Unauthorized']);
  exit();
}

try {
  $query = "SELECT name, email, phone FROM stores WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$_SESSION['store_id']]);
  $store = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($store) {
    echo json_encode(['success' => true, 'store' => $store]);
  } else {
    echo json_encode(['success' => false, 'error' => 'Store not found']);
  }
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'error' => 'Database error']);
}
