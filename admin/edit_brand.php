<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$brandId = intval($_GET['id'] ?? 0);
$brand = [];

if ($brandId > 0) {
    $stmt = $conn->prepare("SELECT * FROM brands WHERE id = ?");
    $stmt->bind_param("i", $brandId);
    $stmt->execute();
    $result = $stmt->get_result();
    $brand = $result->fetch_assoc();
    $stmt->close();

    if (!$brand) {
        header("Location: brands.php");
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['brand_name'] ?? '');
    if (!empty($name)) {
        $stmt = $conn->prepare("UPDATE brands SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $brandId);
        $stmt->execute();
        $stmt->close();

        header("Location: brands.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Brand | Diamond Shop</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" />
</head>
<body>
    <?php include '../includes/admin-header.php'; ?>
    <section class="section section--xl">
        <div class="container">
            <h2>Edit Brand</h2>
            <form method="post" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="brand_name" class="form-label">Brand Name</label>
                    <input type="text" name="brand_name" id="brand_name" class="form-control" value="<?= htmlspecialchars($brand['name'] ?? '') ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Brand</button>
                <a href="brands.php" class="btn btn-secondary">Cancel</a>
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
