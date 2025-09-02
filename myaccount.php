<?php
session_start();
require './config/db.php';
require './config/constants.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>My Account | Diamond Shop</title>
        <link rel="icon" type="image/png" href="./assets/images/favicon.ico" />
        <link rel="stylesheet" href="./assets/lib/css/swiper-bundle.min.css" />
        <link rel="stylesheet" href="./assets/lib/css/bvselect.css" />
        <link rel="stylesheet" href="./assets/lib/css/bootstrap.min.css" />
        <link rel="stylesheet" href="./assets/css/style.css" />
    </head>
  
    <body>
        <div class="loader">
            <div class="loader-icon">
                <img src="./assets/images/loader.gif" alt="loader" />
            </div>
        </div>
        <!-- Header Section start -->
        <?php include './includes/header.php'; ?>
        
        <!-- Header  Section start -->

        <!-- breedcrumb section start  -->
        <div class="section breedcrumb">
            <div class="breedcrumb__img-wrapper">
                <img src="./assets/images/banner/breedcrumb.jpg" alt="breedcrumb" />
                <div class="container">
                <ul class="breedcrumb__content">
                    <li>
                        <a href="javascript: void(0);">
                            <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 8L9 1L17 8V18H12V14C12 13.2044 11.6839 12.4413 11.1213 11.8787C10.5587 11.3161 9.79565 11 9 11C8.20435 11 7.44129 11.3161 6.87868 11.8787C6.31607 12.4413 6 13.2044 6 14V18H1V8Z" stroke="#808080" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span> > </span>
                        </a>  
                    </li>
                    <li>
                    <a href="javascript:void(0);">
                        Account
                    </a>
                    </li>
                </ul>
                </div>
            </div>
        </div>
        <!-- breedcrumb section end   -->

        <!-- dashboard Secton Start  -->
        <div class="dashboard section">
            <div class="container">
                <div class="row dashboard__content">
                    <div class="col-lg-3">
                        <nav class="dashboard__nav">
                            <ul class="dashboard__nav-item">
                                <li class="dashboard__nav-item-link <?= isset($_GET['pending_orders']) ? 'active' : '' ?>">
                                <a href="./myaccount.php?pending_orders" class="font-body--lg-400">
                                    <span class="name">Pending Orders</span>
                                </a>
                                </li>
                                <li class="dashboard__nav-item-link <?= !isset($_GET['pending_orders']) && !isset($_GET['my_orders']) && !isset($_GET['delete_account']) ? 'active' : '' ?>">
                                <a href="./myaccount.php" class="font-body--lg-400">
                                    <span class="name"> Edit Account</span>
                                </a>
                                </li>
                                <li class="dashboard__nav-item-link <?= isset($_GET['my_orders']) ? 'active' : '' ?>">
                                <a href="./myaccount.php?my_orders" class="font-body--lg-400">
                                    <span class="name"> My orders</span>
                                </a>
                                </li>

                                <li class="dashboard__nav-item-link <?= isset($_GET['delete_account']) ? 'active' : '' ?>">
                                <a href="./myaccount.php?delete_account" class="font-body--lg-400">
                                    <span class="name"> Delete Account </span>
                                </a>
                                </li>

                                <li class="dashboard__nav-item-link">
                                <a href="./logout.php" class="font-body--lg-400">
                                    <span class="name"> Log out </span>
                                </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-9 section--xl pt-0">
                        <?php
                        if (isset($_GET['pending_orders'])) {
                            include 'pending_orders.php';
                        } elseif (isset($_GET['my_orders'])) {
                            include 'my_orders.php';
                        } elseif (isset($_GET['delete_account'])) {
                            include 'delete_account.php';
                        } else { 
                            include 'edit_account.php';
                        } 
                        ?>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- dashboard Secton  End  -->

        <!--Footer Section Start  -->
        <?php include './includes/footer.php'; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
  <!-- Shopping Cart sidebar  start  -->
  <?php include './includes/cart-sidebar.php'; ?>
  <!-- Shopping Cart sidebar  end -->
<?php endif; ?>
        <script src="./assets/lib/js/jquery.min.js"></script>
        <script src="./assets/lib/js/swiper-bundle.min.js"></script>
        <script src="./assets/lib/js/bvselect.js"></script>
        <script src="./assets/lib/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/js/main.js"></script>
    </body>
</html>
