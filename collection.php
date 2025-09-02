<?php
session_start();  
include __DIR__ . '/./config/db.php';
include './config/constants.php';

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
    <title>Collection | Diamond Shop</title>
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

    <?php include './includes/header.php'; ?>

    <section class="shop shop--one">
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

    <?php include './includes/footer.php'; ?>
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
