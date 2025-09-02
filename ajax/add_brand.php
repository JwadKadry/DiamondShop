<?php
session_start();
header('Content-Type: application/json');

include '../config/db.php';
include '../config/constants.php';

// Check if the user is logged in and is admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access.'
    ]);
    exit();
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brandName = trim($_POST['brand_name'] ?? '');

    if (empty($brandName)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Brand name is required.'
        ]);
        exit();
    }

    // Check for duplicate
    $stmt = $conn->prepare("SELECT id FROM brands WHERE name = ?");
    $stmt->bind_param("s", $brandName);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        echo json_encode([
            'status' => 'error',
            'message' => 'Brand already exists.'
        ]);
        exit();
    }

    $stmt->close();

    // Insert new brand
    $stmt = $conn->prepare("INSERT INTO brands (name) VALUES (?)");
    $stmt->bind_param("s", $brandName);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Brand added successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add brand.'
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
