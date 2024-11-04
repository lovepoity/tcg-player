<?php
require_once '../../../includes/db_connect.php';
require_once __DIR__ . '/../functions/orders.php';
session_start();

$user_id = $_SESSION['user_id'];

// Lấy email user
$stmt = $conn->prepare("SELECT email FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy orders và tính total spent
$orders = getOrdersByUserId($user_id);
$totalSpent = array_reduce($orders, function ($carry, $order) {
  if ($order['status'] !== 'Cancelled') {
    $carry += $order['total_amount'];
  }
  return $carry;
}, 0);
?>

<div class="user-content">
  <h2 class="user-content__title">My Account</h2>
  <div class="user-content__welcome">
    <p>Welcome, <span><?php echo htmlspecialchars($user['email']); ?></span></p>
    <p>Total Spent: <span>$<?php echo number_format($totalSpent, 2); ?></span></p>
  </div>
  <div class="user-content__actions">
    <div class="my__action">
      <a href="#" data-tab="user_history">
        <i class="fa-solid fa-list"></i>
        <p>Order History</p>
        <span>Access your order history here</span>
      </a>
    </div>
    <div class="my__action">
      <a href="#" data-tab="user_data">
        <i class="fa-solid fa-gear"></i>
        <p>Account Settings</p>
        <span>Manage your account settings</span>
      </a>
    </div>
    <div class="my__action">
      <a href="#" data-tab="user_information">
        <i class="fa-solid fa-user"></i>
        <p>User Information</p>
        <span>Update your personal information</span>
      </a>
    </div>
    <div class="my__action">
      <a href="#" data-tab="user_payment">
        <i class="fa-solid fa-credit-card"></i>
        <p>Payment Methods</p>
        <span>Manage your payment options</span>
      </a>
    </div>
    <div class="my__action">
      <a href="#" data-tab="user_giftcard">
        <i class="fa-solid fa-gift"></i>
        <p>Gift Cards</p>
        <span>Redeem your gift cards</span>
      </a>
    </div>
    <div class="my__action">
      <a href="#" data-tab="user_credit">
        <i class="fa-solid fa-wallet"></i>
        <p>Store Credit</p>
        <span>Check your store credit balance</span>
      </a>
    </div>
    <div class="my__action">
      <a href="#" data-tab="user_permission">
        <i class="fa-solid fa-lock"></i>
        <p>Permissions</p>
        <span>Manage your account permissions</span>
      </a>
    </div>
    <div class="my__action">
      <a href="#" data-tab="user_email">
        <i class="fa-solid fa-envelope"></i>
        <p>Email Preferences</p>
        <span>Control your email notifications</span>
      </a>
    </div>
  </div>
</div>