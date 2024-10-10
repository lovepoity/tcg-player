<?php
function handleEditCard($conn)
{
  try {
    $id = $_POST['id'];
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

    $image_filename = $_POST['current_image'];
    if ($_FILES['image_upload']['name']) {
      $target_dir = "../../public/images/product/";
      $image_filename = basename($_FILES["image_upload"]["name"]);
      $target_file = $target_dir . $image_filename;
      if (!move_uploaded_file($_FILES["image_upload"]["tmp_name"], $target_file)) {
        throw new Exception("Unable to upload file.");
      }
    }

    $sql = "UPDATE cards SET name = :name, image_filename = :image_filename, rarity = :rarity, 
            product_details = :product_details, card_number = :card_number, color = :color, 
            card_type = :card_type, cost = :cost, power = :power, subtype = :subtype, 
            attribute = :attribute, artist = :artist WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $result = $stmt->execute([
      ':id' => $id,
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
      ':artist' => $artist
    ]);

    if (!$result) {
      throw new Exception("Error updating card.");
    }

    echo json_encode(['success' => true]);
  } catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
  }
}

function getCardById($conn, $id)
{
  $query = "SELECT * FROM cards WHERE id = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$id]);
  return $stmt->fetch(PDO::FETCH_ASSOC);
}
