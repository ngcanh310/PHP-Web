<?php

//lay id goi den
$delid = $_GET['id'];

//ket noi csdl
require('../db/conn.php');

$sql_str = "delete from users where id=$delid";
mysqli_query($conn, $sql_str);


// 4. Quay lại trang danh sách sản phẩm
header("location: listusers.php");
exit;
?>