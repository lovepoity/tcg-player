<?php

use function PHPSTORM_META\map;

$host = 'localhost';
$dbname = 'tcg_database';
$username = 'root';
$password = '';
// $username = 'tcg';
// $password = 'Huy221997.';

try {
  $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
  exit;
}
