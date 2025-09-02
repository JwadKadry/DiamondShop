<?php

require './config/db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

// Fetch pending orders (handles 'Pending', 'Pending (Offline)', 'Pending (PayPal)', etc.)
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND order_status LIKE 'Pending%' ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

<div class="dashboard__content-card">
    <div class="dashboard__content-card-header">
        <h5 class="font-body--xxl-500">Pending Orders</h5>
    </div>
    <div class="mt-4 text-end">
        <a href="collection.php" class="btn btn-primary m-2">Explore Products</a>
    </div>
    <div class="dashboard__content-card-body">
        <?php if ($result->num_rows > 0): ?>
            <div class="table-responsive">
                <table id="pendingOrdersTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Invoice</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                                <td><?= htmlspecialchars(date("Y-m-d H:i", strtotime($order['created_at']))) ?></td>
                                <td>$<?= number_format((float)$order['amount_due'], 2) ?></td>
                                <td><?= htmlspecialchars($order['invoice_number']) ?></td>
                                <td>
                                    <?php if ($order['order_status'] === 'Complete'): ?>
                                        Paid
                                    <?php else: ?>
                                        <a href="confirm_payment.php?order_id=<?= urlencode($order['id']) ?>" class="btn btn-success btn-sm">Confirm</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No pending orders found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- jQuery -->
<script src="<?= BASE_URL ?>assets/lib/js/jquery.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $('#pendingOrdersTable').DataTable({
        pageLength: 10,
        lengthChange: true,
        ordering: true,
        searching: true
    });
</script>
