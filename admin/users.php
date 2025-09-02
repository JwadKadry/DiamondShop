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
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Manage Users | Diamond Shop</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" />
</head>
<body>

<?php include '../includes/admin-header.php'; ?>

<section class="section section--md">
    <div class="container">
        <h2 class="mb-4">User Management</h2>

        <table id="usersTable" class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Profile</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Address</th>
                    <th>Registered</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $users = $conn->query("SELECT * FROM users ORDER BY id DESC");
                $i = 1;
                while ($row = $users->fetch_assoc()):
                ?>
                    <tr id="user-<?= $row['id'] ?>" style="vertical-align: middle;">
                        <td><?= $i++ ?></td>
                        <td>
                            <?php if (!empty($row['profile_image'])): ?>
                                <img src="<?= BASE_URL . 'uploads/users/' . htmlspecialchars($row['profile_image']) ?>" alt="Profile" class="rounded-circle" style="height: 50px; width: 50px;">
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= htmlspecialchars($row['address'] ?? 'N/A') ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm delete-user" data-id="<?= $row['id'] ?>">Delete</button>
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
        $('#usersTable').DataTable();

        $('.delete-user').on('click', function () {
            if (!confirm('Are you sure you want to delete this user?')) return;

            const userId = $(this).data('id');
            $.ajax({
                url: 'ajax/delete_user.php',
                method: 'POST',
                data: { id: userId },
                dataType: 'json',
                success: function (response) {
                    if (response.status === 'success') {
                        $('#user-' + userId).remove();
                    } else {
                        alert(response.message || 'Failed to delete user.');
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
