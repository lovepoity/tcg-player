<?php
require_once '../../includes/db_connect.php';

$game_id = isset($_GET['game_id']) ? intval($_GET['game_id']) : null;
$set_id = isset($_GET['set_id']) ? intval($_GET['set_id']) : null;
$card_id = isset($_GET['card_id']) ? intval($_GET['card_id']) : null;

$query = "SELECT DISTINCT c.id, c.name, c.image_filename, c.rarity, c.card_number, 
                 c.color, c.card_type, c.cost, c.power, c.subtype, c.attribute, c.artist,
                 g.name AS game_name, s.name AS set_name 
          FROM cards c
          JOIN sets s ON c.set_id = s.id
          JOIN games g ON s.game_id = g.id";

$params = array();

if ($game_id) {
  $query .= " WHERE g.id = :game_id";
  $params[':game_id'] = $game_id;
  if ($set_id) {
    $query .= " AND s.id = :set_id";
    $params[':set_id'] = $set_id;
  }
} elseif ($set_id) {
  $query .= " WHERE s.id = :set_id";
  $params[':set_id'] = $set_id;
} elseif ($card_id) {
  $query .= " WHERE c.id = :card_id";
  $params[':card_id'] = $card_id;
}

$query .= " GROUP BY c.id ORDER BY c.id ASC";

$stmt = $conn->prepare($query);
$stmt->execute($params);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cards)) {
  echo '<div class="alert alert-info">No card information available. Please select another Game and Set, or try searching again.</div>';
} else {
  include __DIR__ . '/../components/card_table.php';
}
