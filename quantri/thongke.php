<?php
require('includes/header.php');
require('../db/conn.php');
?>

<body>
    <h2>Thống kê doanh thu sản phẩm theo ID</h2>
    <form method="GET">
        <label for="product_id">Nhập ID sản phẩm:</label>
        <input type="number" name="product_id" required>

        <label for="start_date">Từ ngày:</label>
        <input type="date" name="start_date" required>

        <label for="end_date">Đến ngày:</label>
        <input type="date" name="end_date" required>

        <button type="submit">Thống kê</button>
    </form>

    <hr>

    <?php
    if (isset($_GET['product_id'], $_GET['start_date'], $_GET['end_date'])) {
        $product_id = intval($_GET['product_id']);
        $start_date = $_GET['start_date'] . " 00:00:00";
        $end_date = $_GET['end_date'] . " 23:59:59";

        $sql = "SELECT od.*, o.created_at 
                FROM order_details od 
                JOIN orders o ON od.order_id = o.id 
                WHERE od.product_id = $product_id 
                  AND o.created_at BETWEEN '$start_date' AND '$end_date' 
                ORDER BY o.created_at DESC";

        $result = mysqli_query($conn, $sql);

        echo "<h3>Kết quả thống kê sản phẩm ID: $product_id</h3>";
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1' cellpadding='6'";
            echo "<tr>
                    <th>Ngày mua</th>
                    <th>Giá lúc mua</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                  </tr>";

            $total = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . $row['created_at'] . "</td>
                        <td>" . number_format($row['price'], 0, '', '.') . " VNĐ</td>
                        <td>" . $row['qty'] . "</td>
                        <td>" . number_format($row['total'], 0, '', '.') . " VNĐ</td>
                      </tr>";
                $total += $row['total'];
            }

            echo "<tr>
                    <td colspan='3'><strong>Tổng doanh thu</strong></td>
                    <td><strong>" . number_format($total, 0, '', '.') . " VNĐ</strong></td>
                  </tr>";
            echo "</table>";
        } else {
            echo "<p>Không có dữ liệu trong khoảng thời gian đã chọn.</p>";
        }
    }
    ?>
    <style>
        table {
            width: 100%;
        }
    </style>
    <?php
    require('includes/footer.php');
    ?>