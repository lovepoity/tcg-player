<?php
session_start();
include '../../includes/db_connect.php';  // Sửa đường dẫn này

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $password = $_POST['password'];

  $query = "SELECT * FROM stores WHERE name = ?";
  $stmt = $conn->prepare($query);
  $stmt->execute([$name]);
  $store = $stmt->fetch(PDO::FETCH_ASSOC);

  // Debug information
  echo "Entered store name: " . htmlspecialchars($name) . "<br>";
  echo "Entered password: " . $password . "<br>";

  if ($store) {
    echo "Store found. Stored password hash: " . $store['password'] . "<br>";
    if (password_verify($password, $store['password'])) {
      echo "Password verified successfully!<br>";
      $_SESSION['store_id'] = $store['id'];
      $_SESSION['store_name'] = $store['name'];
      header('Location: store_dashboard.php');
      exit;
    } else {
      echo "Password verification failed.<br>";
      $error = "Invalid store name or password";
    }
  } else {
    echo "Store not found.<br>";
    $error = "Invalid store name or password";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Store Login</title>
</head>

<body>
  <h1>Store Login</h1>
  <?php if (isset($error)) echo "<p>$error</p>"; ?>
  <form method="POST">
    <label for="name">Store Name:</label>
    <input type="text" id="name" name="name" required><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br>
    <input type="submit" value="Login">
  </form>
</body>

</html>