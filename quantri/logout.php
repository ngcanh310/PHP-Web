<?php

session_start();
unset($_SESSION["user_admin"]);  //xoa session user
header("Location: login.php");

?>