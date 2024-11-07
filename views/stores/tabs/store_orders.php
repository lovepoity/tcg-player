<?php
session_start();
if (!isset($_SESSION['store_id'])) {
  header("Location: ../auth/login.php");
  exit();
}
?>

<div class="store-orders">
  <h1 class="title">Orders Management</h1>

  <div class="store-orders__filters">
    <select class="store-orders__select" id="statusFilter">
      <option value="">All Status</option>
      <option value="Processing">Processing</option>
      <option value="Picking">Picking</option>
      <option value="Packing">Packing</option>
      <option value="Shipping">Shipping</option>
      <option value="Delivered">Delivered</option>
      <option value="Completed">Completed</option>
      <option value="Cancelled">Cancelled</option>
    </select>

    <div class="store-orders__date-filter">
      <input type="date" id="startDate">
      <span>to</span>
      <input type="date" id="endDate">
    </div>

    <div class="store-orders__search">
      <input type="text" id="orderSearch" placeholder="Search by Order ID">
    </div>
  </div>

  <div class="store-orders__table" id="orderTableContainer">
    <?php include 'order_table.php'; ?>
  </div>
</div>

<div id="orderDetailsModal" class="store-modal">
  <div class="store-modal__content">
    <span class="store-modal__close">&times;</span>
    <div id="orderDetailsContent"></div>
  </div>
</div>

<script src="/views/stores/assets/js/order.js"></script>