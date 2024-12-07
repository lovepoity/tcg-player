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
    <div class="store-orders__select-group">
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
    </div>

    <div class="store-orders__date-filter">
      <input type="date" id="startDate" class="store-orders__date">
      <span>to</span>
      <input type="date" id="endDate" class="store-orders__date">
      <button class="store-orders__btn store-orders__btn--search">Search</button>
    </div>

    <div class="store-orders__search">
      <input type="text" id="searchOrder" placeholder="Search by Order ID">
    </div>
  </div>

  <div class="store-orders__table" id="orderTableContainer">
    <?php include 'order_table.php'; ?>
  </div>
</div>

<div id="order-modal" class="order-modal">
  <div class="order-modal__content">
    <div id="order-modal__body"></div>
  </div>
</div>