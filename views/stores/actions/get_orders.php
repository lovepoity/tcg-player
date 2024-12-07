<?php
session_start();
require_once '../../../includes/db_connect.php';

$store_id = $_SESSION['store_id'];
$status = isset($_GET['status']) ? $_GET['status'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT DISTINCT o.*, oi.status as order_status,
          (oi.quantity * cl.price + cl.shipping) as total_amount
          FROM orders o
          JOIN order_items oi ON o.id = oi.order_id
          JOIN card_listings cl ON oi.card_listing_id = cl.id
          WHERE oi.store_id = :store_id";

$params = [':store_id' => $store_id];

if ($status) {
  $query .= " AND oi.status = :status";
  $params[':status'] = $status;
}

if ($start_date) {
  $query .= " AND o.created_at >= :start_date";
  $params[':start_date'] = $start_date . ' 00:00:00';
}

if ($end_date) {
  $query .= " AND o.created_at <= :end_date";
  $params[':end_date'] = $end_date . ' 23:59:59';
}

if ($search) {
  $query .= " AND o.id LIKE :search";
  $params[':search'] = "%$search%";
}

$query .= " GROUP BY o.id ORDER BY o.created_at DESC";

try {
  $stmt = $conn->prepare($query);
  $stmt->execute($params);
  $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

  include '../tabs/order_table.php';
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
}
