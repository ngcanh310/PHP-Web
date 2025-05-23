<?php
session_start();
$is_homepage = false;
require_once('../components/header.php');
?>

<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-section set-bg" data-setbg="../img/breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Organi Shop</h2>
                    <div class="breadcrumb__option">
                        <a href="./index.php">Home</a>
                        <span>Tin Tức</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once('../components/footer.php');
?>