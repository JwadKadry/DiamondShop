<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

// Ensure user is admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$categoryId = intval($_GET['id'] ?? 0);
$category = [];

// Fetch category
if ($categoryId > 0) {
    $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
    $stmt->close();

    if (!$category) {
        header("Location: categories.php");
        exit();
    }
}

// Handle update submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['name'] ?? '');

    if (!empty($title)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $title, $categoryId);
        $stmt->execute();
        $stmt->close();

        header("Location: categories.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Category | Diamond Shop</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" />
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>
    <section class="section section--xl">
        <div class="container">
            <h2>Edit Category</h2>
            <form method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="name" class="form-label">Category Title</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="<?= htmlspecialchars($category['name'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Category</button>
                <a href="categories.php" class="btn btn-secondary">Cancel</a>
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
</body>
</html>
