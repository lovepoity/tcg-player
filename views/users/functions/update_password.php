<?php
session_start();
include_once '../../../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'];
  $currentPassword = $_POST['current_password'] ?? '';
  $newPassword = $_POST['password'] ?? '';
  $confirmPassword = $_POST['confirm_password'] ?? '';

  try {
    // Validate password
    if (strlen($newPassword) < 8) {
      echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters long']);
      exit;
    }

    if (!preg_match('/[A-Z]/', $newPassword)) {
      echo json_encode(['success' => false, 'message' => 'Password must contain at least one uppercase letter']);
      exit;
    }

    if (!preg_match('/[0-9]/', $newPassword)) {
      echo json_encode(['success' => false, 'message' => 'Password must contain at least one number']);
      exit;
    }

    if (!preg_match('/[a-z]/', $newPassword)) {
      echo json_encode(['success' => false, 'message' => 'Password must contain at least one lowercase letter']);
      exit;
    }

    // Kiểm tra mật khẩu hiện tại
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra mật khẩu hiện tại có đúng không
    if ($user['password'] !== $currentPassword) {
      echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
      exit;
    }

    // Kiểm tra mật khẩu mới và xác nhận mật khẩu
    if ($newPassword !== $confirmPassword) {
      echo json_encode(['success' => false, 'message' => 'New passwords do not match']);
      exit;
    }

    // Cập nhật mật khẩu mới
    $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :user_id");
    $stmt->bindParam(':password', $newPassword);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
