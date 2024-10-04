<?php
session_start();
require_once '../../includes/db_connect.php';
require_once '../../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input_name = $_POST['name'];
  $password = $_POST['password'];

  try {
    $query = "SELECT * FROM stores WHERE LOWER(REPLACE(name, ' ', '')) = LOWER(REPLACE(?, ' ', ''))";
    $stmt = $conn->prepare($query);
    $stmt->execute([preg_replace('/[^a-z0-9]/i', '', $input_name)]);
    $store = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($store && password_verify($password, $store['password'])) {
      $_SESSION['store_id'] = $store['id'];
      $_SESSION['store_name'] = $store['name'];
      redirect('store_dashboard.php');
    } else {
      $error = "Invalid store name or password";
    }
  } catch (PDOException $e) {
    error_log($e->getMessage());
    $error = "An error occurred. Please try again later.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/public/images/favicon.ico">
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