<?php
session_start();  
include __DIR__ . '/./config/db.php';
require './config/constants.php';

$brand_id = isset($_GET['brand']) ? (int)$_GET['brand'] : 0;
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Fetch all brands and categories
$brands = $conn->query("SELECT id, name FROM brands")->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query("SELECT id, name FROM categories")->fetch_all(MYSQLI_ASSOC);

// Dynamic WHERE clause
$where_clauses = [];
$params = [];
$types = "";

if ($category_id > 0) {
    $where_clauses[] = "category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

if ($brand_id > 0) {
    $where_clauses[] = "brand_id = ?";
    $params[] = $brand_id;
    $types .= "i";
}

$where_sql = count($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Count total products
$count_sql = "SELECT COUNT(*) FROM products $where_sql";
$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$count_stmt->bind_result($total_products);
$count_stmt->fetch();
$count_stmt->close();

// Get products
$product_sql = "SELECT * FROM products $where_sql LIMIT ?, ?";
$stmt = $conn->prepare($product_sql);
$params[] = $offset;
$params[] = $limit;
$types .= "ii";
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);

$total_pages = ceil($total_products / $limit);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Diamond Shop</title>
        <link rel="icon" type="image/png" href="./assets/images/favicon.ico" />
        <link rel="stylesheet" href="./assets/lib/css/swiper-bundle.min.css" />
        <link rel="stylesheet" href="./assets/lib/css/bvselect.css" />
        <link rel="stylesheet" href="./assets/lib/css/venobox.css" />
        <link rel="stylesheet" href="./assets/lib/css/bootstrap.min.css" />
        <link rel="stylesheet" href="./assets/css/style.css" />
    </head>

    <body>
        <!-- <div class="loader">
            <div class="loader-icon">
                <img src="./assets/images/loader.gif" alt="loader" />
            </div>
        </div> -->
        
        
        <!-- Header Section start -->
        <?php include './includes/header.php'; ?>
        <!-- Header  Section start -->

        <!-- Banner  Section Start  -->
        <section class="banner banner--01">
            <div class="container">
                <!-- Desktop Version -->
                <div class="banner__wrapper row">
                    <div class="col-lg-8">
                        <div class="swiper-container banner-slider--one">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="banner__wrapper-img banner__wrapper--img-01">
                                        <img src="./assets/images/banner/banner-lg-03.jpg" alt="banner" />

                                        <div class="banner__wrapper-text">
                                            <h2 class="font-title--xl">
                                                Beautiful Earrings
                                            </h2>
                                            <div class="sale-off">
                                                <h5 class="font-body--xxl-500">Sale up to <span>30% off</span></h5>
                                                <p class="font-body--md">
                                                    Free shipping on all your order.
                                                </p>
                                            </div>
                                            <a href="#" class="button button--md">
                                                Shop now
                                                <span>
                                                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M16 7.50049H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M9.95001 1.47559L16 7.49959L9.95001 13.5246" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="banner__wrapper-img banner__wrapper--img-01">
                                        <img src="./assets/images/banner/banner-lg-03.jpg" alt="banner" />

                                        <div class="banner__wrapper-text">
                                            <h2 class="font-title--xl">
                                                Beautiful Earrings
                                            </h2>
                                            <div class="sale-off">
                                                <h5 class="font-body--xxl-500">Sale up to <span>30% off</span></h5>
                                                <p class="font-body--md">
                                                    Free shipping on all your order.
                                                </p>
                                            </div>
                                            <a href="#" class="button button--md">
                                                Shop now
                                                <span>
                                                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M16 7.50049H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M9.95001 1.47559L16 7.49959L9.95001 13.5246" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-slide">
                                    <div class="banner__wrapper-img banner__wrapper--img-01">
                                        <img src="./assets/images/banner/banner-lg-03.jpg" alt="banner" />

                                        <div class="banner__wrapper-text">
                                            <h2 class="font-title--xl">
                                                Beautiful Earrings
                                            </h2>
                                            <div class="sale-off">
                                                <h5 class="font-body--xxl-500">Sale up to <span>30% off</span></h5>
                                                <p class="font-body--md">
                                                    Free shipping on all your order.
                                                </p>
                                            </div>
                                            <a href="#" class="button button--md">
                                                Shop now
                                                <span>
                                                    <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M16 7.50049H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M9.95001 1.47559L16 7.49959L9.95001 13.5246" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="banner__wrapper-img-sm">
                            <div class="banner__wrapper-img banner__wrapper--img-02">
                                <img src="./assets/images/banner/banner-sm-05.jpg" alt="banner" />

                                <div class="banner__wrapper-text">
                                    <h5 class="font-body--md-500">Summer Sale</h5>
                                    <h2 class="font-title--sm">75% off</h2>
                                    <a href="#" class="button button--md">
                                        Shop now
                                        <span>
                                            <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16 7.50049H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M9.95001 1.47559L16 7.49959L9.95001 13.5246" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            <div class="banner__wrapper-img banner__wrapper--img-03">
                                <img src="./assets/images/banner/banner-sm-04.png" alt="banner" />

                                <div class="banner__wrapper-text">
                                    <h5 class="font-body--md-500">Best Deal</h5>
                                    <h2 class="font-title--sm">
                                        Special Products Deal of the Month
                                    </h2>

                                    <a href="#" class="button button--md">
                                        Shop now
                                        <span>
                                            <svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16 7.50049H1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M9.95001 1.47559L16 7.49959L9.95001 13.5246" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mobile Version  -->
            </div>
        </section>
        <!-- Banner Section end  -->

        <!-- Shipping  Section Start  -->
        <section class="shipping-container section section--md pt-0">
            <div class="container">
                <div class="cards-ship cards-ship--one">
                    <!-- Shipping -->
                    <div class="cards-ship__item">
                        <span class="cards-ship__item-icon">
                            <svg width="40" height="28" viewBox="0 0 40 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M32.7021 20.3042C31.7247 20.3042 30.7962 20.687 30.0957 21.3793C29.3952 22.0798 29.0043 22.992 29.0043 23.9694C29.0043 24.9468 29.3871 25.8591 30.0957 26.5595C30.8043 27.2519 31.7247 27.6347 32.7021 27.6347C34.7058 27.6347 36.3348 25.9894 36.3348 23.9694C36.3348 21.9495 34.7058 20.3042 32.7021 20.3042ZM32.7021 26.0057C31.5781 26.0057 30.6333 25.0772 30.6333 23.9694C30.6333 22.8617 31.5781 21.9332 32.7021 21.9332C33.8098 21.9332 34.7058 22.8454 34.7058 23.9694C34.7058 25.0935 33.8098 26.0057 32.7021 26.0057ZM33.6469 8.09488C33.5003 7.95641 33.3048 7.88311 33.1012 7.88311H28.9228C28.4749 7.88311 28.1083 8.24964 28.1083 8.69761V15.3765C28.1083 15.8245 28.4749 16.191 28.9228 16.191H35.5528C36.0008 16.191 36.3673 15.8245 36.3673 15.3765V10.9049C36.3673 10.6768 36.2696 10.4569 36.0986 10.3022L33.6469 8.09488ZM34.7383 14.562H29.7373V9.50396H32.7835L34.7383 11.2633V14.562ZM12.8121 20.3042C11.8347 20.3042 10.9061 20.687 10.2057 21.3793C9.50519 22.0798 9.11423 22.992 9.11423 23.9694C9.11423 24.9468 9.49705 25.8591 10.2057 26.5595C10.9143 27.2519 11.8347 27.6347 12.8121 27.6347C14.8157 27.6347 16.4447 25.9894 16.4447 23.9694C16.4447 21.9495 14.8157 20.3042 12.8121 20.3042ZM12.8121 26.0057C11.688 26.0057 10.7432 25.0772 10.7432 23.9694C10.7432 22.8617 11.688 21.9332 12.8121 21.9332C13.9198 21.9332 14.8157 22.8454 14.8157 23.9694C14.8157 25.0935 13.9198 26.0057 12.8121 26.0057ZM7.37935 21.306H5.74221V19.1395C5.74221 18.6915 5.37569 18.325 4.92771 18.325C4.47974 18.325 4.11322 18.6915 4.11322 19.1395V22.1205C4.11322 22.5685 4.47974 22.935 4.92771 22.935H7.37935C7.82733 22.935 8.19385 22.5685 8.19385 22.1205C8.19385 21.6726 7.82733 21.306 7.37935 21.306ZM11.5089 16.867C11.5089 16.419 11.1423 16.0525 10.6944 16.0525H0.814498C0.366524 16.0525 0 16.419 0 16.867C0 17.315 0.366524 17.6815 0.814498 17.6815H10.6944C11.1423 17.6815 11.5089 17.3231 11.5089 16.867ZM2.46793 13.9267L12.3478 13.9837C12.7958 13.9837 13.1623 13.6253 13.1704 13.1773C13.1786 12.7212 12.8121 12.3547 12.3641 12.3547L2.48422 12.2977C2.47607 12.2977 2.47607 12.2977 2.47607 12.2977C2.0281 12.2977 1.66158 12.6561 1.66158 13.104C1.65343 13.5602 2.01996 13.9267 2.46793 13.9267ZM4.12951 10.2289H14.0094C14.4573 10.2289 14.8239 9.86234 14.8239 9.41437C14.8239 8.96639 14.4573 8.59987 14.0094 8.59987H4.12951C3.68153 8.59987 3.31501 8.96639 3.31501 9.41437C3.31501 9.86234 3.68153 10.2289 4.12951 10.2289ZM39.6986 9.12929L33.8668 4.29932C33.7202 4.17715 33.541 4.11199 33.3456 4.11199H26.4875V1.17979C26.4875 0.73182 26.121 0.365295 25.673 0.365295H4.92771C4.47974 0.365295 4.11322 0.73182 4.11322 1.17979V7.14192C4.11322 7.58989 4.47974 7.95642 4.92771 7.95642C5.37569 7.95642 5.74221 7.58989 5.74221 7.14192V1.99429H24.8666V21.306H18.1877C17.7398 21.306 17.3732 21.6726 17.3732 22.1205C17.3732 22.5685 17.7398 22.935 18.1877 22.935H28.1328C28.5807 22.935 28.9473 22.5685 28.9473 22.1205C28.9473 21.6726 28.5807 21.306 28.1328 21.306H26.4956V5.74098H33.0605L38.371 10.1393L38.314 21.2897H37.4669C37.0189 21.2897 36.6524 21.6563 36.6524 22.1042C36.6524 22.5522 37.0189 22.9187 37.4669 22.9187H39.1203C39.5683 22.9187 39.9348 22.5604 39.9348 22.1124L40 9.7646C39.9919 9.52025 39.886 9.28405 39.6986 9.12929Z"
                                    fill="#00B307"
                                ></path>
                            </svg>
                        </span>
                        <div class="cards-ship__item-process-info">
                            <h2 class="font-body--lg-600">Free Shipping</h2>
                            <p class="font-body--md-400">Free shipping on all your order</p>
                        </div>
                    </div>
                    <!-- Customer Support -->
                    <div class="cards-ship__item">
                        <span class="cards-ship__item-icon">
                            <svg width="39" height="40" viewBox="0 0 39 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M33.0182 15.2101V13.6723C33.0182 9.9916 31.7157 6.57143 29.3543 4.03361C26.9342 1.42857 23.6064 0 19.9762 0H18.7073C15.077 0 11.7493 1.42857 9.32912 4.03361C6.96778 6.57143 5.66526 9.9916 5.66526 13.6723V15.2101C2.79971 15.4034 0.5224 17.7899 0.5224 20.7059V23.1092C0.5224 26.1429 2.99299 28.6134 6.0266 28.6134H9.12744C9.68207 28.6134 10.1358 28.1597 10.1358 27.605V16.2017C10.1358 15.6471 9.68207 15.1933 9.12744 15.1933H7.68206V13.6723C7.68206 7.02521 12.4216 2.01681 18.6989 2.01681H19.9678C26.2535 2.01681 30.9846 7.02521 30.9846 13.6723V15.1933H29.5392C28.9846 15.1933 28.5308 15.6471 28.5308 16.2017V27.5966C28.5308 28.1513 28.9846 28.605 29.5392 28.605H30.951C30.5392 33.8655 26.9174 35.084 25.2367 35.3613C24.7745 33.9412 23.4384 32.916 21.8669 32.916H19.3459C17.3964 32.916 15.8081 34.5042 15.8081 36.4538C15.8081 38.4034 17.3964 40 19.3459 40H21.8753C23.5056 40 24.8753 38.8908 25.2871 37.395C26.1106 37.2773 27.4132 36.9832 28.7073 36.2269C30.5308 35.1597 32.6905 32.9832 32.9762 28.5966C35.8585 28.4202 38.1443 26.0252 38.1443 23.1008V20.6975C38.1527 17.7899 35.8838 15.395 33.0182 15.2101ZM8.13585 26.5882H6.04341C4.11904 26.5882 2.55601 25.0252 2.55601 23.1008V20.6975C2.55601 18.7731 4.11904 17.2101 6.04341 17.2101H8.13585V26.5882ZM21.8753 37.9832H19.3459C18.5056 37.9832 17.8249 37.3025 17.8249 36.4622C17.8249 35.6218 18.5056 34.9412 19.3459 34.9412H21.8753C22.7157 34.9412 23.3964 35.6218 23.3964 36.4622C23.3964 37.3025 22.7157 37.9832 21.8753 37.9832ZM36.1359 23.1008C36.1359 25.0252 34.5728 26.5882 32.6485 26.5882H30.556V17.2101H32.6485C34.5728 17.2101 36.1359 18.7731 36.1359 20.6975V23.1008Z"
                                    fill="#00B307"
                                ></path>
                            </svg>
                        </span>
                        <div class="cards-ship__item-process-info">
                            <h2 class="font-body--lg-600">Customer Support 24/7</h2>
                            <p class="font-body--md-400">Instant access to Support</p>
                        </div>
                    </div>
                    <!-- Secure Payment -->
                    <div class="cards-ship__item">
                        <span class="cards-ship__item-icon">
                            <svg width="33" height="40" viewBox="0 0 33 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M32.6468 34.6678L30.345 8.72697C30.3036 8.21362 29.873 7.82447 29.3514 7.82447H24.4994C24.4911 3.51066 20.9805 0 16.6667 0C12.3528 0 8.84219 3.51066 8.83391 7.82447H3.98191C3.46856 7.82447 3.038 8.21362 2.98832 8.72697L0.686523 34.6678C0.686523 34.7009 0.686523 34.7257 0.686523 34.7589C0.686523 37.6485 3.34436 40 6.60662 40H26.7267C29.9889 40 32.6468 37.6485 32.6468 34.7589C32.6468 34.7257 32.6468 34.7009 32.6468 34.6678ZM16.6667 1.98717C19.8875 1.98717 22.504 4.6036 22.5122 7.82447H10.8211C10.8294 4.6036 13.4458 1.98717 16.6667 1.98717ZM26.7267 38.0046H6.60662C4.45386 38.0046 2.69853 36.5721 2.67369 34.792L4.89269 9.81163H8.83391V13.2975C8.83391 13.8439 9.28102 14.291 9.82749 14.291C10.374 14.291 10.8211 13.8439 10.8211 13.2975V9.81163H22.5122V13.2975C22.5122 13.8439 22.9593 14.291 23.5058 14.291C24.0523 14.291 24.4994 13.8439 24.4994 13.2975V9.81163H28.4406L30.6596 34.8002C30.6348 36.5721 28.8794 38.0046 26.7267 38.0046Z"
                                    fill="#00B307"
                                ></path>
                            </svg>
                        </span>
                        <div class="cards-ship__item-process-info">
                            <h2 class="font-body--lg-600">100% Secure Payment</h2>
                            <p class="font-body--md-400">We ensure your money is save</p>
                        </div>
                    </div>
                    <!-- Money back  -->
                    <div class="cards-ship__item">
                        <span class="cards-ship__item-icon">
                            <svg width="38" height="40" viewBox="0 0 38 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M37.3959 9.43014C37.3959 9.27818 37.3622 9.12621 37.2946 8.98269C37.1595 8.69565 36.8978 8.50148 36.6108 8.44238L19.4137 0.101309C19.1351 -0.0337695 18.8058 -0.0337695 18.5272 0.101309L1.16124 8.51836C0.823545 8.67877 0.604043 9.01646 0.587158 9.39637V9.40481C0.587158 9.41325 0.587158 9.4217 0.587158 9.43858V30.5614C0.587158 30.9498 0.80666 31.3043 1.16124 31.4732L18.5272 39.8903C18.5357 39.8903 18.5357 39.8903 18.5441 39.8987C18.5694 39.9071 18.5948 39.9156 18.6201 39.9325C18.6285 39.9325 18.637 39.9409 18.6539 39.9409C18.6792 39.9493 18.7045 39.9578 18.7298 39.9662C18.7383 39.9662 18.7467 39.9747 18.7552 39.9747C18.7805 39.9831 18.8143 39.9831 18.8396 39.9916C18.848 39.9916 18.8565 39.9916 18.8649 39.9916C18.8987 39.9916 18.9409 40 18.9747 40C19.0084 40 19.0506 40 19.0844 39.9916C19.0929 39.9916 19.1013 39.9916 19.1097 39.9916C19.1351 39.9916 19.1688 39.9831 19.1942 39.9747C19.2026 39.9747 19.211 39.9662 19.2195 39.9662C19.2448 39.9578 19.2701 39.9493 19.2955 39.9409C19.3039 39.9409 19.3124 39.9325 19.3292 39.9325C19.3546 39.924 19.3799 39.9156 19.4052 39.8987C19.4137 39.8987 19.4137 39.8987 19.4221 39.8903L36.8387 31.4479C37.1849 31.279 37.4128 30.9244 37.4128 30.5361V9.45547C37.3959 9.44702 37.3959 9.43858 37.3959 9.43014ZM18.9662 2.13592L34.0274 9.43858L28.4808 12.1317L13.4196 4.82904L18.9662 2.13592ZM18.9662 16.7412L3.90501 9.43858L11.0979 5.95188L26.1591 13.2545L18.9662 16.7412ZM2.61333 11.068L17.9531 18.5057V37.3575L2.61333 29.9198V11.068ZM19.9793 37.3575V18.5057L27.1807 15.0106V19.9409C27.1807 20.4981 27.6365 20.954 28.1937 20.954C28.7509 20.954 29.2068 20.4981 29.2068 19.9409V14.0228L35.3698 11.0342V29.886L19.9793 37.3575Z"
                                    fill="#00B307"
                                ></path>
                            </svg>
                        </span>
                        <div class="cards-ship__item-process-info">
                            <h2 class="font-body--lg-600">Money-Back Guarantee</h2>
                            <p class="font-body--md-400">30 Days Money-Back Guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Shipping  Section end  -->

        <!-- Shop list Section Start  -->
        <section class="shop shop--one mt-5">
        <div class="container">
            <div class="row shop-content">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar-content">
                            <div class="accordion shop" id="shop">

                                <!-- Categories -->
                                <div class="accordion-item shop-item">
                                    <h2 class="accordion-header" id="shop-item-accordion--one">
                                        <button class="accordion-button shop-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true">
                                            All Categories
                                            <span class="icon">
                                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13 7L7 1L1 7" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show">
                                        <div class="accordion-body shop-body">
                                            <div class="categories">
                                                <div class="categories-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="category" id="all" onchange="window.location='?brand=<?= $brand_id ?>'" <?= $category_id == 0 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="all">All Products</label>
                                                    </div>
                                                </div>

                                                <?php foreach ($categories as $cat): ?>
                                                    <div class="categories-item">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="category" id="category-<?= $cat['id'] ?>" onchange="window.location='?category=<?= $cat['id'] ?>&brand=<?= $brand_id ?>'" <?= $category_id == $cat['id'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="category-<?= $cat['id'] ?>">
                                                                <?= htmlspecialchars($cat['name']) ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Brands -->
                                <div class="accordion-item shop-item">
                                    <h2 class="accordion-header" id="shop-item-accordion--two">
                                        <button class="accordion-button shop-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true">
                                            All Brands
                                            <span class="icon">
                                                <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M13 7L7 1L1 7" stroke="#1A1A1A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse show">
                                        <div class="accordion-body shop-body">
                                            <div class="categories">
                                                <div class="categories-item">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="brand" id="brand-all" onchange="window.location='?category=<?= $category_id ?>'" <?= $brand_id == 0 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="brand-all">All Brands</label>
                                                    </div>
                                                </div>

                                                <?php foreach ($brands as $brand): ?>
                                                    <div class="categories-item">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="brand" id="brand-<?= $brand['id'] ?>" onchange="window.location='?category=<?= $category_id ?>&brand=<?= $brand['id'] ?>'" <?= $brand_id == $brand['id'] ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="brand-<?= $brand['id'] ?>">
                                                                <?= htmlspecialchars($brand['name']) ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="row shop__product-items">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <div class="col-xl-4 col-md-6">
                                    <div class="cards-md cards-md--four w-100">
                                        <div class="cards-md__img-wrapper">
                                            <a href="<?= isset($_SESSION['user_id']) ? 'product-details.php?id=' . $product['id'] : 'login.php' ?>">
                                                <img src="<?= BASE_URL . htmlspecialchars($product['image_1']) ?>" alt="<?= htmlspecialchars($product['title']) ?>" />
                                            </a>
                                            <?php if ($product['status'] === 'inactive'): ?>
                                                <span class="tag danger font-body--md-400">Out of Stock</span>
                                            <?php endif; ?>
                                            <div class="cards-md__favs-list">
                                                <a href="<?= isset($_SESSION['user_id']) ? 'product-details.php?id=' . $product['id'] : 'login.php' ?>">
                                                    <span class="action-btn">❤️</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="cards-md__info d-flex justify-content-between align-items-center">
                                            <a href="<?= isset($_SESSION['user_id']) ? 'product-details.php?id=' . $product['id'] : 'login.php' ?>" class="cards-md__info-left">
                                                <h6><?= htmlspecialchars($product['title']) ?></h6>
                                                <div class="cards-md__info-price">
                                                    <span>$<?= number_format($product['price'], 2) ?></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center">
                                <p>No products found.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <nav class="pagination-wrapper section--xl mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?category=<?= $category_id ?>&brand=<?= $brand_id ?>&page=<?= $page - 1 ?>">Prev</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?category=<?= $category_id ?>&brand=<?= $brand_id ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?category=<?= $category_id ?>&brand=<?= $brand_id ?>&page=<?= $page + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </section>

        <!--Footer Section Start  -->
        <?php include './includes/footer.php'; ?>
        <!--Footer Section end  -->

        <?php if (isset($_SESSION['user_id'])): ?>
  <!-- Shopping Cart sidebar  start  -->
  <?php include './includes/cart-sidebar.php'; ?>
  <!-- Shopping Cart sidebar  end -->
<?php endif; ?>

        <script src="./assets/lib/js/jquery.min.js"></script>
        <script src="./assets/lib/js/venobox.min.js"></script>
        <script src="./assets/lib/js/swiper-bundle.min.js"></script>
        <script src="./assets/lib/js/bvselect.js"></script>
        <script src="./assets/lib/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/lib/js/jquery.syotimer.min.js"></script>
        <script src="./assets/js/main.js"></script>
        <script src="./assets/js/home1.js"></script>
    </body>
</html>

