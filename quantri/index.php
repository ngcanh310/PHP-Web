<?php
require('includes/header.php');
include "../db/conn.php";

// Tổng người dùng
$sql_users = "SELECT COUNT(*) AS total_users FROM users";
$result_users = mysqli_query($conn, $sql_users);
$total_users = mysqli_fetch_assoc($result_users)['total_users'];

// Tổng doanh thu
$sql_revenue = "SELECT SUM(total) AS total_revenue FROM order_details";
$result_revenue = mysqli_query($conn, $sql_revenue);
$total_revenue = mysqli_fetch_assoc($result_revenue)['total_revenue'];

// Tổng sản phẩm
$sql_products = "SELECT COUNT(*) AS total_products FROM products";
$result_products = mysqli_query($conn, $sql_products);
$total_products = mysqli_fetch_assoc($result_products)['total_products'];

// Tổng danh mục
$sql_categories = "SELECT COUNT(*) AS total_categories FROM categories";
$result_categories = mysqli_query($conn, $sql_categories);
$total_categories = mysqli_fetch_assoc($result_categories)['total_categories'];


// === Doanh thu theo tháng ===
$sql_month = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total) AS revenue
              FROM order_details
              WHERE created_at >= DATE_FORMAT(CURDATE() - INTERVAL 2 MONTH, '%Y-%m-01')
              GROUP BY month
              ORDER BY month";

$result_month = $conn->query($sql_month);

$labels_month = [];
$data_month = [];

while ($row = $result_month->fetch_assoc()) {
  $labels_month[] = $row['month'];
  $data_month[] = $row['revenue'];
}

// === Doanh thu theo ngày ===
$sql_day = "SELECT DATE(created_at) AS day, SUM(total) AS revenue
            FROM order_details
            WHERE created_at >= CURDATE() - INTERVAL 4 DAY
            GROUP BY day
            ORDER BY day";

$result_day = $conn->query($sql_day);

$labels_day = [];
$data_day = [];

while ($row = $result_day->fetch_assoc()) {
  $labels_day[] = $row['day'];
  $data_day[] = $row['revenue'];
}
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<body>
  <div class="row mt-4 text-center" style="margin-bottom: 20px;">
    <div class="col-md-3">
      <div class="card bg-primary text-white">
        <div class="card-body">
          <h5 class="card-title">👤 Người dùng</h5>
          <h3><?= $total_users ?></h3>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card bg-success text-white">
        <div class="card-body">
          <h5 class="card-title">💰 Doanh thu</h5>
          <h3><?= number_format($total_revenue, 0, '', '.') ?> VNĐ</h3>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card bg-warning text-white">
        <div class="card-body">
          <h5 class="card-title">📦 Sản phẩm</h5>
          <h3><?= $total_products ?></h3>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card bg-danger text-white">
        <div class="card-body">
          <h5 class="card-title">📂 Danh mục</h5>
          <h3><?= $total_categories ?></h3>
        </div>
      </div>
    </div>
  </div>

  <h2 style="text-align: center;">Thống kê doanh thu</h2>

  <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 30px;">
    <div style="flex: 1; min-width: 400px;">
      <h3 style="text-align: center;">📊 Doanh thu theo tháng</h3>
      <canvas id="monthlyRevenue"></canvas>
    </div>

    <div style="flex: 1; min-width: 400px;">
      <h3 style="text-align: center;">📆 Doanh thu theo ngày</h3>
      <canvas id="dailyRevenue"></canvas>
    </div>
  </div>

  <script>
    const monthlyLabels = <?php echo json_encode($labels_month); ?>;
    const monthlyData = <?php echo json_encode($data_month); ?>;
    const dailyLabels = <?php echo json_encode($labels_day); ?>;
    const dailyData = <?php echo json_encode($data_day); ?>;

    new Chart(document.getElementById('monthlyRevenue'), {
      type: 'bar',
      data: {
        labels: monthlyLabels,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: monthlyData,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: { y: { beginAtZero: true } }
      }
    });

    new Chart(document.getElementById('dailyRevenue'), {
      type: 'line',
      data: {
        labels: dailyLabels,
        datasets: [{
          label: 'Doanh thu (VNĐ)',
          data: dailyData,
          fill: false,
          borderColor: 'rgba(255, 99, 132, 1)',
          tension: 0.3
        }]
      },
      options: {
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>

</body>

<style>
  canvas {
    max-width: 600px;
    margin: 20px auto;
  }

  h2 {
    text-align: center;
  }
</style>




<?php
require('includes/footer.php');
?>