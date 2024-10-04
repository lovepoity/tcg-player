<?php
require_once '../../includes/config.php';
require_once INCLUDES_PATH . 'db_connect.php';
require_once INCLUDES_PATH . 'functions.php';

session_start();

if (!isset($_SESSION['store_id'])) {
  header('Location: store_login.php');
  exit;
}

$store_id = $_SESSION['store_id'];
$store_name = $_SESSION['store_name'];
$message = '';

// Handle updating or adding new listing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $card_id = $_POST['card_id'];
  $new_price = $_POST['new_price'];
  $new_quantity = $_POST['new_quantity'];
  $new_shipping = $_POST['new_shipping'];

  // Check conditions
  if ($new_quantity >= 0 && $new_price >= 0 && $new_shipping >= 0) {
    $conn->beginTransaction();

    try {
      // Check if listing already exists
      $check_query = "SELECT id FROM card_listings WHERE card_id = ? AND store_id = ?";
      $check_stmt = $conn->prepare($check_query);
      $check_stmt->execute([$card_id, $store_id]);
      $existing_listing = $check_stmt->fetch(PDO::FETCH_ASSOC);

      if ($existing_listing) {
        // Update existing listing
        $update_query = "UPDATE card_listings 
                         SET price = ?, quantity = ?, shipping = ?
                         WHERE card_id = ? AND store_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->execute([$new_price, $new_quantity, $new_shipping, $card_id, $store_id]);
        $message = "Listing updated successfully!";
      } else {
        // Add new listing
        $insert_query = "INSERT INTO card_listings (card_id, store_id, price, quantity, shipping)
                         VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->execute([$card_id, $store_id, $new_price, $new_quantity, $new_shipping]);
        $message = "New listing added successfully!";
      }

      $conn->commit();
    } catch (Exception $e) {
      $conn->rollBack();
      $message = "Error: " . $e->getMessage();
    }
  } else {
    $message = "Error: Quantity, price, and shipping must be non-negative.";
  }
}

// Get list of games
$games_query = "SELECT id, name FROM games";
$games_stmt = $conn->prepare($games_query);
$games_stmt->execute();
$games = $games_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get store's listings
$listings_query = "SELECT cl.*, c.name as card_name, c.image_filename, s.name as set_name, g.name as game_name
                   FROM card_listings cl
                   JOIN cards c ON cl.card_id = c.id
                   JOIN sets s ON c.set_id = s.id
                   JOIN games g ON s.game_id = g.id
                   WHERE cl.store_id = ?";
