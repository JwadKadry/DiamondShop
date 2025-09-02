<?php
session_start();
include('./config/db.php');
include('./config/constants.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    die("Invalid request.");
}

$order_id = (int)$_GET['order_id'];

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows !== 1) {
    die("Order not found.");
}
$order = $result->fetch_assoc();

$invoice_number = $order['invoice_number'];
$amount_due = $order['amount_due'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $invoice_input = $_POST['invoice_number'];
    $amount_str = $_POST['amount'];
    $amount_str = str_replace(',', '', $amount_str); // Remove commas
    $amount = (float)$amount_str;
    $payment_mode = $_POST['payment_mode'];

    if ($payment_mode === "Select Payment Mode" || empty($payment_mode)) {
        echo "<script>alert('Please select a payment method.');</script>";
    } else {
        // Insert payment record
        $stmt = $conn->prepare("INSERT INTO payments (order_id, invoice_number, amount, payment_mode) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isis", $order_id, $invoice_input, $amount, $payment_mode);
        if ($stmt->execute()) {
            // Mark order as complete
            $conn->query("UPDATE orders SET order_status = 'Complete' WHERE id = $order_id");

            echo "<script>alert('Payment confirmed successfully!'); window.location.href = '" . BASE_URL . "myaccount.php?my_orders';</script>";
            exit;
        } else {
            echo "<script>alert('Failed to confirm payment. Try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirm Payment | Diamond Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary">
    <h1 class="text-center text-light mt-5">Confirm Payment</h1>
    <div class="container mt-4">
        <form action="" method="post" class="bg-dark p-4 rounded text-light w-75 m-auto">
            <div class="mb-3">
                <label class="form-label">Invoice Number</label>
                <input type="text" class="form-control" name="invoice_number" value="<?= htmlspecialchars($invoice_number) ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Amount</label>
                <input type="text" class="form-control" name="amount" value="<?= number_format((float)$amount_due, 2) ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Mode</label>
                <select name="payment_mode" class="form-select" required>
                    <option>Select Payment Mode</option>
                    <option>Paypal</option>
                    <option>Cash on Delivery</option>
                    <option>Pay offline</option>
                </select>
            </div>
            <div class="text-center">
                <button type="submit" name="confirm_payment" class="btn btn-info px-4">Confirm</button>
            </div>
        </form>
    </div>
</body>
</html>
