<?php
session_start();
include_once '../../../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'];
  $newEmail = $_POST['email'] ?? '';
  $confirmEmail = $_POST['confirm_email'] ?? '';
  $password = $_POST['password'] ?? '';

  try {
    // Validate password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user['password'] !== $password) {
      echo json_encode(['success' => false, 'message' => 'Incorrect password']);
      exit;
    }

    // Kiểm tra email mới có giống email hiện tại không
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($currentUser['email'] === $newEmail) {
      echo json_encode(['success' => false, 'message' => 'New email must be different from current email.']);
      exit;
    }

    // Kiểm tra email mới và email xác nhận có khớp không
    if ($newEmail !== $confirmEmail) {
      echo json_encode(['success' => false, 'message' => 'Email confirmation does not match.']);
      exit;
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email AND id != :user_id");
    $stmt->bindParam(':email', $newEmail);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    if ($stmt->fetch()) {
      echo json_encode(['success' => false, 'message' => 'Email already in use']);
      exit;
    }

    // Update email
    $stmt = $conn->prepare("UPDATE users SET email = :email WHERE id = :user_id");
    $stmt->bindParam(':email', $newEmail);
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();

    // Cập nhật session với email mới
    $_SESSION['user_email'] = $newEmail;

    echo json_encode(['success' => true, 'message' => 'Email updated successfully']);
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
