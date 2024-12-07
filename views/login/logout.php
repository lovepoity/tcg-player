<?php
session_start();
// Chỉ xóa user session
if (isset($_SESSION['is_user'])) {
  unset($_SESSION['user_id']);
  unset($_SESSION['user_email']);
  unset($_SESSION['is_user']);
}
header('Location: sign_in.php');
exit();
