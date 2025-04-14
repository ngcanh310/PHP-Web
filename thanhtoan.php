<?php
session_start();
$is_homepage = false;

$cart = [];
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}

require_once('./db/conn.php');

if (isset($_POST['btDathang'])) {
    // Lưu thông tin người dùng và giỏ hàng vào SESSION
    $_SESSION['temp_order'] = [
        'firstname' => $_POST['firstname'],
        'lastname' => $_POST['lastname'],
        'phone' => $_POST['phone'],
        'email' => $_POST['email'],
        'address' => $_POST['address'],
        'cart' => $_SESSION['cart'] ?? []
    ];

    // Chuyển sang trang chọn phương thức thanh toán
    header("Location: phuongthuc.php");
    exit;
}



require_once('components/header.php');
?>
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Thanh toán</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Home</a>
                        <span>Thanh toán</span>
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
            <h4>Thông tin Khách hàng</h4>
            <form action="#" method="post" id="checkoutForm">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Họ & tên lót<span>*</span></p>
                                    <input type="text" name="firstname" id="firstname" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Tên<span>*</span></p>
                                    <input type="text" name="lastname" id="lastname" required>
                                </div>
                            </div>
                        </div>

                        <div class="checkout__input">
                            <p>Địa chỉ nhận hàng:<span>*</span></p>
                            <input type="text" placeholder="Địa chỉ" class="checkout__input__add" name="address"
                                id="address" required>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Số điện thoại:<span>*</span></p>
                                    <input type="tel" name="phone" id="phone" required pattern="[0-9]{10,11}"
                                        title="Vui lòng nhập số điện thoại hợp lệ (10-11 chữ số)">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Email:</p>
                                    <input type="email" name="email" id="email">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="checkout__order">
                            <h4>Đơn hàng</h4>
                            <div class="checkout__order__products">Sản phẩm <span>Thành tiền</span></div>
                            <ul>
                                <?php
                                $cart = $_SESSION['cart'] ?? [];
                                $total = 0;
                                foreach ($cart as $item) {
                                    $total += $item['qty'] * $item['disscounted_price'];
                                    ?>
                                    <li>
                                        <?= htmlspecialchars($item['name']) ?> <span>
                                            <?= number_format($item['disscounted_price'] * $item['qty'], 0, '', '.') . " VNĐ" ?>
                                        </span>
                                    </li>
                                <?php } ?>
                            </ul>

                            <div class="checkout__order__total">Tổng tiền: <span>
                                    <?= number_format($total, 0, '', '.') . " VNĐ" ?>
                                </span>
                            </div>

                            <!-- Cảnh báo nếu giỏ hàng trống -->
                            <?php if ($total == 0): ?>
                                <p style="color: red; margin-top: 10px;">Bạn chưa có sản phẩm nào trong giỏ hàng!</p>
                            <?php endif; ?>

                            <!-- Nút Đặt hàng bị disable nếu giỏ hàng trống -->
                            <button type="submit" class="site-btn" name="btDathang" id="submitBtn" <?= ($total == 0) ? 'disabled' : '' ?>>Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                const firstname = document.getElementById('firstname');
                const lastname = document.getElementById('lastname');
                const address = document.getElementById('address');
                const phone = document.getElementById('phone');
                const submitBtn = document.getElementById('submitBtn');

                function validateForm() {
                    const isFirstNameValid = firstname.value.trim() !== "";
                    const isLastNameValid = lastname.value.trim() !== "";
                    const isAddressValid = address.value.trim() !== "";
                    const isPhoneValid = /^[0-9]{10,11}$/.test(phone.value);

                    // Nếu nút đã bị disable từ PHP vì giỏ hàng trống, không bật lại
                    if (submitBtn.hasAttribute('disabled') && submitBtn.getAttribute('disabled') === 'disabled') return;

                    // Chỉ bật nếu các trường hợp lệ
                    submitBtn.disabled = !(isFirstNameValid && isLastNameValid && isAddressValid && isPhoneValid);
                }

                [firstname, lastname, address, phone].forEach(input => {
                    input.addEventListener('input', validateForm);
                });

                validateForm(); // Kiểm tra ban đầu khi trang tải
            </script>


        </div>
    </div>
</section>
<!-- Checkout Section End -->

<!-- Footer Section Begin -->
<?php

require_once('components/footer.php');
?>