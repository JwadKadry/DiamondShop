<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$stmt = $conn->prepare("SELECT * FROM payments ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>All Payments | Diamond Shop</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/images/favicon.ico" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css" />
</head>
<body>

<?php include '../includes/admin-header.php'; ?>

<section class="section section--md">
    <div class="container">
        <h2 class="mb-4">All Payments</h2>
        <div class="dashboard__content-card-body">
            <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table id="paymentsTable" class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Invoice No</th>
                                <th>Amount</th>
                                <th>Payment Mode</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr id="payment-<?= $row['id'] ?>">
                                    <td><?= $count++ ?></td>
                                    <td><?= htmlspecialchars($row['order_id']) ?></td>
                                    <td><?= htmlspecialchars($row['invoice_number']) ?></td>
                                    <td>$<?= number_format((float)$row['amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($row['payment_mode']) ?></td>
                                    <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                                    <td>
                                        <button class="btn btn-danger btn-sm delete-payment" data-id="<?= $row['id'] ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No payments recorded yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Scripts -->
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
    $('#paymentsTable').DataTable({
        pageLength: 10,
        lengthChange: true,
        ordering: true,
        searching: true
    });

    $('.delete-payment').on('click', function () {
        if (!confirm('Are you sure you want to delete this payment?')) return;

        const paymentId = $(this).data('id');
        $.ajax({
            url: '<?= BASE_URL ?>ajax/delete_payment.php',
            method: 'POST',
            data: { id: paymentId },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#payment-' + paymentId).fadeOut();
                } else {
                    alert(response.message || 'Failed to delete payment.');
                }
            },
            error: function () {
                alert('Server error. Please try again.');
            }
        });
    });

</script>

</body>
</html>
