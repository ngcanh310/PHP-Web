<?php

session_start();
$errors = [];
$success = '';
require('./db/conn.php');



// Đăng ký
if (isset($_POST['register'])) {
    $name = trim($_POST['reg_name']);
    $email = trim($_POST['reg_email']);
    $password = trim($_POST['reg_password']);
    $phone = trim($_POST['reg_phone']);
    $address = trim($_POST['reg_address']);

    if ($name == '' || $email == '' || $password == '' || $phone == '' || $address == '') {
        $errors[] = "Vui lòng điền đầy đủ thông tin.";
    } else {
        $check = $conn->query("SELECT * FROM users WHERE email = '$email'");
        if ($check->num_rows > 0) {
            $errors[] = "Email đã được sử dụng.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, address, status, created_at, updated_at)
                                    VALUES (?, ?, ?, ?, ?, 'Active', NOW(), NOW())");
            $stmt->bind_param("sssss", $name, $email, $hashed, $phone, $address);
            $stmt->execute();
            $success = "Đăng ký thành công! Bạn có thể đăng nhập.";
        }
    }
}

// Đăng nhập
if (isset($_POST['login'])) {
    $email = trim($_POST['login_email']);
    $password = trim($_POST['login_password']);

    $result = $conn->query("SELECT * FROM users WHERE email = '$email' AND status = 'Active' LIMIT 1");
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) { // bắt đầu session mới sạch sẽ

            $_SESSION['user'] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email']
            ];
            header("Location: index.php");
            exit;
        } else {
            $errors[] = "Mật khẩu không đúng.";
        }
    } else {
        $errors[] = "Email không tồn tại hoặc tài khoản bị khóa.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng nhập / Đăng ký</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            padding-top: 40px;
        }

        .form-box {
            background: #fff;
            padding: 25px;
            width: 400px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }

        hr {
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="form-box">
        <h2>Đăng nhập</h2>
        <?php foreach ($errors as $e)
            echo "<div class='error'>$e</div>"; ?>
        <?php if ($success)
            echo "<div class='success'>$success</div>"; ?>
        <form method="post">
            <input type="email" name="login_email" placeholder="Email" required>
            <input type="password" name="login_password" placeholder="Mật khẩu" required>
            <input type="submit" name="login" value="Đăng nhập">
        </form>

        <hr>
        <h2>Đăng ký</h2>
        <form method="post">
            <input type="text" name="reg_name" placeholder="Họ tên" required>
            <input type="email" name="reg_email" placeholder="Email" required>
            <input type="password" name="reg_password" placeholder="Mật khẩu" required>
            <input type="text" name="reg_phone" placeholder="Số điện thoại" required>
            <textarea name="reg_address" placeholder="Địa chỉ" required></textarea>
            <input type="submit" name="register" value="Đăng ký">
        </form>
    </div>
</body>

</html>