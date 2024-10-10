<?php
require_once __DIR__ . '/db_connect.php';

function get_main_banner()
{
  global $conn;
  $stmt = $conn->prepare("SELECT * FROM banners WHERE banner_type = 'main' LIMIT 1");
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_sub_banners()
{
  global $conn;
  $stmt = $conn->prepare("SELECT * FROM banners WHERE banner_type = 'sub' LIMIT 5");
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_sub_page_banner()
{
  global $conn;
  $stmt = $conn->prepare("SELECT * FROM banners WHERE banner_type = 'sub_page' LIMIT 1");
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
