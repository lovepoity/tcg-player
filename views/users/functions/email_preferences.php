<?php
require_once '../../../includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

try {
  $conn->beginTransaction();

  // Kiểm tra xem user đã có bản ghi chưa
  $stmt = $conn->prepare("SELECT id FROM email_pref WHERE user_id = :user_id");
  $stmt->bindParam(':user_id', $user_id);
  $stmt->execute();
  $exists = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($exists) {
    // Update
    $sql = "UPDATE email_pref SET 
                magic_gathering = :magic_gathering,
                pokemon = :pokemon,
                flesh_blood = :flesh_blood,
                digimon = :digimon,
                yu_gi_oh = :yu_gi_oh,
                disney_lorcana = :disney_lorcana,
                one_piece = :one_piece,
                star_wars = :star_wars,
                channel_fireball = :channel_fireball,
                cassie_calls = :cassie_calls,
                new_games = :new_games,
                promotions = :promotions,
                new_features = :new_features 
                WHERE user_id = :user_id";
  } else {
    // Insert
    $sql = "INSERT INTO email_pref 
                (magic_gathering, pokemon, flesh_blood, digimon, yu_gi_oh, 
                disney_lorcana, one_piece, star_wars, channel_fireball, 
                cassie_calls, new_games, promotions, new_features, user_id) 
                VALUES 
                (:magic_gathering, :pokemon, :flesh_blood, :digimon, :yu_gi_oh,
                :disney_lorcana, :one_piece, :star_wars, :channel_fireball,
                :cassie_calls, :new_games, :promotions, :new_features, :user_id)";
  }

  $stmt = $conn->prepare($sql);

  $stmt->bindValue(':magic_gathering', $data['magic_gathering'], PDO::PARAM_INT);
  $stmt->bindValue(':pokemon', $data['pokemon'], PDO::PARAM_INT);
  $stmt->bindValue(':flesh_blood', $data['flesh_blood'], PDO::PARAM_INT);
  $stmt->bindValue(':digimon', $data['digimon'], PDO::PARAM_INT);
  $stmt->bindValue(':yu_gi_oh', $data['yu_gi_oh'], PDO::PARAM_INT);
  $stmt->bindValue(':disney_lorcana', $data['disney_lorcana'], PDO::PARAM_INT);
  $stmt->bindValue(':one_piece', $data['one_piece'], PDO::PARAM_INT);
  $stmt->bindValue(':star_wars', $data['star_wars'], PDO::PARAM_INT);
  $stmt->bindValue(':channel_fireball', $data['channel_fireball'], PDO::PARAM_INT);
  $stmt->bindValue(':cassie_calls', $data['cassie_calls'], PDO::PARAM_INT);
  $stmt->bindValue(':new_games', $data['new_games'], PDO::PARAM_INT);
  $stmt->bindValue(':promotions', $data['promotions'], PDO::PARAM_INT);
  $stmt->bindValue(':new_features', $data['new_features'], PDO::PARAM_INT);
  $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

  $stmt->execute();
  $conn->commit();

  echo json_encode(['success' => true]);
} catch (Exception $e) {
  $conn->rollBack();
  echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
