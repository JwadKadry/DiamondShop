<?php
session_start();
include '../config/db.php';  
include '../config/constants.php'; // contains BASE_URL

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$userRole = $_SESSION['role'] ?? '';

// Redirect based on role
switch ($userRole) {
    case 'admin':
        header('Location: ' . BASE_URL . 'admin/products.php');
        break;

    case 'customer':
        header('Location: ' . BASE_URL . 'index.php');
        break;

    default:
        header('Location: ' . BASE_URL . 'login.php');
        break;
}

exit();