$listings_stmt = $conn->prepare($listings_query);
$listings_stmt->execute([$store_id]);
$listings = $listings_stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/public/images/favicon.ico">
  <title>Store Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      margin: 0;
      padding: 20px;
      background-color: #f4f4f4;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      background-color: #fff;
      padding: 20px;
      border-radius: 5px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1,
    h2 {
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

    input[type="number"],
    input[type="submit"] {
      width: 80px;
      padding: 5px;
    }

    .logout {
      margin-top: 20px;
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

    .add-listing {
      background-color: #e9ecef;
      padding: 20px;
      border-radius: 5px;
      margin-top: 20px;
    }

    .add-listing select,
    .add-listing input {
      margin-bottom: 10px;
      padding: 5px;
    }

    #card-image {
      max-width: 200px;
      max-height: 280px;
      margin-top: 10px;
    }

    .card-image-small {
      max-width: 50px;
      max-height: 70px;
      object-fit: contain;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($store_name); ?>!</h1>

    <?php if ($message): ?>
      <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <h2>Your Product Listings</h2>
    <table>
      <tr>
        <th>Image</th>
        <th>Game</th>
        <th>Set</th>
        <th>Card Name</th>
        <th>Current Price</th>
        <th>Current Quantity</th>
        <th>Current Shipping</th>
        <th>Action</th>
      </tr>
      <?php foreach ($listings as $listing): ?>
        <tr>
          <td>
            <img class="card-image-small" src="/public/images/product/<?php echo htmlspecialchars($listing['image_filename']); ?>" alt="<?php echo htmlspecialchars($listing['card_name']); ?>">
          </td>
          <td><?php echo htmlspecialchars($listing['game_name']); ?></td>
          <td><?php echo htmlspecialchars($listing['set_name']); ?></td>
          <td><?php echo htmlspecialchars($listing['card_name']); ?></td>
          <td>$<?php echo number_format($listing['price'], 2); ?></td>
          <td><?php echo $listing['quantity']; ?></td>
          <td>$<?php echo number_format($listing['shipping'], 2); ?></td>
          <td>
            <form method="POST" onsubmit="return validateForm(this);">
              <input type="hidden" name="card_id" value="<?php echo $listing['card_id']; ?>">
              <input type="number" name="new_price" step="0.01" min="0" value="<?php echo $listing['price']; ?>" placeholder="New Price">
              <input type="number" name="new_quantity" min="0" value="<?php echo $listing['quantity']; ?>" placeholder="New Quantity">
              <input type="number" name="new_shipping" step="0.01" min="0" value="<?php echo $listing['shipping']; ?>" placeholder="New Shipping">
              <input type="submit" value="Update">
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <div class="add-listing">
      <h2>Add New Product</h2>
      <form method="POST" onsubmit="return validateForm(this);">
        <select id="game_select" name="game_id" required onchange="loadSets(this.value)">
          <option value="">Select Game</option>
          <?php foreach ($games as $game): ?>
            <option value="<?php echo $game['id']; ?>"><?php echo htmlspecialchars($game['name']); ?></option>
          <?php endforeach; ?>
        </select>

        <select id="set_select" name="set_id" required onchange="loadCards(this.value)" disabled>
          <option value="">Select Set</option>
        </select>

        <select id="card_select" name="card_id" required onchange="loadCardImage(this.value)" disabled>
          <option value="">Select Card</option>
        </select>

        <input type="number" name="new_price" step="0.01" min="0" placeholder="Price" required>
        <input type="number" name="new_quantity" min="0" placeholder="Quantity" required>
        <input type="number" name="new_shipping" step="0.01" min="0" placeholder="Shipping" required>
        <input type="submit" value="Add Product">
      </form>
      <img id="card-image" src="" alt="Card Image" style="display: none;">
    </div>

    <div class="logout">
      <a href="store_logout.php">Logout</a>
    </div>
  </div>

  <script>
    function validateForm(form) {
      var price = parseFloat(form.elements['new_price'].value);
      var quantity = parseInt(form.elements['new_quantity'].value);
      var shipping = parseFloat(form.elements['new_shipping'].value);

      if (quantity < 0 || price < 0 || shipping < 0) {
        alert("Quantity, price, and shipping must be non-negative.");
        return false;
      }
      return true;
    }

    function loadSets(gameId) {
      fetch(`get_sets.php?game_id=${gameId}`)
        .then(response => response.json())
        .then(sets => {
          const setSelect = document.getElementById('set_select');
          setSelect.innerHTML = '<option value="">Select Set</option>';
          sets.forEach(set => {
            setSelect.innerHTML += `<option value="${set.id}">${set.name}</option>`;
          });
          setSelect.disabled = false;
          document.getElementById('card_select').disabled = true;
          document.getElementById('card-image').style.display = 'none';
        });
    }

    function loadCards(setId) {
      fetch(`get_cards.php?set_id=${setId}`)
        .then(response => response.json())
        .then(cards => {
          const cardSelect = document.getElementById('card_select');
          cardSelect.innerHTML = '<option value="">Select Card</option>';
          cards.forEach(card => {
            cardSelect.innerHTML += `<option value="${card.id}">${card.name}</option>`;
          });
          cardSelect.disabled = false;
          document.getElementById('card-image').style.display = 'none';
        });
    }

    function loadCardImage(cardId) {
      fetch(`get_card_image.php?card_id=${cardId}`)
        .then(response => response.json())
        .then(data => {
          const cardImage = document.getElementById('card-image');
          if (data.image_filename) {
            cardImage.src = `/public/images/product/${data.image_filename}`;
            cardImage.style.display = 'block';
          } else {
            cardImage.style.display = 'none';
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('card-image').style.display = 'none';
        });
    }
  </script>
</body>

</html>