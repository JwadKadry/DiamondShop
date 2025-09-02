<?php
// ajax/edit_product.php
session_start();
header('Content-Type: application/json');

include '../config/db.php';
include '../config/constants.php';

// Check admin authentication
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

// Validate request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit();
}

$productId = intval($_POST['product_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$keywords = trim($_POST['keywords'] ?? '');
$categoryId = intval($_POST['category_id'] ?? 0);
$brandId = intval($_POST['brand_id'] ?? 0);
$price = floatval($_POST['price'] ?? 0);
$status = $_POST['status'] ?? 'active';

if (!$productId || !$title || !$description || !$categoryId || !$brandId || !$price) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit();
}

// Fetch current product to update image paths if not changed
$stmt = $conn->prepare("SELECT image_1, image_2, image_3 FROM products WHERE id = ?");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo json_encode(['status' => 'error', 'message' => 'Product not found.']);
    exit();
}

// Handle image uploads
$uploadDir = '../uploads/products/';
$updatedImages = [];

for ($i = 1; $i <= 3; $i++) {
    $field = 'image_' . $i;
    if (!empty($_FILES[$field]['name'])) {
        $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
        $fileName = 'product_' . time() . "_$i." . $ext;
        $targetFile = $uploadDir . $fileName;

        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $targetFile)) {
            echo json_encode(['status' => 'error', 'message' => "Failed to upload image $i."]);
            exit();
        }

        $updatedImages[$field] = 'uploads/products/' . $fileName;
    } else {
        $updatedImages[$field] = $product[$field]; // Keep old image
    }
}

// Update product
$stmt = $conn->prepare("
    UPDATE products 
    SET title = ?, description = ?, keywords = ?, category_id = ?, brand_id = ?, price = ?, 
        image_1 = ?, image_2 = ?, image_3 = ?, status = ?, updated_at = NOW()
    WHERE id = ?
");

$stmt->bind_param(
    "sssiiissssi",
    $title,
    $description,
    $keywords,
    $categoryId,
    $brandId,
    $price,
    $updatedImages['image_1'],
    $updatedImages['image_2'],
    $updatedImages['image_3'],
    $status,
    $productId
);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Product updated successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update product.']);
}

$stmt->close();
?>
