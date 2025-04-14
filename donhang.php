<?php
session_start();
require_once('./db/conn.php');

// Kiểm tra nếu không có đơn hàng trong session hoặc không có sản phẩm trong giỏ hàng
if (!isset($_SESSION['temp_order']) || empty($_SESSION['temp_order']['cart'])) {
    header('Location: index.php');
    exit;
}

$order = $_SESSION['temp_order'];
$cart = $order['cart'];
$firstname = $order['firstname'];
$lastname = $order['lastname'];
$phone = $order['phone'];
$email = $order['email'];
$address = $order['address'];
$user_id = isset($order['user_id']) ? $order['user_id'] : null; // Nếu có thông tin user_id

// Tạo đơn hàng trong bảng `orders` (không có cột qty)
$sqli = "INSERT INTO orders (user_id, firstname, lastname, address, phone, email, status, created_at, updated_at)
         VALUES ('$user_id', '$firstname', '$lastname', '$address', '$phone', '$email', 'Processing', now(), now())";

if (mysqli_query($conn, $sqli)) {
    // Lấy ID của đơn hàng vừa tạo
    $last_order_id = mysqli_insert_id($conn);

    // Thêm chi tiết sản phẩm vào bảng `order_details` (có cột qty)
    foreach ($cart as $item) {
        $masp = $item['id'];
        $disscounted_price = $item['disscounted_price'];
        $qty = $item['qty'];
        $total = $disscounted_price * $qty;

        // Câu lệnh SQL để thêm chi tiết sản phẩm vào bảng `order_details`
        $sqli2 = "INSERT INTO order_details (order_id, product_id, price, qty, total, created_at, updated_at)
                  VALUES ($last_order_id, $masp, $disscounted_price, $qty, $total, now(), now())";
        mysqli_query($conn, $sqli2);
    }

    // Xóa giỏ hàng và đơn hàng tạm thời
    unset($_SESSION['cart']);
    unset($_SESSION['temp_order']);

    // Chuyển hướng đến trang cảm ơn
    header("Location: camon.php");
    exit;
} else {
    // Hiển thị thông báo lỗi nếu không thể lưu đơn hàng
    echo "Lỗi khi lưu đơn hàng! Vui lòng thử lại.";
}
?>