<?php
function get_card_details($conn, $id)
{
  $query = "SELECT c.*, s.name AS set_name, s.id AS set_id, g.id AS game_id, g.name AS game_name,
              MIN(cl.price) AS market_price, AVG(cl.price) AS avg_price, MAX(cl.price) AS max_price,
              COUNT(DISTINCT cl.store_id) AS total_stores, SUM(cl.quantity) AS total_quantity
              FROM cards c
              JOIN sets s ON c.set_id = s.id 
              JOIN games g ON s.game_id = g.id
              LEFT JOIN card_listings cl ON c.id = cl.card_id
              WHERE c.id = :id
              GROUP BY c.id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_card_listings($conn, $id, $limit = 5)
{
  $listing_query = "SELECT cl.*, s.name as store_name, cl.shipping as shipping_cost
                      FROM card_listings cl
                      JOIN stores s ON cl.store_id = s.id
                      WHERE cl.card_id = :card_id
                      GROUP BY cl.store_id
                      ORDER BY cl.price DESC
                      LIMIT :limit";
  $listing_stmt = $conn->prepare($listing_query);
  $listing_stmt->bindParam(':card_id', $id, PDO::PARAM_INT);
  $listing_stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $listing_stmt->execute();
  return $listing_stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_recommended_cards($conn, $set_id, $current_card_id, $limit = 12)
{
  $recommend_query = "SELECT c.*, s.name AS set_name, 
                        MIN(cl.price) AS market_price,
                        AVG(cl.price) AS avg_price,
                        SUM(cl.quantity) AS total_quantity
                        FROM cards c
                        JOIN sets s ON c.set_id = s.id 
                        LEFT JOIN card_listings cl ON c.id = cl.card_id
                        WHERE c.set_id = :set_id AND c.id != :current_card_id
                        GROUP BY c.id
                        ORDER BY RAND() LIMIT :limit";
  $stmt = $conn->prepare($recommend_query);
  $stmt->bindParam(':set_id', $set_id, PDO::PARAM_INT);
  $stmt->bindParam(':current_card_id', $current_card_id, PDO::PARAM_INT);
  $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_highest_price_listing($conn, $id)
{
  $highest_price_query = "SELECT cl.*, s.name as store_name
                            FROM card_listings cl
                            JOIN stores s ON cl.store_id = s.id
                            WHERE cl.card_id = ? AND cl.price = (
                                SELECT MAX(price)
                                FROM card_listings
                                WHERE card_id = ?
                            )
                            LIMIT 1";
  $highest_price_stmt = $conn->prepare($highest_price_query);
  $highest_price_stmt->execute([$id, $id]);
  return $highest_price_stmt->fetch(PDO::FETCH_ASSOC);
}

function get_cards_for_page($conn, $game_id, $set_id, $offset, $items_per_page)
{
  $where_clause = $set_id ? "WHERE c.set_id = :set_id" : "WHERE s.game_id = :game_id";
  $query = "
        SELECT c.id, c.name, c.image_filename, c.rarity, c.card_number, s.name as set_name
        FROM cards c
        JOIN sets s ON c.set_id = s.id
        $where_clause
        ORDER BY c.id DESC
        LIMIT :offset, :items_per_page
    ";

  $stmt = $conn->prepare($query);
  if ($set_id) {
    $stmt->bindParam(':set_id', $set_id, PDO::PARAM_INT);
  } else {
    $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
  }
  $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
  $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
  $stmt->execute();

  $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Fetch listings for each card
  foreach ($cards as &$card) {
    $query = "
            SELECT cl.price, cl.quantity, s.name as store_name
            FROM card_listings cl
            JOIN stores s ON cl.store_id = s.id
            WHERE cl.card_id = :card_id
        ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':card_id', $card['id'], PDO::PARAM_INT);
    $stmt->execute();
    $card['listings'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  return $cards;
}

function get_total_cards($conn, $game_id = null, $set_id = null)
{
  $sql = "SELECT COUNT(*) FROM cards";
  $params = [];

  if ($set_id) {
    $sql .= " WHERE set_id = ?";
    $params[] = $set_id;
  } elseif ($game_id) {
    $sql .= " WHERE set_id IN (SELECT id FROM sets WHERE game_id = ?)";
    $params[] = $game_id;
  }

  $stmt = $conn->prepare($sql);
  $stmt->execute($params);
  return $stmt->fetchColumn();
}

function get_game_set_info($conn, $game_id, $set_id)
{
  if ($set_id) {
    $info_query = "SELECT s.name AS set_name, g.id AS game_id, g.name AS game_name 
                       FROM sets s 
                       JOIN games g ON s.game_id = g.id 
                       WHERE s.id = :id";
    $param = $set_id;
  } else {
    $info_query = "SELECT id AS game_id, name AS game_name FROM games WHERE id = :id";
    $param = $game_id;
  }

  $info_stmt = $conn->prepare($info_query);
  $info_stmt->bindParam(':id', $param, PDO::PARAM_INT);
  $info_stmt->execute();
  return $info_stmt->fetch(PDO::FETCH_ASSOC);
}

function get_all_cards_for_page($conn, $offset, $items_per_page)
{
  $sql = "SELECT c.*, s.name as set_name, g.name as game_name,
                   (SELECT COUNT(*) FROM card_listings cl WHERE cl.card_id = c.id) as listing_count,
                   (SELECT MIN(price) FROM card_listings cl WHERE cl.card_id = c.id) as lowest_price
            FROM cards c
            JOIN sets s ON c.set_id = s.id
            JOIN games g ON s.game_id = g.id
            ORDER BY c.id DESC
            LIMIT :limit OFFSET :offset";

  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
