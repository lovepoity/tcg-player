<?php
session_start();
include_once '../../../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'User not logged in']);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $userId = $_SESSION['user_id'];

  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';
  $phone = $_POST['phone'] ?? '';
  $address = $_POST['address'] ?? '';
  $city = $_POST['city'] ?? '';
  $state = $_POST['state'] ?? '';
  $postal_code = $_POST['postal_code'] ?? '';
  $country = $_POST['country'] ?? '';

  try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $userId);
    $stmt->execute();
    $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
      $currentUser['first_name'] === $first_name &&
      $currentUser['last_name'] === $last_name &&
      $currentUser['phone'] === $phone &&
      $currentUser['address'] === $address &&
      $currentUser['city'] === $city &&
      $currentUser['state'] === $state &&
      $currentUser['postal_code'] === $postal_code &&
      $currentUser['country'] === $country
    ) {

      echo json_encode(['success' => true, 'message' => 'No changes detected.']);
      exit;
    }

    $sql = "UPDATE users SET 
                    first_name = :first_name,
                    last_name = :last_name,
                    phone = :phone,
                    address = :address,
                    city = :city,
                    state = :state,
                    postal_code = :postal_code,
                    country = :country,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE id = :user_id";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':postal_code', $postal_code);
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':user_id', $userId);

    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Information updated successfully']);
  } catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
