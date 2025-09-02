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
    <title>Manage Products | Diamond Shop</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bvselect.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" />
</head>
<body>

<?php include '../includes/admin-header.php'; ?>

<section class="section section--md">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Product Management</h2>
            <a href="add_product.php" class="btn btn-success">Add New Product</a>
        </div>

        <table id="productsTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "
                    SELECT p.*, c.name AS category_name, b.name AS brand_name
                    FROM products p
                    JOIN categories c ON p.category_id = c.id
                    JOIN brands b ON p.brand_id = b.id
                    ORDER BY p.id DESC
                ";
                $result = $conn->query($query);
                $i = 1;
                while ($row = $result->fetch_assoc()):
                    $imagePath = !empty($row['image_1']) ? BASE_URL . $row['image_1'] : BASE_URL . 'assets/images/no-image.png';
                ?>
                    <tr id="product-<?= $row['id'] ?>" style="vertical-align: middle; ">
                        <td><?= $i++ ?></td>
                        <td>
                            <img src="<?= $imagePath ?>" alt="Image" style="width: 70px; height: 70px; object-fit: cover; border-radius: 5px; border: 1px solid #ccc;">
                        </td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($row['category_name']) ?></td>
                        <td><?= htmlspecialchars($row['brand_name']) ?></td>
                        <td>$<?= number_format($row['price'], 2) ?></td>
                        <td><span class="badge bg-<?= $row['status'] === 'active' ? 'success' : 'secondary' ?>">
                            <?= ucfirst($row['status']) ?>
                        </span></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <button class="btn btn-sm btn-danger delete-product" data-id="<?= $row['id'] ?>">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<script src="<?= BASE_URL ?>assets/lib/js/jquery.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/venobox.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/swiper-bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/bvselect.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/jquery.syotimer.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#productsTable').DataTable();

        $('.delete-product').on('click', function () {
            if (!confirm('Are you sure you want to delete this product?')) return;

            const productId = $(this).data('id');

            $.ajax({
                url: '<?= BASE_URL ?>ajax/delete_product.php',
                method: 'POST',
                data: { id: productId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#product-' + productId).remove();
                    } else {
                        alert(response.message || 'Failed to delete product.');
                    }
                },
                error: function () {
                    alert('Server error. Please try again.');
                }
            });
        });
    });
</script>

</body>
</html>
