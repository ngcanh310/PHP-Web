<?php
session_start();

// Kiểm tra nếu không có mã OTP thì quay lại trang đăng ký
if (!isset($_SESSION['otp'])) {
    header("Location: login.php");
    exit();
}

// Xử lý xác minh OTP
if (isset($_POST['verify_otp'])) {
    $otp_input = trim($_POST['otp_input']);

    if ($otp_input == $_SESSION['otp']) {
        // ✅ OTP đúng
        unset($_SESSION['otp']); // Xóa OTP sau khi xác minh

        // Bạn có thể gắn trạng thái "đã xác minh" nếu muốn,
        // hoặc cho phép đăng nhập tại đây

        $_SESSION['otp_verified'] = true; // Đánh dấu đã xác minh OTP
        $_SESSION['otp_success'] = "✅ Mã OTP chính xác! Bạn đã xác minh thành công.";

        // Chuyển hướng đến trang đăng nhập hoặc dashboard
        header("Location: login.php");
        exit();
    } else {
        // ❌ OTP sai
        header("Location: verify_otp.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Xác minh OTP</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        .form-box {
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 10px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }

        button {
            padding: 10px 15px;
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }

        p.error {
            color: red;
        }

        p.success {
            color: green;
        }
    </style>
</head>

<body>

    <div class="form-box">
        <h2>Nhập mã OTP</h2>


        <form method="post" action="verify_otp.php">
            <label for="otp_input">Mã OTP đã gửi đến số điện thoại của bạn:</label>
            <input type="text" id="otp_input" name="otp_input" placeholder="Nhập mã OTP" required>
            <button type="submit" name="verify_otp">Xác minh</button>
        </form>
    </div>

</body>

</html>