<?php
session_start();
require_once '../includes/db_connect.php';

// Lấy thống kê doanh thu
$stmt = $conn->prepare("
    SELECT 
        COUNT(DISTINCT CASE WHEN o.status != 'Cancelled' THEN o.id END) as completed_orders,
        COUNT(DISTINCT CASE WHEN o.status = 'Cancelled' THEN o.id END) as cancelled_orders,
        SUM(CASE WHEN o.status != 'Cancelled' THEN o.total_amount - o.shipping_fee ELSE 0 END) as total_revenue,
        SUM(CASE WHEN o.status != 'Cancelled' THEN o.shipping_fee ELSE 0 END) as total_shipping,
        SUM(CASE 
            WHEN o.status != 'Cancelled' THEN (o.total_amount - o.shipping_fee) * 0.1 
            ELSE 0 
        END) as admin_commission,
        SUM(CASE 
            WHEN o.status != 'Cancelled' THEN (o.total_amount - o.shipping_fee) * 0.9 
            ELSE 0 
        END) as store_earnings
    FROM orders o
");
$stmt->execute();
$revenue = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy thống kê theo store
$stmt = $conn->prepare("
    SELECT 
        s.name as store_name,
        COUNT(DISTINCT CASE WHEN o.status != 'Cancelled' THEN o.id END) as completed_orders,
        COUNT(DISTINCT CASE WHEN o.status = 'Cancelled' THEN o.id END) as cancelled_orders,
        SUM(CASE WHEN o.status != 'Cancelled' THEN oi.price * oi.quantity ELSE 0 END) as total_sales,
        SUM(CASE 
            WHEN o.status != 'Cancelled' THEN 
                CASE 
                    WHEN oi.id = (
                        SELECT MIN(oi2.id) 
                        FROM order_items oi2 
                        WHERE oi2.order_id = o.id 
                        AND oi2.store_id = s.id
                    ) THEN 
                        (SELECT cl.shipping 
                        FROM card_listings cl 
                        WHERE cl.id = oi.card_listing_id)
                    ELSE 0 
                END
            ELSE 0 
        END) as total_shipping,
        SUM(CASE 
            WHEN o.status != 'Cancelled' THEN (oi.price * oi.quantity) * 0.9 
            ELSE 0 
        END) as store_earnings
    FROM stores s
    LEFT JOIN order_items oi ON s.id = oi.store_id
    LEFT JOIN orders o ON oi.order_id = o.id
    GROUP BY s.id, s.name
    ORDER BY store_earnings DESC
");
$stmt->execute();
$store_stats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<style>
  .revenue {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
    font-family: Arial, sans-serif;
  }

  .revenue__header {
    margin-bottom: 30px;
    text-align: center;
  }

  .revenue__header h1 {
    color: #333;
    font-size: 28px;
  }

  .revenue__overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
  }

  .revenue__card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.2s;
  }

  .revenue__card:hover {
    transform: translateY(-5px);
  }

  .revenue__card h3 {
    color: #666;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: 500;
  }

  .revenue__card p {
    color: #2c3e50;
    font-size: 24px;
    font-weight: bold;
    margin: 0;
  }

  .revenue__stores {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .revenue__stores h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 22px;
  }

  .revenue__table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
  }

  .revenue__table th,
  .revenue__table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
  }

  .revenue__table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #333;
    white-space: nowrap;
  }

  .revenue__table tr:hover {
    background-color: #f8f9fa;
  }

  .revenue__table td {
    color: #666;
  }

  /* Responsive styles */
  @media (max-width: 768px) {
    .revenue {
      padding: 15px;
    }

    .revenue__overview {
      grid-template-columns: repeat(2, 1fr);
      gap: 15px;
    }

    .revenue__card {
      padding: 15px;
    }

    .revenue__card h3 {
      font-size: 14px;
    }

    .revenue__card p {
      font-size: 20px;
    }

    .revenue__table {
      display: block;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    .revenue__table th,
    .revenue__table td {
      padding: 12px;
      font-size: 14px;
    }
  }

  @media (max-width: 480px) {
    .revenue__overview {
      grid-template-columns: 1fr;
    }

    .revenue__header h1 {
      font-size: 24px;
    }

    .revenue__stores h2 {
      font-size: 20px;
    }
  }
</style>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Revenue Statistics</title>
  <link rel="stylesheet" href="/views/admin/assets/css/revenue.css">
</head>

<body>
  <div class="revenue">
    <div class="revenue__header">
      <h1>Revenue Statistics</h1>
    </div>

    <div class="revenue__overview">
      <div class="revenue__card">
        <h3>Completed Orders</h3>
        <p><?php echo number_format($revenue['completed_orders']); ?></p>
      </div>
      <div class="revenue__card">
        <h3>Cancelled Orders</h3>
        <p><?php echo number_format($revenue['cancelled_orders']); ?></p>
      </div>
      <div class="revenue__card">
        <h3>Total Revenue</h3>
        <p>$<?php echo number_format($revenue['total_revenue'], 2); ?></p>
      </div>
      <div class="revenue__card">
        <h3>Total Shipping</h3>
        <p>$<?php echo number_format($revenue['total_shipping'], 2); ?></p>
      </div>
      <div class="revenue__card">
        <h3>Admin Commission (10%)</h3>
        <p>$<?php echo number_format($revenue['admin_commission'], 2); ?></p>
      </div>
      <div class="revenue__card">
        <h3>Store Earnings (90%)</h3>
        <p>$<?php echo number_format($revenue['store_earnings'], 2); ?></p>
      </div>
    </div>

    <div class="revenue__stores">
      <h2>Store Statistics</h2>
      <table class="revenue__table">
        <thead>
          <tr>
            <th>Store Name</th>
            <th>Completed Orders</th>
            <th>Cancelled Orders</th>
            <th>Total Sales</th>
            <th>Total Shipping</th>
            <th>Store Earnings (90%)</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($store_stats as $store): ?>
            <tr>
              <td><?php echo htmlspecialchars($store['store_name']); ?></td>
              <td><?php echo number_format($store['completed_orders']); ?></td>
              <td><?php echo number_format($store['cancelled_orders']); ?></td>
              <td>$<?php echo number_format($store['total_sales'], 2); ?></td>
              <td>$<?php echo number_format($store['total_shipping'], 2); ?></td>
              <td>$<?php echo number_format($store['store_earnings'], 2); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>

</html>