<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

// Fetch all brands
$brands = [];
$result = mysqli_query($conn, "SELECT * FROM brands ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    $brands[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manage Brands | Diamond Shop</title>
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
    <div class="loader"><div class="loader-icon"><img src="<?= BASE_URL ?>assets/images/loader.gif" alt="loader" /></div></div>
    <?php include '../includes/admin-header.php'; ?>

    <section class="section section--xl">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Manage Brands</h2>
                <a href="add_brand.php" class="btn btn-primary">Add New Brand</a>
            </div>
            <table id="brandsTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Brand Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($brands as $index => $brand): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($brand['name']) ?></td>
                            <td>
                                <a href="edit_brand.php?id=<?= $brand['id'] ?>" class="btn btn-sm btn-warning">✏️</a>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $brand['id'] ?>">🗑️</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Deletion</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">Are you sure you want to delete this brand?</div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
          </div>
        </div>
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
    <script src="<?= BASE_URL ?>assets/lib/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#brandsTable').DataTable();

            let brandIdToDelete = null;

            $(".delete-btn").on("click", function () {
                brandIdToDelete = $(this).data("id");
                $("#deleteModal").modal("show");
            });

            $("#confirmDeleteBtn").on("click", function () {
                if (brandIdToDelete) {
                    $.post("<?= BASE_URL ?>ajax/delete_brand.php", { id: brandIdToDelete }, function (response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert(response.message || "Deletion failed.");
                        }
                    }, "json");
                }
            });
        });
    </script>
</body>
</html>
