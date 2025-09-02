<?php
// add_brand.php
session_start();
include '../config/db.php';
include '../config/constants.php'; // contains BASE_URL

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$userRole = $_SESSION['role'] ?? '';

// Redirect customers to homepage
if ($userRole !== 'admin') {
    header('Location: ' . BASE_URL . 'index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Add Brand | Diamond Shop</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bvselect.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/venobox.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" />
</head>
<body>
    <!-- Loader -->
    <div class="loader">
        <div class="loader-icon">
            <img src="<?= BASE_URL ?>assets/images/loader.gif" alt="loader" />
        </div>
    </div>

    <!-- Header Section -->
    <?php include '../includes/admin-header.php'; ?>

    <!-- Brand Form Section -->
    <section class="section section--xl">
        <div class="container">
            <h2 class="mb-4">Add New Brand</h2>
            
            <div id="errorMsg"></div>

            <form novalidate class="needs-validation" id="brandForm">
                <div class="mb-3">
                    <label for="brand_name" class="form-label">Brand Name</label>
                    <input type="text" name="brand_name" id="brand_name" class="form-control" required />
                </div>
                <button type="button" class="button button--md w-100" id="brand_btn">Add Brand</button>
            </form>
        </div>
    </section>

    <!-- Scripts -->
    <script src="<?= BASE_URL ?>assets/lib/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/venobox.min.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/swiper-bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/bvselect.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/jquery.syotimer.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
    <script src="<?= BASE_URL ?>assets/js/form-validate.js"></script>

    <script>
        $("#brand_btn").on("click", function (e) {
            const form = $("#brandForm");
            if (form[0].checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                form[0].classList.add("was-validated");
                return;
            }

            const brand_name = $("#brand_name").val();

            $.ajax({
                url: "<?= BASE_URL ?>ajax/add_brand.php",
                type: "POST",
                dataType: "json",
                data: {
                    brand_name: brand_name
                },
                success: function (data) {
                    if (data.status === "success") {
                        window.location.href = "<?= BASE_URL ?>admin/brands.php";
                    } else {
                        $("#errorMsg").html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>${data.message}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                    }
                },
                error: function (err) {
                    console.error(err);
                    $("#errorMsg").html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Something went wrong. Please try again.</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            });
        });
    </script>
</body>
</html>
