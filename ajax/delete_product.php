<?php
session_start();
header('Content-Type: application/json');

include '../config/db.php';
include '../config/constants.php';

// Check authentication and authorization
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access.'
    ]);
    exit();
}

// Validate request method and input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = intval($_POST['id'] ?? 0);

    if ($productId <= 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid product ID.'
        ]);
        exit();
    }

    // Check if product exists
    $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        echo json_encode([
            'status' => 'error',
            'message' => 'Product not found.'
        ]);
        exit();
    }
    $stmt->close();

    // Proceed with deletion
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Product deleted successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete product.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
