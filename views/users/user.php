<?php include_once '../../includes/header.php';
?>
<link rel="stylesheet" href="./assets/css/user.css">
<div class="user">
  <div class="user__container">
    <div class="user__sidebar">
      <nav class="user-nav">
        <h1 class="user-nav__title">My Account</h1>
        <ul class="user-nav__list">
          <li class="user-nav__item">
            <a href="#" class="user-nav__link user-nav__link--active" data-tab="my_account"><i class="fa-solid fa-house"></i> Account Home</a>
          </li>
          <li class="user-nav__item">
            <a href="#" class="user-nav__link" data-tab="user_history"><i class="fa-solid fa-clock-rotate-left"></i> Order History</a>
          </li>
          <li class="user-nav__item">
            <a href="#" class="user-nav__link" data-tab="user_data"><i class="fa-solid fa-shield-halved"></i> Account & Data</a>
          </li>
          <li class="user-nav__item">
            <a href="#" class="user-nav__link" data-tab="user_information"><i class="fa-solid fa-user"></i> User Information</a>
          </li>
          <li class="user-nav__item">
            <a href="#" class="user-nav__link" data-tab="user_payment"><i class="fa-solid fa-credit-card"></i> Payment Methods</a>
          </li>
          <li class="user-nav__item">
            <a href="#" class="user-nav__link" data-tab="user_giftcard"><i class="fa-solid fa-gift"></i> Redeem Gift Card</a>
          </li>
          <li class="user-nav__item">
            <a href="#" class="user-nav__link" data-tab="user_credit"><i class="fa-solid fa-money-bill"></i> Store Credit</a>
          </li>
          <li class="user-nav__item">
            <a href="#" class="user-nav__link" data-tab="user_permission"><i class="fa-solid fa-user-shield"></i> Permissions</a>
          </li>
          <li class="user-nav__item">
            <a href="#" class="user-nav__link" data-tab="user_email"><i class="fa-solid fa-envelope"></i> Email Preferences</a>
          </li>
        </ul>
      </nav>
    </div>
    <div class="user__content" id="userContent">
      <?php
      $tab = isset($_GET['tab']) ? $_GET['tab'] : '';
      $orderId = isset($_GET['order_id']) ? $_GET['order_id'] : null;

      if ($tab === 'orders' && $orderId) {
        include_once './tabs/order_detail.php';
      } else {
        switch ($tab) {
          case 'my_account':
            include_once './tabs/my_account.php';
            break;
          case 'user_history':
            include_once './tabs/user_history.php';
            break;
          case 'user_data':
            include_once './tabs/user_data.php';
            break;
          case 'user_address':
            include_once './tabs/user_address.php';
            break;
          case 'user_payment':
            include_once './tabs/user_payment.php';
            break;
          case 'user_giftcard':
            include_once './tabs/user_giftcard.php';
            break;
          case 'user_credit':
            include_once './tabs/user_credit.php';
            break;
          case 'user_permission':
            include_once './tabs/user_permission.php';
            break;
          case 'user_email':
            include_once './tabs/user_email.php';
            break;
        }
      }
      ?>
    </div>
  </div>
</div>
<div id="user__toast" class="user__toast">
  <div class="user__toast-content">
    <p id="user__toast-message"></p>
  </div>
</div>
<script src="./assets/js/user.js"></script>
<script src="./assets/js/order_detail.js"></script>
<script src="./assets/js/toast.js"></script>
<script src="./assets/js/data.js"></script>
<script src="./assets/js/information.js"></script>
<?php include_once '../../includes/footer.php'; ?>