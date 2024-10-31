<?php
session_start();
include_once '../../../includes/db_connect.php';

$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT email FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  echo "User not found";
  exit;
}
?>

<div class="user-content">
  <h2 class="user-content__title">Account & Data</h2>
  <div class="user-data">
    <h3 class="user--section__title">Change Your Email</h3>
    <p class="user--section__desc">Use the form below to change your email.</p>
    <form id="emailForm" class="user-data__form">
      <div class="user-data__field">
        <input placeholder="New Email" type="email" name="email" class="user--input">
      </div>
      <div class="user-data__field">
        <input placeholder="Confirm Email" type="email" name="confirm_email" class="user--input">
      </div>
      <div class="user-data__field">
        <input placeholder="Current Password" type="password" name="password" class="user--input">
      </div>
      <button type="submit" class="user--submit">Submit</button>
    </form>
    <h3 class="user--section__title">Change Your Password</h3>
    <p class="user--section__desc">Use the form below to change your password. Required fields are marked with an asterisk (*).</p>
    <form id="passwordForm" class="user-data__form">
      <div class="user-data__field">
        <input placeholder="Current Password" type="password" name="current_password" class="user--input">
      </div>
      <div class="password-requirements">
        <span>At least:</span>
        <span id="length" class="requirement">8 characters &nbsp;- </span>
        <span id="lowercase" class="requirement">1 lowercase &nbsp;- </span>
        <span id="number" class="requirement">1 number &nbsp;- </span>
        <span id="uppercase" class="requirement">1 uppercase</span>
      </div>
      <div class="user-data__field">
        <input placeholder="New Password" type="password" name="password" class="user--input" id="newPassword">
      </div>
      <div class="user-data__field">
        <input placeholder="Confirm New Password" type="password" name="confirm_password" class="user--input">
      </div>
      <button type="submit" class="user--submit">Submit</button>
    </form>
  </div>
</div>

<div id="user__toast" class="user__toast">
  <div class="user__toast-content">
    <p id="user__toast-message"></p>
  </div>
</div>