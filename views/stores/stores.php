<?php
session_start();
if (!isset($_SESSION['store_id'])) {
  header("Location: auth/login.php");
  exit();
}
header("Location: tabs/store_layout.php?page=store_dashboard");
exit();
