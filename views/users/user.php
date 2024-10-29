<?php include_once '../../includes/header.php'; ?>
<link rel="stylesheet" href="./assets/css/user.css">
<div class="user">
  <div class="user__container">
    <div class="user__sidebar">
      <?php include_once './tabs/user_layout.php'; ?>
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
<script src="./assets/js/user.js"></script>
<script src="./assets/js/orders.js"></script>
<?php include_once '../../includes/footer.php'; ?>