<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}

$productId = intval($_GET['id'] ?? 0);
$product = [];

if ($productId > 0) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        header("Location: products.php");
        exit();
    }
}

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
$brands = $conn->query("SELECT id, name FROM brands ORDER BY name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Edit Product | Diamond Shop</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css"/>
</head>
<body>
<?php include '../includes/admin-header.php'; ?>

<section class="section section--xl">
  <div class="container">
    <h2>Edit Product</h2>

    <form id="editProductForm" enctype="multipart/form-data" class="needs-validation" novalidate>
      <input type="hidden" name="product_id" value="<?= $productId ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($product['title']) ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Price</label>
          <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select" required>
            <option value="">Choose</option>
            <?php while ($cat = $categories->fetch_assoc()): ?>
              <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Brand</label>
          <select name="brand_id" class="form-select" required>
            <option value="">Choose</option>
            <?php while ($brand = $brands->fetch_assoc()): ?>
              <option value="<?= $brand['id'] ?>" <?= $brand['id'] == $product['brand_id'] ? 'selected' : '' ?>><?= htmlspecialchars($brand['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="col-12">
          <label class="form-label">Keywords (comma-separated)</label>
          <input type="text" name="keywords" class="form-control" value="<?= htmlspecialchars($product['keywords']) ?>">
        </div>

        <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="col-md-4">
                <label class="form-label">Image <?= $i ?></label>
                <div class="mb-2 preview-box" style="height: 120px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px;">
                <img id="preview_<?= $i ?>" 
                    src="<?= !empty($product["image_$i"]) ? BASE_URL . $product["image_$i"] : '' ?>" 
                    alt="Image <?= $i ?>" 
                    style="max-height: 100%; max-width: 100%; object-fit: contain; <?= empty($product["image_$i"]) ? 'display:none;' : '' ?>">
                <?php if (empty($product["image_$i"])): ?>
                    <span id="no_preview_<?= $i ?>" style="color: #aaa;">No Image</span>
                <?php endif; ?>
                </div>
                <input type="file" name="image_<?= $i ?>" class="form-control" accept="image/*" onchange="previewImage(event, <?= $i ?>)">
            </div>
        <?php endfor; ?>


        <div class="col-12">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
          </select>
        </div>
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-primary">Update Product</button>
          <a href="products.php" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
    </form>

    <div id="msgBox" class="mt-3"></div>

  </div>
</section>
<script src="<?= BASE_URL ?>assets/lib/js/jquery.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/venobox.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/swiper-bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/bvselect.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>assets/lib/js/jquery.syotimer.min.js"></script>
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
<script>
    $('#editProductForm').on('submit', function(e){
    e.preventDefault();
    if(!this.checkValidity()) { $(this).addClass('was-validated'); return; }

    var formData = new FormData(this);
    $.ajax({
        url: '<?= BASE_URL ?>ajax/edit_product.php',
        type: 'POST',
        data: formData,
        processData: false, contentType: false,
        success: function(res){
        if(res.status==='success') {
            $('#msgBox').html(`<div class="alert alert-success">${res.message}</div>`);
            setTimeout(() => window.location.href = 'products.php', 1000);
        } else {
            $('#msgBox').html(`<div class="alert alert-danger">${res.message}</div>`);
        }
        },
        error: ()=> $('#msgBox').html(`<div class="alert alert-danger">Server error</div>`)
    });
    });
</script>
<script>
  function previewImage(event, index) {
    const fileInput = event.target;
    const file = fileInput.files[0];
    const preview = document.getElementById('preview_' + index);
    const noPreview = document.getElementById('no_preview_' + index);

    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
        if (noPreview) noPreview.style.display = 'none';
      }
      reader.readAsDataURL(file);
    } else {
      preview.style.display = 'none';
      if (noPreview) noPreview.style.display = 'block';
    }
  }
</script>

</body>
</html>
