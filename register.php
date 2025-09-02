<?php
session_start();
require './config/constants.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register | Diamond Shop</title>
    <link rel="icon" type="image/png" href="./assets/images/favicon.ico" />
    <link rel="stylesheet" href="./assets/lib/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./assets/lib/css/bvselect.css" />
    <link rel="stylesheet" href="./assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>

<body>    
    <?php include './includes/header.php'; ?>

    <section class="sign-in section section--xl">
        <div class="container">
            <div class="form-wrapper">
                <h6 class="font-title--sm">Register</h6>
                <div id="errorMsg"></div>

                <form id="registerForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div id="errorMsg"></div>
                    <div class="form-input mb-3">
                        <input class="form-control" type="text" name="username" id="username" placeholder="Username" required />
                    </div>

                    <div class="form-input mb-3">
                        <input class="form-control" type="email" name="email" id="email" placeholder="Email" required />
                    </div>

                    <div class="form-input mb-3">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
                    </div>

                    <div class="form-input mb-3">
                        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm Password" required />
                    </div>

                    <div class="form-input mb-3">
                        <input class="form-control" type="text" name="userAddress" id="userAddress" placeholder="Address" required />
                    </div>
                    <label for="image" class="form-label">Profile Image (optional)</label>
                    <div class="form-input mb-3">
                        
                        <input class="form-control" type="file" name="image" id="image" />
                    </div>                      

                    <div class="form-button mb-3">
                        <button type="button" class="button button--md w-100" id="register_btn">Register</button>
                    </div>

                    <div class="form-register">
                        Already have an account? <a href="<?php echo BASE_URL . 'login.php'; ?>">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

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
    <script src="./assets/js/form-validate.js"></script>

    <script>
        $('#register_btn').on('click', function (e) {
            e.preventDefault();

            const form = $('#registerForm')[0];
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            let formData = new FormData(form);

            $.ajax({
                url: './ajax/register.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {
                    const data = JSON.parse(result);
                    if (data.status === "success") {
                        if (data.role === 'admin') {
                            window.location.href = "<?= BASE_URL ?>admin/products.php";
                        } else {
                            window.location.href = "<?= BASE_URL ?>index.php";
                        }
                    } else {
                        $('#errorMsg').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>${data.message}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                    }
                },
                error: function () {
                    $('#errorMsg').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Registration failed. Please try again.</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            });
        });
    </script>
</body>
</html>
