<?php
// Lấy ID sản phẩm cần xóa
$delid = $_GET['id'];

// Kết nối CSDL
require('../../db/conn.php');

// 1. Tìm các hình ảnh của sản phẩm để xóa file ảnh vật lý
$sql1 = "SELECT images FROM products WHERE id=$delid";
$rs = mysqli_query($conn, $sql1);

if ($rs && mysqli_num_rows($rs) > 0) {
    $row = mysqli_fetch_assoc($rs);

    // Danh sách các ảnh (tách từ chuỗi phân cách ;)
    $images_arr = explode(';', $row['images']);

    // Xóa ảnh vật lý khỏi thư mục
    foreach ($images_arr as $img) {
        if (!empty($img) && file_exists($img)) {
            unlink($img);
        }
    }

    // 2. Xóa các chi tiết đơn hàng liên quan đến sản phẩm
    $sql_delete_details = "DELETE FROM order_details WHERE product_id = $delid";
    mysqli_query($conn, $sql_delete_details);

    // 3. Xóa sản phẩm khỏi bảng products
    $sql_delete_product = "DELETE FROM products WHERE id = $delid";
    mysqli_query($conn, $sql_delete_product);
}

// 4. Quay lại trang danh sách sản phẩm
header("location: listsanpham.php");
exit;
?>