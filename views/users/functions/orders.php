<?php
function getOrdersByUserId($userId)
{
  global $conn;

  $sql = "SELECT 
                o.id,
                o.total_amount,
                o.shipping_fee,
                o.status,
                o.payment_method,
                o.payment_status,
                o.created_at,
                o.first_name,
                o.last_name,
                o.email,
                o.phone,
                o.address,
                o.city,
                o.state,
                o.country,
                o.postal_code,
                GROUP_CONCAT(
                    CONCAT_WS('|',
                        c.name,
                        c.image_filename,
                        s.name,
                        oi.quantity,
                        oi.price,
                        oi.status,
                        c.id,
                        c.rarity,
                        c.card_number,
                        c.subtype,
                        sets.name,
                        g.name,
                        cl.shipping
                    )
                ) as items_info
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN card_listings cl ON oi.card_listing_id = cl.id 
            LEFT JOIN cards c ON cl.card_id = c.id
            LEFT JOIN stores s ON oi.store_id = s.id
            LEFT JOIN sets ON c.set_id = sets.id
            LEFT JOIN games g ON sets.game_id = g.id
            WHERE o.user_id = :user_id
            GROUP BY o.id
            ORDER BY o.created_at DESC";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':user_id', $userId);
  $stmt->execute();

  $orders = [];
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $order = [
      'id' => $row['id'],
      'total_amount' => $row['total_amount'],
      'shipping_fee' => $row['shipping_fee'],
      'status' => $row['status'],
      'payment_method' => $row['payment_method'],
      'payment_status' => $row['payment_status'],
      'created_at' => $row['created_at'],
      'first_name' => $row['first_name'],
      'last_name' => $row['last_name'],
      'email' => $row['email'],
      'phone' => $row['phone'],
      'address' => $row['address'],
      'city' => $row['city'],
      'state' => $row['state'],
      'country' => $row['country'],
      'postal_code' => $row['postal_code'],
      'items' => []
    ];

    if ($row['items_info']) {
      $items = explode(',', $row['items_info']);
      foreach ($items as $item) {
        list($card_name, $image_filename, $store_name, $quantity, $price, $status, $card_id, $rarity, $card_number, $subtype, $set_name, $game_name, $shipping) = explode('|', $item);
        $order['items'][] = [
          'card_name' => $card_name,
          'image_filename' => $image_filename,
          'store_name' => $store_name,
          'quantity' => $quantity,
          'price' => $price,
          'status' => $status,
          'card_id' => $card_id,
          'rarity' => $rarity,
          'card_number' => $card_number,
          'subtype' => $subtype,
          'set_name' => $set_name,
          'game_name' => $game_name,
          'shipping' => $shipping
        ];
      }
    }

    $orders[] = $order;
  }

  return $orders;
}

function getOrderById($orderId)
{
  global $conn;

  $sql = "SELECT 
                o.id,
                o.total_amount,
                o.shipping_fee,
                o.status,
                o.payment_method,
                o.payment_status,
                o.created_at,
                o.first_name,
                o.last_name,
                o.email,
                o.phone,
                o.address,
                o.city,
                o.state,
                o.country,
                o.postal_code,
                GROUP_CONCAT(
                    CONCAT_WS('|',
                        c.name,
                        c.image_filename,
                        s.name,
                        oi.quantity,
                        oi.price,
                        oi.status,
                        c.id,
                        c.rarity,
                        c.card_number,
                        c.subtype,
                        sets.name,
                        g.name,
                        cl.shipping
                    )
                ) as items_info
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN card_listings cl ON oi.card_listing_id = cl.id 
            LEFT JOIN cards c ON cl.card_id = c.id
            LEFT JOIN stores s ON oi.store_id = s.id
            LEFT JOIN sets ON c.set_id = sets.id
            LEFT JOIN games g ON sets.game_id = g.id
            WHERE o.id = :order_id
            GROUP BY o.id";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':order_id', $orderId);
  $stmt->execute();

  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row) return null;

  $order = [
    'id' => $row['id'],
    'total_amount' => $row['total_amount'],
    'shipping_fee' => $row['shipping_fee'],
    'status' => $row['status'],
    'payment_method' => $row['payment_method'],
    'payment_status' => $row['payment_status'],
    'created_at' => $row['created_at'],
    'first_name' => $row['first_name'],
    'last_name' => $row['last_name'],
    'email' => $row['email'],
    'phone' => $row['phone'],
    'address' => $row['address'],
    'city' => $row['city'],
    'state' => $row['state'],
    'country' => $row['country'],
    'postal_code' => $row['postal_code'],
    'items' => []
  ];

  if ($row['items_info']) {
    $items = explode(',', $row['items_info']);
    foreach ($items as $item) {
      list($card_name, $image_filename, $store_name, $quantity, $price, $status, $card_id, $rarity, $card_number, $subtype, $set_name, $game_name, $shipping) = explode('|', $item);
      $order['items'][] = [
        'card_name' => $card_name,
        'image_filename' => $image_filename,
        'store_name' => $store_name,
        'quantity' => $quantity,
        'price' => $price,
        'status' => $status,
        'card_id' => $card_id,
        'rarity' => $rarity,
        'card_number' => $card_number,
        'subtype' => $subtype,
        'set_name' => $set_name,
        'game_name' => $game_name,
        'shipping' => $shipping
      ];
    }
  }

  return $order;
}
