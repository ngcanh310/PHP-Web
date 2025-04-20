<?php
session_start();
$is_homepage = false;
require_once('components/header.php');
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Organi Shop</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Home</a>
                        <span>Đơn hàng của bạn</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->
<style>
    .order-section {
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
        background-color: #f7f7f7;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: Arial, sans-serif;
    }

    .order-section h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
    }

    .order-table th,
    .order-table td {
        padding: 12px 15px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .order-table th {
        background-color: #3498db;
        color: #fff;
        font-weight: bold;
    }

    .order-table tr:hover {
        background-color: #f1f1f1;
    }

    .order-empty {
        text-align: center;
        font-style: italic;
        color: #888;
        margin-top: 20px;
    }
</style>

<section class="order-section">
    <?php
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!isset($_SESSION['user'])): ?>
        <h2 class="order-empty">Bạn cần <a href="login.php">đăng nhập</a> để xem đơn hàng của bạn.</h2>
    <?php else:
        // Nếu đã đăng nhập, lấy id người dùng từ session
        $user_id = $_SESSION['user']['id'];
        $sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
        $result = $conn->query($sql);

        // Hiển thị đơn hàng nếu có
        if ($result->num_rows > 0): ?>
            <h2>🧾 Danh sách đơn hàng của bạn</h2>
            <table class="order-table">
                <tr>
                    <th>ID đơn hàng</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Ngày cập nhật</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['created_at'])); ?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['updated_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <h2 class="order-empty">🛒 Bạn chưa có đơn hàng nào.</h2>
        <?php endif;
    endif;
    ?>
</section>


<?php

require_once('components/footer.php');
?>