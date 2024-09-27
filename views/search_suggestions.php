<?php
include '../includes/db_connect.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';
$suggestions = [];

if (!empty($query)) {
  $stmt = $conn->prepare("SELECT name FROM cards WHERE name LIKE :query LIMIT 5");
  $stmt->bindValue(':query', '%' . $query . '%');
  $stmt->execute();
  $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

header('Content-Type: application/json');
echo json_encode($suggestions);
