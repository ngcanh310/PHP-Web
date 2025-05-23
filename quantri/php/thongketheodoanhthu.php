<?php
require('../includes/header.php');
require('../../db/conn.php');

// Lấy tháng được chọn từ GET, mặc định là tháng hiện tại
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// Tổng doanh thu
$sql_total_revenue = "
    SELECT SUM(od.total) as total_revenue
    FROM order_details od
    JOIN orders o ON od.order_id = o.id
    WHERE DATE_FORMAT(o.created_at, '%Y-%m') = '$selected_month'
";
$total_revenue_result = mysqli_query($conn, $sql_total_revenue);
$total_revenue = mysqli_fetch_assoc($total_revenue_result)['total_revenue'] ?? 0;

// Tổng sản phẩm bán ra
$sql_total_qty = "
    SELECT SUM(od.qty) as total_qty
    FROM order_details od
    JOIN orders o ON od.order_id = o.id
    WHERE DATE_FORMAT(o.created_at, '%Y-%m') = '$selected_month'
";
$total_qty_result = mysqli_query($conn, $sql_total_qty);
$total_qty = mysqli_fetch_assoc($total_qty_result)['total_qty'] ?? 0;

// Truy vấn top 3 sản phẩm theo doanh thu
$sql_revenue = "
    SELECT p.name, SUM(od.total) as total_revenue
    FROM order_details od
    JOIN products p ON od.product_id = p.id
    JOIN orders o ON od.order_id = o.id
    WHERE DATE_FORMAT(o.created_at, '%Y-%m') = '$selected_month'
    GROUP BY od.product_id
    ORDER BY total_revenue DESC
    LIMIT 3
";
$result_revenue = mysqli_query($conn, $sql_revenue);

// Truy vấn top 3 sản phẩm theo số lượng
$sql_quantity = "
    SELECT p.name, SUM(od.qty) as total_qty
    FROM order_details od
    JOIN products p ON od.product_id = p.id
    JOIN orders o ON od.order_id = o.id
    WHERE DATE_FORMAT(o.created_at, '%Y-%m') = '$selected_month'
    GROUP BY od.product_id
    ORDER BY total_qty DESC
    LIMIT 3
";
$result_quantity = mysqli_query($conn, $sql_quantity);

// Chuyển dữ liệu sang mảng cho JavaScript
$revenue_data = $quantity_data = [];

while ($row = mysqli_fetch_assoc($result_revenue)) {
    $revenue_data[] = [
        'name' => $row['name'],
        'value' => $row['total_revenue']
    ];
}

while ($row = mysqli_fetch_assoc($result_quantity)) {
    $quantity_data[] = [
        'name' => $row['name'],
        'value' => $row['total_qty']
    ];
}
?>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Tổng doanh thu</h5>
                <p class="card-text fs-4"><?= number_format($total_revenue) ?> VNĐ</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Tổng sản phẩm đã bán</h5>
                <p class="card-text fs-4"><?= number_format($total_qty) ?> sản phẩm</p>
            </div>
        </div>
    </div>
</div>


<div class="container mt-4">
    <h3 class="mb-4">Thống kê sản phẩm bán chạy nhất theo tháng</h3>

    <form method="GET" class="mb-4">
        <label for="month">Chọn tháng:</label>
        <input type="month" name="month" value="<?= $selected_month ?>" required>
        <button type="submit" class="btn btn-primary btn-sm">Xem</button>
    </form>

    <div class="row">
        <div class="col-md-6 mb-4">
            <h5 class="text-center">Top 3 sản phẩm theo doanh thu</h5>
            <canvas id="revenueChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <h5 class="text-center">Top 3 sản phẩm theo số lượng</h5>
            <canvas id="quantityChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const revenueData = <?= json_encode($revenue_data) ?>;
    const quantityData = <?= json_encode($quantity_data) ?>;

    const revenueChart = new Chart(document.getElementById('revenueChart'), {
        type: 'bar',
        data: {
            labels: revenueData.map(item => item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name),

            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: revenueData.map(item => item.value),
                backgroundColor: ['#4CAF50', '#2196F3', '#FF9800']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value.toLocaleString() + ' VNĐ'
                    }
                }
            }
        }
    });

    const quantityChart = new Chart(document.getElementById('quantityChart'), {
        type: 'bar',
        data: {
            labels: revenueData.map(item => item.name.length > 15 ? item.name.substring(0, 15) + '...' : item.name),
            datasets: [{
                label: 'Số lượng bán',
                data: quantityData.map(item => item.value),
                backgroundColor: ['#E91E63', '#3F51B5', '#FFC107']
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>

<?php require('../includes/footer.php'); ?>