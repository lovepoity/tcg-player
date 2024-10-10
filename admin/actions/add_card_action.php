<?php
function handleAddCard($conn)
{
  try {
    $name = $_POST['name'];
    $rarity = $_POST['rarity'];
    $product_details = $_POST['product_details'];
    $card_number = $_POST['card_number'];
    $color = $_POST['color'];
    $card_type = $_POST['card_type'];
    $cost = $_POST['cost'];
    $power = $_POST['power'];
    $subtype = $_POST['subtype'];
    $attribute = $_POST['attribute'];
    $artist = $_POST['artist'];
    $set_id = $_POST['set_id'];

    // Check if the card name already exists in the cards table
    $check_cards_sql = "SELECT COUNT(*) FROM cards WHERE name = :name";
    $check_cards_stmt = $conn->prepare($check_cards_sql);
    $check_cards_stmt->execute([':name' => $name]);
    $card_exists = $check_cards_stmt->fetchColumn();

    // Check if the card name already exists in the sets table
    $check_sets_sql = "SELECT COUNT(*) FROM sets WHERE name = :name";
    $check_sets_stmt = $conn->prepare($check_sets_sql);
    $check_sets_stmt->execute([':name' => $name]);
    $set_exists = $check_sets_stmt->fetchColumn();

    if ($card_exists > 0 || $set_exists > 0) {
      throw new Exception("A card or set with this name already exists.");
    }

    // Handle image upload
    if ($_FILES['image_upload']['name']) {
      $target_dir = "../../public/images/product/";
      $image_filename = basename($_FILES["image_upload"]["name"]);
      $target_file = $target_dir . $image_filename;
      if (!move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
        throw new Exception("Unable to upload file.");
      }
    } else {
      throw new Exception("No image uploaded.");
    }

    $sql = "INSERT INTO cards (name, image_filename, rarity, product_details, card_number, color, card_type, cost, power, subtype, attribute, artist, set_id) VALUES (:name, :image_filename, :rarity, :product_details, :card_number, :color, :card_type, :cost, :power, :subtype, :attribute, :artist, :set_id)";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
      ':name' => $name,
      ':image_filename' => $image_filename,
      ':rarity' => $rarity,
      ':product_details' => $product_details,
      ':card_number' => $card_number,
      ':color' => $color,
      ':card_type' => $card_type,
      ':cost' => $cost,
      ':power' => $power,
      ':subtype' => $subtype,
      ':attribute' => $attribute,
      ':artist' => $artist,
      ':set_id' => $set_id
    ]);

    if (!$result) {
      throw new Exception("Error adding new card.");
    }

    echo json_encode(['success' => true]);
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
  }
}
