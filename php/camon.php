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
                    <h2>Hoàn thành</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Trang chủ</a>
                        <span>Hoàn thành</span>
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
        <div class="checkout__form text-center">
            <h4 class="mb-4">🎉 Cảm ơn bạn đã đặt hàng!</h4>
            <p>Chúng tôi đã nhận được đơn hàng của bạn và sẽ liên hệ trong thời gian sớm nhất để xác nhận.</p>
            <a href="index.php" class="site-btn mt-4">Tiếp tục mua sắm</a>

        </div>
    </div>
</section>
<!-- Checkout Section End -->

<?php require_once('../components/footer.php'); ?>