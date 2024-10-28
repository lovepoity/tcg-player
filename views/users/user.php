<?php include_once '../../includes/header.php'; ?>
<link rel="stylesheet" href="./assets/css/user.css">
<div class="user">
  <div class="user__container">
    <div class="user__sidebar">
      <?php include_once './tabs/user_layout.php'; ?>
    </div>
    <div class="user__content" id="userContent">
      <?php include_once './tabs/my_account.php'; ?>
    </div>
  </div>
</div>
<script src="./assets/js/user.js"></script>
<?php include_once '../../includes/footer.php'; ?>