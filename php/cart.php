<?php
session_start();
$is_homepage = false;
require_once('../db/conn.php');
require_once('../components/header.php');
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="../img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Giỏ hàng</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Home</a>
                        <span>Giỏ hàng</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Checkout Section Begin -->
<section class="checkout spad">
    <div class="container">
        <div class="checkout__form">
            <h4>Giỏ hàng</h4>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="checkout__order">
                        <h4>Thông tin giỏ hàng</h4>
                        <?php
                        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
                        $count = 0;
                        $total = 0;

                        if (empty($cart)) {
                            echo "<p style='color: red; font-weight: bold;'>Chưa có sản phẩm nào trong giỏ hàng!</p>";
                        } else {
                            ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th colspan='2'>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $item):
                                        $count++;
                                        $thanhtien = $item['qty'] * $item['disscounted_price'];
                                        $total += $thanhtien;
                                        ?>
                                        <form action="updatecart.php?id=<?= $item['id'] ?>" method="post">
                                            <tr>
                                                <td><?= $count ?></td>
                                                <td><?= $item['name'] ?></td>
                                                <td><?= number_format($item['disscounted_price'], 0, '', '.') ?> VNĐ</td>
                                                <td>
                                                    <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1"
                                                        class="form-control" style="width:80px;">
                                                </td>
                                                <td><?= number_format($thanhtien, 0, '', '.') ?> VNĐ</td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm">Cập nhật</button>
                                                </td>
                                                <td>
                                                    <a href='deletecart.php?id=<?= $item['id'] ?>'
                                                        class="btn btn-danger btn-sm">Xóa</a>
                                                </td>
                                            </tr>
                                        </form>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <div class="checkout__order__total mb-3">
                                <strong>Tổng tiền:</strong> <span><?= number_format($total, 0, '', '.') ?> VNĐ</span>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="shop.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                                <a href="thanhtoan.php" class="btn btn-success">Thanh toán</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Checkout Section End -->

<?php require_once('../components/footer.php'); ?>