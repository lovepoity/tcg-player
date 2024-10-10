<?php
require_once '../../includes/db_connect.php';


$game_query = "SELECT * FROM games ORDER BY id ASC";
$game_stmt = $conn->query($game_query);
$all_games = $game_stmt->fetchAll(PDO::FETCH_ASSOC);

$store_query = "SELECT * FROM stores ORDER BY name";
$store_stmt = $conn->query($store_query);
$all_stores = $store_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="admin-products">
  <h1 class="title">Store List</h1>
  <div class="content__detail">
  </div>
</div>