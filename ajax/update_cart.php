<?php
session_start();
require '../config/db.php';

$user_id = $_SESSION['user_id'] ?? null;

$data = json_decode(file_get_contents("php://input"), true);

if (!$user_id) {
    http_response_code(401);
    echo 'Unauthorized';
    exit;
}

if (!is_array($data)) {
    http_response_code(400);
    echo 'Invalid input';
    exit;
}

foreach ($data as $item) {
    $cart_id = intval($item['cart_id']);
    $quantity = intval($item['quantity']);

    if ($quantity < 1) continue;

    $stmt = $conn->prepare("UPDATE shopping_cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $quantity, $cart_id, $user_id);
    $stmt->execute();
}

echo 'success';
