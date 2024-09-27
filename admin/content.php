<?php
session_start();
include '../includes/db_connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit();
}

// Xác định trang cần tải
$page = isset($_GET['page']) ? $_GET['page'] : 'index.php';

// Kiểm tra xem file có tồn tại không
if (file_exists($page)) {
  include $page;
} else {
  echo "Page not found.";
}
