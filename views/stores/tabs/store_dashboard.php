<?php
session_start();
if (!isset($_SESSION['store_id'])) {
  header("Location: ../auth/login.php");
  exit();
}
?>

<div class="store-dashboard">
  <div class="store-dashboard__header">
    <h1 class="store-dashboard__title">Dashboard</h1>
    <select class="store-dashboard__filter-date">
      <option value="today">Today</option>
      <option value="week">This Week</option>
      <option value="month" selected>This Month</option>
      <option value="year">This Year</option>
    </select>
  </div>

  <div class="store-dashboard__stats">
    <div class="store-dashboard__stat-card">
      <div class="store-dashboard__stat-icon">
        <i class="fa-solid fa-gamepad"></i>
      </div>
      <div class="store-dashboard__stat-info">
        <h3>Total Games</h3>
        <div class="store-dashboard__stat-value" data-stat="games">0</div>
      </div>
    </div>

    <div class="store-dashboard__stat-card">
      <div class="store-dashboard__stat-icon">
        <i class="fa-solid fa-layer-group"></i>
      </div>
      <div class="store-dashboard__stat-info">
        <h3>Total Sets</h3>
        <div class="store-dashboard__stat-value" data-stat="sets">0</div>
      </div>
    </div>

    <div class="store-dashboard__stat-card">
      <div class="store-dashboard__stat-icon">
        <i class="fa-solid fa-credit-card"></i>
      </div>
      <div class="store-dashboard__stat-info">
        <h3>Total Cards</h3>
        <div class="store-dashboard__stat-value" data-stat="cards">0</div>
      </div>
    </div>

    <div class="store-dashboard__stat-card">
      <div class="store-dashboard__stat-icon">
        <i class="fa-solid fa-shopping-cart"></i>
      </div>
      <div class="store-dashboard__stat-info">
        <h3>Total Orders</h3>
        <div class="store-dashboard__stat-value" data-stat="orders">0</div>
      </div>
    </div>

    <div class="store-dashboard__stat-card">
      <div class="store-dashboard__stat-icon">
        <i class="fa-solid fa-dollar-sign"></i>
      </div>
      <div class="store-dashboard__stat-info">
        <h3>Total Earnings</h3>
        <div class="store-dashboard__stat-value" data-stat="earnings">$0.00</div>
      </div>
    </div>
  </div>

  <div class="store-dashboard__content">
    <div class="store-dashboard__charts">
      <div class="store-dashboard__chart-card">
        <h2>Revenue Overview</h2>
        <div class="store-dashboard__chart-container">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>

    <div class="store-dashboard__tables">
      <div class="store-dashboard__top-expensive">
        <h2>Top 5 Most Expensive Cards</h2>
        <div class="store-dashboard__table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Card Name</th>
                <th>Game</th>
                <th>Set</th>
                <th>Price</th>
                <th>Quantity</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      <div class="store-dashboard__top-quantity">
        <h2>Top 5 Cards by Quantity</h2>
        <div class="store-dashboard__table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Card Name</th>
                <th>Game</th>
                <th>Set</th>
                <th>Price</th>
                <th>Quantity</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      <div class="store-dashboard__top-selling">
        <h2>Top 5 Best Selling Cards</h2>
        <div class="store-dashboard__table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Card Name</th>
                <th>Game</th>
                <th>Set</th>
                <th>Price</th>
                <th>Total Sold</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>

      <div class="store-dashboard__top-customers">
        <h2>Top 5 Customers</h2>
        <div class="store-dashboard__table-wrapper">
          <table>
            <thead>
              <tr>
                <th>Customer Name</th>
                <th>Total Orders</th>
                <th>Items Purchased</th>
                <th>Total Purchase</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>