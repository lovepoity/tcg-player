<?php
session_start();
include '../includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

if (isset($_GET['id'])) {
  $id = (int)$_GET['id'];

  $query = "DELETE FROM cards WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$id]);

  header("Location: index.php");
  exit();
} else {
  header("Location: index.php");
  exit();
}
