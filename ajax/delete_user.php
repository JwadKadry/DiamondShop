<?php
session_start();
header('Content-Type: application/json');

include '../../config/db.php';
include '../../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$userId = intval($_POST['id'] ?? 0);

// Prevent admin from deleting themselves
if ($userId === intval($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You cannot delete your own account.']);
    exit();
}

$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete user.']);
}

$stmt->close();
?>
