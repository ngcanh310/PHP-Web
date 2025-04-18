<?php
require('../db/conn.php');

// Lấy dữ liệu từ form
$name = $_POST['name'];
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
$sumary = $_POST['sumary'];
$description = $_POST['description'];
$stock = $_POST['stock'];
$giagoc = $_POST['giagoc'];
$giaban = $_POST['giaban'];
$danhmuc = $_POST['danhmuc'];

// Xử lý hình ảnh
$countfiles = count($_FILES['anhs']['name']);
$imgs = '';
for ($i = 0; $i < $countfiles; $i++) {
    $filename = $_FILES['anhs']['name'][$i];
    $location = "uploads/" . uniqid() . $filename;
    $extension = strtolower(pathinfo($location, PATHINFO_EXTENSION));
    $valid_extensions = array("jpg", "jpeg", "png");

    if (in_array($extension, $valid_extensions)) {
        if (move_uploaded_file($_FILES['anhs']['tmp_name'][$i], $location)) {
            $imgs .= $location . ";";
        }
    }
}
$imgs = rtrim($imgs, ";");

// Câu lệnh thêm vào bảng products (không có brand_id)
$sql_str = "INSERT INTO `products` (`id`, `name`, `slug`, `description`, `summary`, `stock`, `price`, `disscounted_price`, `images`, `category_id`, `status`, `created_at`, `updated_at`) 
VALUES 
(NULL, '$name', '$slug', '$description', '$sumary', $stock, $giagoc, $giaban, '$imgs', $danhmuc, 'Active', NULL, NULL);";

// Thực thi
mysqli_query($conn, $sql_str);

// Trở về trang danh sách sản phẩm
header("location: ./listsanpham.php");
?>