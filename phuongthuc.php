<?php
session_start();
require_once('./db/conn.php');
$is_homepage = false;
require_once('components/header.php');
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Chọn phương thức thanh toán</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.html">Home</a>
                        <span>Phương thức thanh toán</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Payment Method Section Begin -->
<section class="payment-method-section spad">
    <div class="container">
        <form method="POST" action="donhang.php" class="payment-method-form">
            <h4>Chọn phương thức thanh toán</h4>

            <div class="checkout__input">
                <div class="payment-option">
                    <input type="radio" id="cash" name="payment_method" value="cash" required>
                    <label for="cash">Thanh toán khi nhận hàng</label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="bank" name="payment_method" value="bank">
                    <label for="bank">Chuyển khoản ngân hàng</label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="momo" name="payment_method" value="momo">
                    <label for="momo">Ví điện tử Momo</label>
                </div>
                <div class="payment-option">
                    <input type="radio" id="momo" name="payment_method" value="momo">
                    <label for="momo">Ví điện tử VNPay</label>
                </div>
            </div>

            <button type="submit" class="site-btn2">Xác nhận thanh toán</button>
        </form>
    </div>
</section>
<!-- Payment Method Section End -->

<style>
    .payment-method-form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #f9f9f9;
    }

    .payment-method-form h4 {
        text-align: center;
        font-size: 20px;
        margin-bottom: 20px;
        color: #333;
    }

    .checkout__input {
        margin-bottom: 20px;
    }

    .payment-option {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .payment-option input[type="radio"] {
        margin-right: 10px;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .payment-option label {
        font-size: 16px;
        color: #333;
        cursor: pointer;
    }

    .site-btn2 {
        width: 100%;
        padding: 12px;
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
    }

    .site-btn:hover {
        background-color: #45a049;
    }
</style>

<?php
require_once('components/footer.php');
?>