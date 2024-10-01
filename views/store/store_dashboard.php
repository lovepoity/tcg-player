<?php
session_start();
include '../../includes/db_connect.php';

if (!isset($_SESSION['store_id'])) {
  header('Location: store_login.php');
  exit;
}

$store_id = $_SESSION['store_id'];
$store_name = $_SESSION['store_name'];
$message = '';

// Xử lý cập nhật giá và số lượng
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $card_id = $_POST['card_id'];
  $new_price = $_POST['new_price'];
  $new_quantity = $_POST['new_quantity'];

  // Kiểm tra điều kiện
  if ($new_quantity > 0 && $new_price >= 0) {
    $conn->beginTransaction();

    try {
      // Lấy listing_id cho store này
      $get_listing_query = "SELECT id FROM listings WHERE id = ?";
      $get_listing_stmt = $conn->prepare($get_listing_query);
      $get_listing_stmt->execute([$store_id]);
      $listing = $get_listing_stmt->fetch(PDO::FETCH_ASSOC);

      if (!$listing) {
        throw new Exception("Không tìm thấy listing cho cửa hàng này.");
      }

      $listing_id = $listing['id'];

      // Sử dụng INSERT ... ON DUPLICATE KEY UPDATE
      $upsert_query = "INSERT INTO card_listings (card_id, listing_id, store_id, price, quantity) 
                       VALUES (?, ?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE 
                       price = VALUES(price), 
                       quantity = VALUES(quantity),
                       store_id = VALUES(store_id)";
      $upsert_stmt = $conn->prepare($upsert_query);
      $upsert_stmt->execute([$card_id, $listing_id, $store_id, $new_price, $new_quantity]);

      $conn->commit();
      $message = "Cập nhật thành công!";
    } catch (Exception $e) {
      $conn->rollBack();
      $message = "Lỗi: " . $e->getMessage();
    }
  } else {
    $message = "Lỗi: Số lượng phải lớn hơn 0 và giá phải không âm.";
  }
}

// Lấy danh sách tất cả các card và thông tin listing của cửa hàng (nếu có)
$query = "SELECT c.id AS card_id, c.name AS card_name, 
          COALESCE(cl.price, 0.00) AS price, 
          COALESCE(cl.quantity, 0) AS quantity
          FROM cards c
          LEFT JOIN card_listings cl ON c.id = cl.card_id AND cl.store_id = ?
          ORDER BY c.name ASC";
$stmt = $conn->prepare($query);
$stmt->execute([$store_id]);
$listings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Store Dashboard - <?php echo htmlspecialchars($store_name); ?></title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      margin: 0;
      padding: 20px;
    }

    h1 {
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }

    tr:hover {
      background-color: #f5f5f5;
    }

    input[type="number"] {
      width: 80px;
    }

    .logout {
      margin-top: 20px;
    }

    .out-of-stock {
      color: red;
    }

    .message {
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 5px;
    }

    .success {
      background-color: #d4edda;
      border-color: #c3e6cb;
      color: #155724;
    }

    .error {
      background-color: #f8d7da;
      border-color: #f5c6cb;
      color: #721c24;
    }
  </style>
</head>

<body>
  <h1>Welcome, <?php echo htmlspecialchars($store_name); ?>!</h1>

  <?php if ($message): ?>
    <div class="message <?php echo strpos($message, 'Lỗi') !== false ? 'error' : 'success'; ?>">
      <?php echo $message; ?>
    </div>
  <?php endif; ?>

  <h2>Your Listings</h2>
  <table>
    <tr>
      <th>Card Name</th>
      <th>Current Price</th>
      <th>Current Quantity</th>
      <th>Action</th>
    </tr>
    <?php foreach ($listings as $listing): ?>
      <tr <?php if ($listing['quantity'] == 0) echo 'class="out-of-stock"'; ?>>
        <td><?php echo htmlspecialchars($listing['card_name']); ?></td>
        <td>$<?php echo number_format($listing['price'], 2); ?></td>
        <td><?php echo $listing['quantity']; ?></td>
        <td>
          <form method="POST" onsubmit="return validateForm(this);">
            <input type="hidden" name="card_id" value="<?php echo $listing['card_id']; ?>">
            <label for="new_price_<?php echo $listing['card_id']; ?>">New Price:</label>
            <input type="number" id="new_price_<?php echo $listing['card_id']; ?>" name="new_price" step="0.01" min="0" value="<?php echo $listing['price']; ?>">
            <label for="new_quantity_<?php echo $listing['card_id']; ?>">New Quantity:</label>
            <input type="number" id="new_quantity_<?php echo $listing['card_id']; ?>" name="new_quantity" min="0" value="<?php echo $listing['quantity']; ?>">
            <input type="submit" value="Update">
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  <div class="logout">
    <a href="store_logout.php">Logout</a>
  </div>

  <script>
    function validateForm(form) {
      var price = parseFloat(form.elements['new_price'].value);
      var quantity = parseInt(form.elements['new_quantity'].value);

      if (quantity <= 0 || price < 0) {
        alert("Số lượng phải lớn hơn 0 và giá phải không âm.");
        return false;
      }
      return true;
    }
  </script>
</body>

</html>