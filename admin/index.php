<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: auth/login.php");
  exit();
}
header("Location: layouts/admin_layout.php?page=dashboard");
exit();
