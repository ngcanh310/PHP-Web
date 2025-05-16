<?php
require('includes/header.php');
require('../db/conn.php');
?>

<body>
    <h2>Thống kê doanh thu sản phẩm theo ID</h2>
    <form method="GET" class="mb-4">
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

        // Phân trang
        $limit = 5;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        // Đếm tổng số dòng
        $count_sql = "SELECT COUNT(*) as total FROM order_details od 
                      JOIN orders o ON od.order_id = o.id 
                      WHERE od.product_id = $product_id 
                      AND o.created_at BETWEEN '$start_date' AND '$end_date'";
        $count_result = mysqli_query($conn, $count_sql);
        $total_rows = mysqli_fetch_assoc($count_result)['total'];
        $total_pages = ceil($total_rows / $limit);

        // Lấy dữ liệu phân trang
        $sql = "SELECT od.*, o.created_at 
                FROM order_details od 
                JOIN orders o ON od.order_id = o.id 
                WHERE od.product_id = $product_id 
                AND o.created_at BETWEEN '$start_date' AND '$end_date' 
                ORDER BY o.created_at DESC
                LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $sql);

        // Lấy thông tin sản phẩm
        $sql_product = "SELECT 
                            p.id AS product_id, 
                            p.name AS product_name, 
                            p.stock, 
                            b.name AS category_name 
                        FROM products p 
                        JOIN categories b ON p.category_id = b.id 
                        WHERE p.id = $product_id";
        $result_product = mysqli_query($conn, $sql_product);
        $product_info = mysqli_fetch_assoc($result_product);

        if ($product_info): ?>
            <div class="card mt-4 mb-4">
                <div class="card-header bg-info text-white">
                    Thông tin sản phẩm
                </div>
                <div class="card-body">
                    <p><strong>ID sản phẩm:</strong> <?= $product_info['product_id'] ?></p>
                    <p><strong>Tên sản phẩm:</strong> <?= $product_info['product_name'] ?></p>
                    <p><strong>Danh mục:</strong> <?= $product_info['category_name'] ?></p>
                    <p><strong>Số lượng tồn kho:</strong> <?= $product_info['stock'] ?></p>
                </div>
            </div>
        <?php endif;

        echo "<h4>Kết quả thống kê sản phẩm ID: $product_id</h4>";
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1' cellpadding='6' class='table table-striped table-bordered'>";
            echo "<thead class='table-light'>
                    <tr>
                        <th>Ngày mua</th>
                        <th>Giá lúc mua</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                    </tr>
                  </thead><tbody>";

            $total = 0;

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . date("d-m-Y H:i", strtotime($row['created_at'])) . "</td>
                        <td>" . number_format($row['price'], 0, '', '.') . " VNĐ</td>
                        <td>" . $row['qty'] . "</td>
                        <td>" . number_format($row['total'], 0, '', '.') . " VNĐ</td>
                      </tr>";
                $total += $row['total'];
            }

            echo "<tr class='table-secondary'>
                    <td colspan='3'><strong>Tổng doanh thu</strong></td>
                    <td><strong>" . number_format($total, 0, '', '.') . " VNĐ</strong></td>
                  </tr>";
            echo "</tbody></table>";

            // PHÂN TRANG
            if ($total_pages > 1) {
                echo '<nav aria-label="Page navigation"><ul class="pagination justify-content-center mt-3">';
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active = $i == $page ? 'active' : '';
                    echo "<li class='page-item $active'><a class='page-link' href='?product_id=$product_id&start_date=" . $_GET['start_date'] . "&end_date=" . $_GET['end_date'] . "&page=$i'>$i</a></li>";
                }
                echo '</ul></nav>';
            }
        } else {
            echo "<p>Không có dữ liệu trong khoảng thời gian đã chọn.</p>";
        }
    }
    ?>

    <style>
        form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            text-align: center;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 6px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-weight: bold;
        }

        .card-body p {
            margin: 6px 0;
        }

        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
    </style>

    <?php require('includes/footer.php'); ?>