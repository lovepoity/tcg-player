<?php
function getCartItemCount($conn, $user_id)
{
  $stmt = $conn->prepare("SELECT COUNT(DISTINCT card_listing_id) as unique_items FROM cart WHERE user_id = :user_id");
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  return $result['unique_items'];
}
