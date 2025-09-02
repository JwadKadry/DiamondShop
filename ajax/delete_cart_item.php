<?php
session_start();
require '../config/db.php';

$cart_id = $_POST['cart_id'] ?? null;

if (!$cart_id) {
    echo 'invalid';
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo 'unauthorized';
    exit;
}

$stmt = $conn->prepare("DELETE FROM shopping_cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $cart_id, $user_id);

if ($stmt->execute()) {
    echo 'success';
} else {
    echo 'error';
}
