<?php
session_start();

require_once('../config/constants.php'); // BASE_URL
require_once('../config/db.php');       // $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
    $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $address = mysqli_real_escape_string($conn, $_POST['userAddress'] ?? '');

    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword) || empty($address)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    if ($password !== $confirmPassword) {
        echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
        exit;
    }

    // Check for existing username or email
    $checkQuery = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username or Email already exists.']);
        exit;
    }

    // Handle file upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = '../uploads/users/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $filename; // Save just filename (for URL use later)
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
            exit;
        }
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $role = 'customer';

    $insertQuery = "INSERT INTO users (username, email, password, address, role, profile_image)
                    VALUES ('$username', '$email', '$hashedPassword', '$address', '$role', '$imagePath')";

    if (mysqli_query($conn, $insertQuery)) {
        $userId = mysqli_insert_id($conn);
        
        // Fetch the inserted user
        $result = mysqli_query($conn, "SELECT id, username, role FROM users WHERE id = $userId");
        $user = mysqli_fetch_assoc($result);

        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        echo json_encode([
            'status' => 'success',
            'message' => 'Registration successful',
            'role' => $user['role']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
