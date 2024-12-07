<?php
session_start();
// Chỉ xóa admin session
if (isset($_SESSION['is_admin'])) {
  unset($_SESSION['admin_id']);
  unset($_SESSION['is_admin']);
}
header("Location: login.php");
exit();
