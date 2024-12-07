<?php
session_start();
// Chỉ xóa store session
if (isset($_SESSION['is_store'])) {
  unset($_SESSION['store_id']);
  unset($_SESSION['store_name']);
  unset($_SESSION['is_store']);
}
header('Location: ../auth/login.php');
exit();
