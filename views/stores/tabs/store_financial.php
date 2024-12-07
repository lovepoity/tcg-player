<?php
session_start();
if (!isset($_SESSION['store_id'])) {
  header("Location: ../auth/login.php");
  exit();
}

$store_id = $_SESSION['store_id'];
?>

<div class="store-financial">
  <div class="store-financial__header">
    <h1 class="store-financial__title">Financial Report</h1>

    <select class="store-financial__filter-date">
      <option value="today">Today</option>
      <option value="week">This Week</option>
      <option value="month" selected>This Month</option>
      <option value="year">This Year</option>
    </select>
  </div>

  <div class="store-financial__stats">
    <div class="store-financial__stat-card">
      <div class="store-financial__stat-icon">
        <i class="fa-solid fa-shopping-cart"></i>
      </div>
      <div class="store-financial__stat-info">
        <h3>Total Orders</h3>
        <div class="store-financial__stat-value" data-stat="orders">0</div>
      </div>
    </div>

    <div class="store-financial__stat-card">
      <div class="store-financial__stat-icon">
        <i class="fa-solid fa-chart-line"></i>
      </div>
      <div class="store-financial__stat-info">
        <h3>Total Revenue</h3>
        <div class="store-financial__stat-value" data-stat="revenue">$0.00</div>
      </div>
    </div>

    <div class="store-financial__stat-card">
      <div class="store-financial__stat-icon">
        <i class="fa-solid fa-dollar-sign"></i>
      </div>
      <div class="store-financial__stat-info">
        <h3>Total Earnings</h3>
        <div class="store-financial__stat-value" data-stat="earnings">$0.00</div>
      </div>
    </div>

    <div class="store-financial__stat-card">
      <div class="store-financial__stat-icon">
        <i class="fa-solid fa-percent"></i>
      </div>
      <div class="store-financial__stat-info">
        <h3>Total Commission</h3>
        <div class="store-financial__stat-value" data-stat="commission">$0.00</div>
      </div>
    </div>
  </div>

  <div class="store-financial__tables">
    <div class="store-financial__recent-orders">
      <h2>Recent Orders</h2>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <!-- PHP code to populate orders -->
        </tbody>
      </table>
    </div>

    <div class="store-financial__earnings">
      <h2>Earnings History</h2>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Earning</th>
            <th>Commission</th>
          </tr>
        </thead>
        <tbody>
          <!-- PHP code to populate earnings -->
        </tbody>
      </table>
    </div>
  </div>

  <div class="store-financial__chart">
    <h2>Revenue Chart</h2>
    <div class="store-financial__chart-container">
      <canvas id="revenueChart"></canvas>
    </div>
  </div>
</div>