<?php
session_start();
include('./config/db.php');
include('./config/constants.php');

// Get search query
$searchQuery = trim($_GET['query'] ?? '');

if (empty($searchQuery)) {
    header("Location: index.php");
    exit;
}

// Fetch matching products
$stmt = $conn->prepare("SELECT * FROM products WHERE title LIKE CONCAT('%', ?, '%') AND status = 'active'");
$stmt->bind_param("s", $searchQuery);
$stmt->execute();
$result = $stmt->get_result();

$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Collection | Diamond Shop</title>
        <link rel="icon" type="image/png" href="./assets/images/favicon.ico" />
        <link rel="stylesheet" href="./assets/lib/css/swiper-bundle.min.css" />
        <link rel="stylesheet" href="./assets/lib/css/bvselect.css" />
        <link rel="stylesheet" href="./assets/lib/css/venobox.css" />
        <link rel="stylesheet" href="./assets/lib/css/bootstrap.min.css" />
        <link rel="stylesheet" href="./assets/css/style.css" />
    </head>

    <body>
        <?php include('./includes/header.php'); ?>

        <section class="shop-area mt-5">
            <div class="container">
                <h4 class="mb-4">Search results for: <strong><?= htmlspecialchars($searchQuery) ?></strong></h4>

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
                                    </div>
                                    <div class="cards-md__info d-flex justify-content-between align-items-center">
                                        <a href="<?= isset($_SESSION['user_id']) ? 'product-details.php?id=' . $product['id'] : 'login.php' ?>" class="cards-md__info-left">
                                            <h6 class="font-body--md-400"><?= htmlspecialchars($product['title']) ?></h6>
                                            <div class="cards-md__info-price">
                                                <span class="font-body--lg-500">$<?= number_format($product['price'], 2) ?></span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12 text-center">
                            <p>No products found matching "<strong><?= htmlspecialchars($searchQuery) ?></strong>".</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php include('./includes/footer.php'); ?>
        <!-- Shopping Cart sidebar  start  -->
        <?php if (isset($_SESSION['user_id'])): ?>
  <!-- Shopping Cart sidebar  start  -->
  <?php include './includes/cart-sidebar.php'; ?>
  <!-- Shopping Cart sidebar  end -->
<?php endif; ?>
        <!-- Shopping Cart sidebar  end -->
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
