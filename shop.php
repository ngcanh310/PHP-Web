<?php
session_start();
$is_homepage = false;
require_once('components/header.php');
?>

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Organi Shop</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.php">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Danh mục sản phẩm</h4>
                            <ul>
                <?php
                require('./db/conn.php');
                 $sql_str = "select * from categories order by name";
                 $result = mysqli_query($conn, $sql_str);
                    while ($row = mysqli_fetch_assoc($result)){
                ?>
                    <li><?=$row['name']?></li>
                <?php } ?>
                                
                            </ul>
                        </div>
                        
                        
                        <div class="sidebar__item">
                            <div class="latest-product__text">
                                <h4>Mới nhất</h4>
                                <div class="latest-product__slider owl-carousel">
                                    <div class="latest-prdouct__slider__item">
<?php
$sql_str = "SELECT * FROM `products` order by created_at desc limit 0, 3";
$result = mysqli_query($conn, $sql_str);
while ($row = mysqli_fetch_assoc($result)){
    $anh_arr = explode(';', $row['images']);
?>
                                        <a href="sanpham.php?id=<?=$row['id']?>" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="<?="quantri/".$anh_arr[0]?>" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6><?=$row['name']?></h6>
                                                <!-- <span><?=$row['price']?></span> -->
                                                <div class="prices">
                                                    <del class="old"><?= $row['price'] ?> VNĐ</del>
                                                    <span class="curr"><?= number_format($row['disscounted_price'], 0, '', '.') . " VNĐ" ?></span>
                                                </div>
                                            </div>
                                        </a>
<?php
}
?>
                                        
                                    </div>
                                    <div class="latest-prdouct__slider__item">
<?php
$sql_str = "SELECT * FROM `products` order by created_at desc limit 3, 3";
$result = mysqli_query($conn, $sql_str);
while ($row = mysqli_fetch_assoc($result)){
    $anh_arr = explode(';', $row['images']);
?>
                                        <a href="sanpham.php?id=<?=$row['id']?>" class="latest-product__item">
                                            <div class="latest-product__item__pic">
                                                <img src="<?="quantri/".$anh_arr[0]?>" alt="">
                                            </div>
                                            <div class="latest-product__item__text">
                                                <h6><?=$row['name']?></h6>
                                                <div class="prices">
                                                    <del class="old"><?= $row['price'] ?> VNĐ</del>
                                                    <span class="curr"><?= number_format($row['disscounted_price'], 0, '', '.') . " VNĐ" ?></span>
                                                </div>
                                            </div>
                                        </a>
                                    <?php } ?>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="product__discount">
                        <div class="section-title product__discount__title">
                            <h2>Giảm giá</h2>
                        </div>
                        <div class="row">
                            <div class="product__discount__slider owl-carousel">
                            <?php
$sql_str = "SELECT products.id as pid, products.name as pname, categories.name as cname, round((price - disscounted_price)/price*100) as discount, images, price, disscounted_price  FROM `products`, `categories` where products.category_id=categories.id order by discount desc limit 0, 6 ";
$result = mysqli_query($conn, $sql_str);
while ($row = mysqli_fetch_assoc($result)){
    $anh_arr = explode(';', $row['images']);
?>                                
                                <div class="col-lg-4">
                                    <div class="product__discount__item">
                                        <div class="product__discount__item__pic set-bg"
                                            data-setbg="<?="quantri/".$anh_arr[0]?>">
                                            <div class="product__discount__percent">-<?=$row['discount']?>%</div>
                                            <ul class="product__item__pic__hover">
                                                <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                                <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                                <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                            </ul>
                                        </div>
                                        <div class="product__discount__item__text">
                                            <span><?=$row['cname']?></span>
                                            <h5><a href="sanpham.php?id=<?=$row['pid']?>"><?=$row['pname']?></a></h5>
                                            <!-- <div class="product__item__price"><?=$row['disscounted_price']?> <span><?=$row['price']?></span></div> -->
                                            <div class="prices">
                                                    <del class="old"><?= $row['price'] ?> VNĐ</del>
                                                    <span class="curr"><?= number_format($row['disscounted_price'], 0, '', '.') . " VNĐ" ?></span>
                                                </div>
                                        </div>
                                    </div>
                                </div>
<?php } ?>
                               
                            </div>
                        </div>
                    </div>
                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sort By</span>
                                    <select>
                                        <option value="0">Default</option>
                                        <option value="0">Default</option>
                                    </select>
                                </div>
                            </div>
 <?php
    $sql_str = "select * from products order by name";
    $result = mysqli_query($conn, $sql_str);
    
?>
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6>Có <span><?=mysqli_num_rows($result)?></span> sản phẩm</h6>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    <span class="icon_grid-2x2"></span>
                                    <span class="icon_ul"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
<?php
while ($row = mysqli_fetch_assoc($result)){
    $anh_arr = explode(';', $row['images']);
?>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?="quantri/".$anh_arr[0]?>">
                                    <ul class="product__item__pic__hover">
                                        <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                        <li><a href="#"><i class="fa fa-retweet"></i></a></li>
                                        <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="sanpham.php?id=<?=$row['id']?>"><?=$row['name']?></a></h6>
                                    <!-- <h5><?=$row['price']?></h5> -->
                                    <div class="prices">
                                        <del class="old"><?= $row['price'] ?> VNĐ</del>
                                        <span class="curr"> -> <?= number_format($row['disscounted_price'], 0, '', '.') . " VNĐ" ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
<?php } ?>

                    </div>
                    
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <?php

require_once('components/footer.php');
?>