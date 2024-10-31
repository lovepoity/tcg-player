<?php
session_start();
include_once '../../../includes/db_connect.php';

// Lấy thông tin user từ database
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Nếu không tìm thấy user
if (!$user) {
  echo "User not found";
  exit;
}
?>

<div class="user-content">
  <h2 class="user-content__title">User Information</h2>
  <div class="load--content">
    <h3 class="user--section__title">Change Your Information</h3>
    <form id="informationForm" class="user-information__form">
      <div class="user-information__group">
        <div class="user-information__field">
          <label class="user-information__label">First Name</label>
          <input autocomplete="off" type="text" name="first_name" class="user--input" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
        </div>
        <div class="user-information__field">
          <label class="user-information__label">Last Name</label>
          <input autocomplete="off" type="text" name="last_name" class="user--input" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
        </div>
      </div>

      <div class="user-information__field">
        <label class="user-information__label">Phone</label>
        <input autocomplete="off" type="tel" name="phone" class="user--input" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
      </div>

      <div class="user-information__field">
        <label class="user-information__label">Address</label>
        <input autocomplete="off" type="text" name="address" class="user--input" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
      </div>

      <div class="user-information__group">
        <div class="user-information__field">
          <label class="user-information__label">City</label>
          <input autocomplete="off" type="text" name="city" class="user--input" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
        </div>
        <div class="user-information__field">
          <label class="user-information__label">State</label>
          <input autocomplete="off" type="text" name="state" class="user--input" value="<?php echo htmlspecialchars($user['state'] ?? ''); ?>">
        </div>
      </div>

      <div class="user-information__group">
        <div class="user-information__field">
          <label class="user-information__label">Postal Code</label>
          <input autocomplete="off" type="text" name="postal_code" class="user--input" value="<?php echo htmlspecialchars($user['postal_code'] ?? ''); ?>">
        </div>
        <div class="user-information__field">
          <label class="user-information__label">Country</label>
          <input autocomplete="off" type="text" name="country" class="user--input" value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>">
        </div>
      </div>

      <button style="margin-top: 0;" type="submit" class="user--submit">Save Changes</button>
    </form>
  </div>
</div>
<div id="user__toast" class="user__toast">
  <div class="user__toast-content">
    <p id="user__toast-message"></p>
  </div>
</div>