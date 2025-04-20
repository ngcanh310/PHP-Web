<?php
require('includes/header.php');
include "../db/conn.php";
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