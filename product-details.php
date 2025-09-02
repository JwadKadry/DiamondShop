<?php
session_start();
require './config/db.php';
include './config/constants.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid product ID');
}

$product_id = (int) $_GET['id'];

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Fetch category
$categoryStmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
$categoryStmt->bind_param("i", $product['category_id']);
$categoryStmt->execute();
$categoryResult = $categoryStmt->get_result();
$category = $categoryResult->fetch_assoc();

// Handle Add to Cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];
    $price = (float) $_POST['price'];
    $user_id = $_SESSION['user_id'] ?? 0;

    if ($quantity > 0 && $user_id > 0) {
        $stmt = $conn->prepare("INSERT INTO shopping_cart (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            // die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("iiid", $user_id, $product_id, $quantity, $price);
        if ($stmt->execute()) {
            echo "<script>alert('Product added to cart'); window.location.href = './cart.php';</script>";
        } else {
            echo "<script>alert('Failed to add to cart');</script>";
        }
    } else {
        echo "<script>alert('Invalid quantity or not logged in.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Product Details | Diamond Shop</title>
        <link rel="icon" type="image/png" href="./assets/images/favicon.ico" />
        <link rel="stylesheet" href="./assets/lib/css/swiper-bundle.min.css" />
        <link rel="stylesheet" href="./assets/lib/css/bvselect.css" />
        <link rel="stylesheet" href="./assets/lib/css/venobox.css" />
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
                        
                        <li class="active"><a href="javascript: void(0);">Product Detail</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breedcrumb section end   -->

        <!-- Products View Section Start  -->
        <section class="products section" style="margin-bottom: 150px;">
            <div class="container">
                <div class="row" style="margin-top: 32px;">
                    <div class="col-lg-6">
                        <!-- Product View Slider -->
                        <div class="gallery-view">
                            <div class="gallery-items">
                                <div class="swiper-container gallery-items-slider">
                                    <div class="swiper-wrapper">
                                        <div class="gallery-item swiper-slide">
                                            <img src="<?= BASE_URL . htmlspecialchars($product['image_1']) ?>" alt="Slide 01" />
                                        </div>

                                        <?php if (!empty($product['image_2'])): ?>
                                            <div class="gallery-item swiper-slide">
                                                <img src="<?= BASE_URL . htmlspecialchars($product['image_2']) ?>" alt="Slide 02" />
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!empty($product['image_3'])): ?>
                                            <div class="gallery-item swiper-slide">
                                                <img src="<?= BASE_URL . htmlspecialchars($product['image_3']) ?>" alt="Slide 03" />
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                                <div class="gallery-prev-item">
                                    <div class="gallery-icon">
                                        <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 8.5L8 1.5L1 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="gallery-next-item">
                                    <div class="gallery-icon">
                                        <svg width="16" height="10" viewBox="0 0 16 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15 1.5L8 8.5L1 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <div class="gallery-main-image products__gallery-img--lg">
                                <img class="product-main-image" src="<?= BASE_URL . htmlspecialchars($product['image_1']) ?>" alt="Slide 01" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Products information -->
                        <div class="products__content">
                            <div class="products__content-title">
                                <h2 class="font-title--md"><?php echo $product['title'] ?></h2>
                            </div>

                            <div class="products__content-price">
                                <h2 class="font-body--xxxl-500">$ <?php echo $product['price'] ?></h2>
                            </div>
                        </div>
                        <!-- brand  -->
                        <div class="products__content">
                            <div class="products__content-brand">
                                <div class="brand-name">
                                    <h2 class="font-body--md-400">Brand:</h2>
                                    <a href="javascript:void(0);" class="brand-name-logo">
                                        <img src="<?= BASE_URL . htmlspecialchars($product['image_1']) ?>" style="height: 50px; width: auto;"/>
                                    </a>
                                </div>
                            </div>
                            <p class="products__content-brand-info font-body--md-400">
                                <?php echo $product['description'] ?>
                            </p>
                        </div>
                        <!-- Action button -->
                        <div class="products__content">
                            <div class="products__content-action">
                                <div class="counter-btn-wrapper products__content-action-item">
                                    <button class="counter-btn-dec counter-btn" onclick="decrement1()">
                                        -
                                    </button>
                                    <input type="number" id="counter-btn-counter" class="counter-btn-counter" min="0" max="1000" placeholder="0" />
                                    <button class="counter-btn-inc counter-btn" onclick="increment1()">
                                        +
                                    </button>
                                </div>
                                <!-- add to cart  -->
                                <form method="post" onsubmit="return validateQuantity();" style="width: 100%; ">
                                    <input type="hidden" name="add_to_cart" value="1">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="price" value="<?= $product['price'] ?>">
                                    <input type="hidden" id="quantity_input" name="quantity" value="0">
                                    <button type="submit" class="button button--md products__content-action-item">
                                        Add to Cart
                                        <span>
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M5.66667 7.33333H3.16667L1.5 16.5H16.5L14.8333 7.33333H12.3333M5.66667 7.33333V4.83333C5.66667 2.99239 7.15905 1.5 9 1.5V1.5C10.8409 1.5 12.3333 2.99238 12.3333 4.83333V7.33333M5.66667 7.33333H12.3333M5.66667 7.33333V9.83333M12.3333 7.33333V9.83333"
                                                    stroke="currentColor"
                                                    stroke-width="1.3"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- Tags  -->
                        <div class="products__content">
                            <h5 class="products__content-category font-body--md-500">Category: <a href="javascript:void(0);"><?php echo $category['name'] ?></a></h5>
                            <div class="products__content-tags">
                                <h5 class="font-body--md-500">Tag :</h5>
                                <div class="products__content-tags-item">
                                    <a href="javascript:void(0);" class="font-body--md-400"><?php echo $product['keywords'] ?></a></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Products View Section end  -->

        <!--Footer Section Start  -->
        <?php include './includes/footer.php'; ?>
        <!--Footer Section end  -->

        <!-- Shopping Cart sidebar  start  -->
        <?php if (isset($_SESSION['user_id'])): ?>
  <!-- Shopping Cart sidebar  start  -->
  <?php include './includes/cart-sidebar.php'; ?>
  <!-- Shopping Cart sidebar  end -->
<?php endif; ?>
        <!-- Shopping Cart sidebar  end -->

        <script src="./assets/lib/js/jquery.min.js"></script>
        <script src="./assets/lib/js/swiper-bundle.min.js"></script>
        <script src="./assets/lib/js/venobox.min.js"></script>
        <script src="./assets/lib/js/bvselect.js"></script>
        <script src="./assets/lib/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/js/main.js"></script>
        <script>
            function increment1() {
                const input = document.getElementById("counter-btn-counter");
                let value = parseInt(input.value) || 0;
                input.value = value + 1;
                document.getElementById("quantity_input").value = input.value;
            }

            function decrement1() {
                const input = document.getElementById("counter-btn-counter");
                let value = parseInt(input.value) || 0;
                if (value > 0) {
                    input.value = value - 1;
                    document.getElementById("quantity_input").value = input.value;
                }
            }

            function validateQuantity() {
                const quantity = parseInt(document.getElementById("counter-btn-counter").value) || 0;
                if (quantity <= 0) {
                    alert("Please enter a valid quantity.");
                    return false;
                }
                return true;
            }

            // Sync on manual input
            document.getElementById("counter-btn-counter").addEventListener("input", function () {
                const val = parseInt(this.value) || 0;
                document.getElementById("quantity_input").value = val;
            });
        </script>
    </body>
</html>
