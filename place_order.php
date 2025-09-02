<?php
session_start();
require './config/db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit();
}

$payment_method = $_POST['payment_method'] ?? 'offline';

// Fetch cart items
$query = "
    SELECT sc.product_id, sc.quantity, sc.price
    FROM shopping_cart sc
    WHERE sc.user_id = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total_amount = 0;
$total_products = 0;

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_amount += $row['price'] * $row['quantity'];
    $total_products += $row['quantity'];
}

if (empty($cart_items)) {
    echo "<script>alert('Your cart is empty.'); window.location.href='cart.php';</script>";
    exit();
}

// Generate random invoice number
$invoice_number = strtoupper(uniqid('INV'));

// Set order status
$order_status = ($payment_method === 'paypal') ? 'Pending (PayPal)' : 'Pending (Offline)';

// Insert into orders table
$order_stmt = $conn->prepare("
    INSERT INTO orders (user_id, amount_due, invoice_number, total_products, order_status)
    VALUES (?, ?, ?, ?, ?)
");
$order_stmt->bind_param("idsis", $user_id, $total_amount, $invoice_number, $total_products, $order_status);
$order_stmt->execute();

$order_id = $order_stmt->insert_id;

// Insert into order_items table
$item_stmt = $conn->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, price)
    VALUES (?, ?, ?, ?)
");

foreach ($cart_items as $item) {
    $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $item_stmt->execute();
}

// Clear cart
$clear_stmt = $conn->prepare("DELETE FROM shopping_cart WHERE user_id = ?");
$clear_stmt->bind_param("i", $user_id);
$clear_stmt->execute();

// Redirect based on payment method
if ($payment_method === 'paypal') {
    echo "<script>
        alert('Redirecting to PayPal...');
        window.open('https://www.sandbox.paypal.com', '_blank');
        window.location.href = 'myaccount.php';
    </script>";
} else {
    echo "<script>
        alert('Order placed with Offline Payment.');
        window.location.href = 'myaccount.php';
    </script>";
}

