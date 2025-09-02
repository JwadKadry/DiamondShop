<?php
session_start();
require './config/db.php';
include('./config/constants.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Select Payment Option</title>
    <link rel="icon" type="image/png" href="./assets/images/favicon.ico" />
    <link rel="stylesheet" href="./assets/lib/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./assets/lib/css/bvselect.css" />
    <link rel="stylesheet" href="./assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>
<body>
    <!-- Header Section start -->
    <?php include './includes/header.php'; ?>
    <!-- Header  Section start -->
    <section class="shoping-cart section section--xl">
        <div class="container py-5">
            <h2>Select Payment Method</h2>
            <form action="place_order.php" method="post">
                <div>
                    <input type="radio" id="paypal" name="payment_method" value="paypal" required>
                    <label for="paypal">PayPal</label>
                </div>
                <div>
                    <input type="radio" id="offline" name="payment_method" value="offline" required>
                    <label for="offline">Pay Offline</label>
                </div>
                <button type="submit" class="button button--md mt-3">Confirm Order</button>
            </form>
        </div>
    </section>

    <!-- Shopping Cart sidebar  end -->
    <script src="./assets/lib/js/jquery.min.js"></script>
    <script src="./assets/lib/js/swiper-bundle.min.js"></script>
    <script src="./assets/lib/js/bvselect.js"></script>
    <script src="./assets/lib/js/bootstrap.bundle.min.js"></script>
    <script src="./assets/js/main.js"></script>
</body>
</html>
