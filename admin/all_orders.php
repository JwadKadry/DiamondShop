<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
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
        <h2 class="mb-4">All orders</h2>
        <div class="dashboard__content-card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table id="myOrdersTable" class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Order ID</th>
                                <th>Due Amount</th>
                                <th>Invoice Number</th>
                                <th>Total Products</th>
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($order = $result->fetch_assoc()): ?>
                                <tr id="order-<?= $order['id'] ?>" style="vertical-align: middle;">
                                    <td><?= htmlspecialchars($order['id']) ?></td>
                                    <td>$<?= number_format((float)$order['amount_due'], 2) ?></td>
                                    <td><?= htmlspecialchars($order['invoice_number']) ?></td>
                                    <td><?= htmlspecialchars($order['total_products']) ?></td>
                                    <td><?= htmlspecialchars(date("Y-m-d H:i", strtotime($order['order_date']))) ?></td>
                                    <td><?= htmlspecialchars($order['order_status']) ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-order" data-id="<?= $order['id'] ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>You have no orders yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
<!-- jQuery -->
<script src="<?= BASE_URL ?>assets/lib/js/jquery.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/venobox.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/swiper-bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/bvselect.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $('#myOrdersTable').DataTable({
        pageLength: 10,
        lengthChange: true,
        ordering: true,
        searching: true
    });

    $('.delete-order').on('click', function () {
        if (!confirm('Are you sure you want to delete this order?')) return;

        const orderId = $(this).data('id');
        $.ajax({
            url: '<?= BASE_URL ?>ajax/delete_order.php',
            method: 'POST',
            data: { id: orderId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#order-' + orderId).remove();
                } else {
                    alert(response.message || 'Failed to delete order.');
                }
            },
            error: function () {
                alert('Server error. Please try again.');
            }
        });
    });
</script>
