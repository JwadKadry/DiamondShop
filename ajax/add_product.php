<?php
session_start();
header('Content-Type: application/json');
include '../config/db.php';
include '../config/constants.php';

if(!isset($_SESSION['user_id'])||($_SESSION['role'] ?? '')!=='admin'){
  echo json_encode(['status'=>'error','message'=>'Unauthorized']); exit;
}
if($_SERVER['REQUEST_METHOD']!=='POST'){
  echo json_encode(['status'=>'error','message'=>'Invalid request']); exit;
}

// Sanitize inputs
$title = trim($_POST['title'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$cat = intval($_POST['category_id'] ?? 0);
$brand = intval($_POST['brand_id'] ?? 0);
$desc = trim($_POST['description'] ?? '');
$keywords = trim($_POST['keywords'] ?? '');
$status = in_array($_POST['status'], ['active','inactive']) ? $_POST['status'] : 'inactive';

if(!$title||!$price||!$cat||!$brand||!$desc){
  echo json_encode(['status'=>'error','message'=>'All required fields must be filled.']); exit;
}

// Handle uploads
$imgPath = [];
for($i=1;$i<=3;++$i){
  if(!empty($_FILES["image_$i"]['tmp_name'])){
    $dest = time()."_{$i}_".basename($_FILES["image_$i"]['name']);
    $fullPath = '../uploads/products/'.$dest;
    if(!is_dir('../uploads/products/')) mkdir('../uploads/products/',0755,true);
    if(move_uploaded_file($_FILES["image_$i"]['tmp_name'], $fullPath)){
      $imgPath["image_$i"] = 'uploads/products/'.$dest;
    }
  }
}

// Prepare SQL
$sql = "INSERT INTO products (title,description,keywords,category_id,brand_id,price,status,image_1,image_2,image_3)
        VALUES (?,?,?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$image1 = $imgPath['image_1'] ?? null;
$image2 = $imgPath['image_2'] ?? null;
$image3 = $imgPath['image_3'] ?? null;

$stmt->bind_param(
  "sssiisssss",
  $title, $desc, $keywords, $cat, $brand, $price, $status,
  $image1, $image2, $image3
);


if($stmt->execute()){
  echo json_encode(['status'=>'success','message'=>'Product created!']);
} else {
  echo json_encode(['status'=>'error','message'=>'DB error: '.$stmt->error]);
}
$stmt->close();
