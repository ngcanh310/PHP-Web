<?php
session_start();
$is_homepage = false;

$cart = [];
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}

require_once('./db/conn.php');

if (isset($_POST['btDathang'])) {
    if (isset($_SESSION['user'])) {
        // Lấy thông tin khách hàng nếu đã đăng nhập
        $id = $_SESSION['user']['id'];
    } else {
        // Nếu chưa đăng nhập, set id = 0 (khách hàng)
        $id = 0;
    }
    //lay thong tin khach hang tu form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    //tao du lieu cho order
    $sqli = "insert into orders values (0, $id, '$firstname', '$lastname', '$address', '$phone', '$email', 'Processing', now(), now())";
    // echo $sqli;
    //exit; // mysqli_query($conn, $sqli);
    // cap nhat kho hang
    // Lấy số lượng hiện tại trong kho
    for ($i = 0; $i < count($cart); $i++) {
        // print_r($cart[$i]);
        if ($cart[$i]['id'] == $id) {
            $cart[$i]['qty'] += $qty;
            $isFound = true;
            break;
        }
    }
    //lay id vua duoc them vao 
    if (mysqli_query($conn, $sqli)) {
        $last_order_id = mysqli_insert_id($conn);
        //sau do them vao orer detail
        foreach ($cart as $item) {
            $masp = $item['id'];
            $disscounted_price = $item['disscounted_price'];
            $qty = $item['qty'];
            $total = $item['qty'] * $item['disscounted_price'];
            $sqli2 = "insert into order_details values 
            (0, $last_order_id, $masp,  $disscounted_price, $qty, $total, now(), now())";
            // echo $sqli2, exit;
            mysqli_query($conn, $sqli2);

            // Trừ số lượng trong kho
            $sqli3 = "UPDATE products SET stock = stock - $qty WHERE id = $masp";
            mysqli_query($conn, $sqli3);
        }
    }

    //xoa cart
    unset($_SESSION["cart"]);
    header("Location: camon.php");

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
            <form action="#" method="post" id="checkout-form">
                <div class="row">
                    <div class="col-lg-8 col-md-6">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Họ & tên lót<span>*</span></p>
                                    <input type="text" name='firstname' required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Tên<span>*</span></p>
                                    <input type="text" name='lastname' required>
                                </div>
                            </div>
                        </div>

                        <div class="checkout__input">
                            <p>Địa chỉ nhận hàng:<span>*</span></p>
                            <input type="text" placeholder="Địa chỉ" class="checkout__input__add" name="address"
                                required>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Số điện thoại:<span>*</span></p>
                                    <input type="text" name="phone" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="checkout__input">
                                    <p>Email:</p>
                                    <input type="email" name="email">
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
                                $total = 0;
                                foreach ($cart as $item):
                                    $subtotal = $item['qty'] * $item['disscounted_price'];
                                    $total += $subtotal;
                                    ?>
                                    <li><?= $item['name'] ?> <span><?= number_format($subtotal, 0, '', '.') ?> VNĐ</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="checkout__order__total">Tổng tiền:
                                <span><?= number_format($total, 0, '', '.') ?> VNĐ</span>
                            </div>

                            <!-- Thêm phương thức thanh toán -->
                            <div class="checkout__input">
                                <p>Phương thức thanh toán:<span>*</span></p>
                                <div class="payment-options">
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="momo">
                                        <span>Thanh toán bằng Momo</span>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="vnpay">
                                        <span>Thanh toán qua VNPay</span>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="cod">
                                        <span>Thanh toán khi nhận hàng (COD)</span>
                                    </label>
                                    <label class="payment-option">
                                        <input type="radio" name="payment_method" value="paypal">
                                        <span>Thanh toán qua PayPal</span>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="site-btn" name="btDathang" id="submit-btn" disabled>Đặt
                                hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Checkout Section End -->

<style>
    .payment-options {
        display: flex;
        flex-direction: row;
        gap: 5px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .payment-option {
        border: 1px solid #ccc;
        padding: 10px 14px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        gap: 6px;
        background-color: #fff;
        font-size: 14px;
    }

    .payment-option:hover {
        background-color: #f0f0f0;
        border-color: #888;
    }

    .payment-option input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 14px;
        height: 14px;
        border: 2px solid #7fad39;
        border-radius: 50%;
        /* Đảm bảo hình tròn */
        background-color: #fff;
        outline: none;
        cursor: pointer;
        position: relative;
        display: inline-block;
        vertical-align: middle;
        margin: 0;
        padding: 0;
    }


    .payment-option input[type="radio"]::before {
        content: "";
        width: 8px;
        height: 8px;
        background-color: #7fad39;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        border-radius: 50%;
        transition: transform 0.2s ease-in-out;
    }

    .payment-option input[type="radio"]:checked::before {
        transform: translate(-50%, -50%) scale(1);
    }
</style>


<script>
    // Kích hoạt nút đặt hàng nếu đã điền đủ thông tin
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('submit-btn');

    function checkFormValid() {
        const firstname = form.firstname.value.trim();
        const lastname = form.lastname.value.trim();
        const phone = form.phone.value.trim();
        const address = form.address.value.trim();
        const payment = form.querySelector('input[name=\"payment_method\"]:checked');

        submitBtn.disabled = !(firstname && lastname && phone && address && payment);
    }

    form.addEventListener('input', checkFormValid);
    form.addEventListener('change', checkFormValid);
</script>

<?php require_once('components/footer.php'); ?>