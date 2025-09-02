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
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Login | Diamond Shop</title>
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

    <!-- breedcrumb section start  -->
    <div class="section breedcrumb">
      <div class="breedcrumb__img-wrapper">
        <img src="./assets/images/banner/breedcrumb.jpg" alt="breedcrumb" />
        <div class="container">
          <ul class="breedcrumb__content">
            <li>
              <a href="javascript:void(0);">
                <svg
                  width="18"
                  height="19"
                  viewBox="0 0 18 19"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M1 8L9 1L17 8V18H12V14C12 13.2044 11.6839 12.4413 11.1213 11.8787C10.5587 11.3161 9.79565 11 9 11C8.20435 11 7.44129 11.3161 6.87868 11.8787C6.31607 12.4413 6 13.2044 6 14V18H1V8Z"
                    stroke="#808080"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
                <span> > </span>
              </a>
            </li>
            <li class="active"><a href="javascript:void(0);">Login</a></li>
          </ul>
        </div>
      </div>
    </div>
    <!-- breedcrumb section end   -->

    <!-- Login Section Start  -->
    <section class="sign-in section section--xl">
      <div class="container">
        <div class="form-wrapper">
          <h6 class="font-title--sm">Login</h6>
          <form novalidate class="needs-validation" id="loginForm">
            <div id="errorMsg"></div>
            <div class="form-input mb-3">
              <input class="form-control" type="text" name="username" id="username" placeholder="Username" required />
              <!-- <div class="invalid-feedback">
                Username is required.
              </div> -->
            </div>
            <div class="form-input">
              <input type="password" class="form-control" name="password" placeholder="Password" id="password" required />
              
              <button
                type="button"
                class="icon icon-eye"
                onclick="showPassword('password',this)"
              >
                <svg
                  width="20"
                  height="21"
                  viewBox="0 0 20 21"
                  fill="none"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M1.66663 10.5003C1.66663 10.5003 4.69663 4.66699 9.99996 4.66699C15.3033 4.66699 18.3333 10.5003 18.3333 10.5003C18.3333 10.5003 15.3033 16.3337 9.99996 16.3337C4.69663 16.3337 1.66663 10.5003 1.66663 10.5003Z"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                  <path
                    d="M10 13C10.663 13 11.2989 12.7366 11.7678 12.2678C12.2366 11.7989 12.5 11.163 12.5 10.5C12.5 9.83696 12.2366 9.20107 11.7678 8.73223C11.2989 8.26339 10.663 8 10 8C9.33696 8 8.70107 8.26339 8.23223 8.73223C7.76339 9.20107 7.5 9.83696 7.5 10.5C7.5 11.163 7.76339 11.7989 8.23223 12.2678C8.70107 12.7366 9.33696 13 10 13V13Z"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
              </button>
            </div>
            
            <div class="form-button">
              <button type="button" class="button button--md w-100" id="login_btn">Login</button>
            </div>
            <div class="form-register">
              Don't have account ? <a href=<?php echo BASE_URL . 'register.php'; ?>>Register</a>
            </div>
          </form>
        </div>
      </div>
    </section>
    <!-- Sign-in Section end  -->

    <!--Footer Section Start  -->
    <?php include './includes/footer.php'; ?>
    <!--Footer Section end  -->

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
        $("#login_btn").on("click", function(e) {
            var form = $("#loginForm");
            if (form[0].checkValidity() === false) {
                e.preventDefault();
                e.stopPropagation();
                form[0].classList.add("was-validated");
                return;
            }

            const username = $("#username").val();
            const password = $("#password").val();

            $.ajax({
                url: "<?= BASE_URL ?>ajax/login.php",
                type: 'POST',
                data: {
                    username: username,
                    password: password,
                },
                success: function(result) {
                    const data = result;
                    
                    if (data.status == "success") {
                        if (data.role === 'admin') {
                            window.location.href = "<?= BASE_URL ?>admin/products.php";
                        } else {
                            window.location.href = "<?= BASE_URL ?>index.php"; 
                        }
                    } else {
                        $("#errorMsg").html(
                            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>${data.message}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                        );
                    }
                },
                error: function(err) {
                    console.log(err);
                    $("#errorMsg").html(
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Login failed. Please try again.</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`
                    );
                }
            });
        });
    </script>
  </body>
</html>
