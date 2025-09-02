<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$categories = [];
$result = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bvselect.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/venobox.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" />
</head>
<body>
<?php include '../includes/admin-header.php'; ?>

<section class="section section--md">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Categories</h2>
            <a href="add_category.php" class="btn btn-primary">Add Category</a>
        </div>
        <table class="table table-bordered" id="categoriesTable">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Category Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $i => $cat): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($cat['name']) ?></td>
                    <td>
                        <a href="edit_category.php?id=<?= $cat['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $cat['id'] ?>">Delete</button>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="deleteCategoryForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this category?
                    <input type="hidden" name="id" id="delete_category_id">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
            $('#categoriesTable').DataTable();

            $('#deleteModal').on('show.bs.modal', function (e) {
                const button = $(e.relatedTarget);
                $('#delete_category_id').val(button.data('id'));
            });

            $('#deleteCategoryForm').on('submit', function (e) {
                e.preventDefault();
                const categoryId = $('#delete_category_id').val();
                $.post("<?= BASE_URL ?>ajax/delete_category.php", { id: categoryId }, function (response) {
                    const res = JSON.parse(response);
                    if (res.status === "success") {
                        location.reload();
                    } else {
                        alert(res.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
