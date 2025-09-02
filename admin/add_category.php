<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Add Category</title>
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
    <?php include '../includes/admin-header.php'; ?>
    <section class="section section--md">
        <div class="container py-5">
            <h2>Add Category</h2>
            <form id="categoryForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Category Title</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <button type="button" class="btn btn-success" id="category_btn">Add Category</button>
            </form>
        </div>
    </section>

    <script src="<?= BASE_URL ?>assets/lib/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/venobox.min.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/swiper-bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/bvselect.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>assets/lib/js/jquery.syotimer.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/main.js"></script>
    <script src="<?= BASE_URL ?>assets/js/form-validate.js"></script>
    <script>
        $("#category_btn").on("click", function (e) {
            const form = $("#categoryForm");
            if (form[0].checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                form[0].classList.add("was-validated");
                return;
            }

            const name = $("#name").val();

            $.ajax({
                url: "<?= BASE_URL ?>ajax/add_category.php",
                type: "POST",
                dataType: "json",
                data: {
                    name: name
                },
                success: function (data) {
                    if (data.status === "success") {
                        window.location.href = "<?= BASE_URL ?>admin/categories.php";
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
