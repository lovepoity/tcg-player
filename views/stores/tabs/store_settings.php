<?php
session_start();
require_once '../../../includes/db_connect.php';

if (!isset($_SESSION['store_id'])) {
  header("Location: ../auth/login.php");
  exit();
}

// Lấy thông tin store
$query = "SELECT name, email, phone FROM stores WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$_SESSION['store_id']]);
$store = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="store-settings">
  <div class="store-settings__header">
    <h1 class="store-settings__title">Store Settings</h1>
  </div>

  <div class="store-settings__content">
    <div class="store-settings__card">
      <div class="store-settings__card-header">
        <div class="store-settings__card-icon">
          <i class="fa-solid fa-store"></i>
        </div>
        <h2>Store Information</h2>
      </div>

      <form id="store-info-form" class="store-settings__form">
        <div class="store-settings__form-group">
          <label for="store_name">Store Name</label>
          <input type="text" id="store_name" name="store_name" value="<?php echo htmlspecialchars($store['name']); ?>" disabled>
        </div>

        <div class="store-settings__form-group">
          <label for="store_email">Email</label>
          <input type="email" id="store_email" name="store_email" value="<?php echo htmlspecialchars($store['email']); ?>" required>
        </div>

        <div class="store-settings__form-group">
          <label for="store_phone">Phone Number</label>
          <input type="tel" id="store_phone" name="store_phone" value="<?php echo htmlspecialchars($store['phone']); ?>" required>
        </div>

        <button type="submit" class="store-settings__btn">Update Information</button>
      </form>
    </div>

    <div class="store-settings__card">
      <div class="store-settings__card-header">
        <div class="store-settings__card-icon">
          <i class="fa-solid fa-lock"></i>
        </div>
        <h2>Change Password</h2>
      </div>

      <form id="password-form" class="store-settings__form">
        <div class="store-settings__form-group">
          <label for="current_password">Current Password</label>
          <input type="password" id="current_password" name="current_password" required>
        </div>

        <div class="store-settings__form-group">
          <label for="new_password">New Password</label>
          <input type="password" id="new_password" name="new_password" required>
        </div>

        <div class="store-settings__form-group">
          <label for="confirm_password">Confirm New Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required>
        </div>

        <button type="submit" class="store-settings__btn">Change Password</button>
      </form>
    </div>
  </div>
</div>

<script>
  new StoreSettings();
</script>