<?php
session_start();
include '../config/db.php';
include '../config/constants.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ' . BASE_URL . 'login.php');
    exit();
}
// Fetch categories & brands
$cats = $conn->query("SELECT id, name FROM categories");
$brands = $conn->query("SELECT id, name FROM brands");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Add Product | Diamond Shop</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/lib/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css"/>
</head>
<body>
<?php include '../includes/admin-header.php'; ?>

<section class="section section--xl">
  <div class="container">
    <h2>Add Product</h2>

    <form id="productForm" enctype="multipart/form-data" class="needs-validation" novalidate>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Title</label>
          <input type="text" name="title" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Price</label>
          <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Category</label>
          <select name="category_id" class="form-select" required>
            <option value="">Choose</option>
            <?php while ($r = $cats->fetch_assoc()): ?>
              <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label class="form-label">Brand</label>
          <select name="brand_id" class="form-select" required>
            <option value="">Choose</option>
            <?php while ($r = $brands->fetch_assoc()): ?>
              <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="col-12">
          <label class="form-label">Keywords (comma-separated)</label>
          <input type="text" name="keywords" class="form-control">
        </div>
        <div class="col-md-4">
          <label class="form-label">Image 1</label>
          <input type="file" name="image_1" class="form-control" accept="image/*" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Image 2</label>
          <input type="file" name="image_2" class="form-control" accept="image/*">
        </div>
        <div class="col-md-4">
          <label class="form-label">Image 3</label>
          <input type="file" name="image_3" class="form-control" accept="image/*">
        </div>
        <div class="col-12">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>
        <div class="col-12 text-end">
          <button type="submit" class="btn btn-primary">Create Product</button>
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
$('#productForm').on('submit', function(e){
  e.preventDefault();
  if(!this.checkValidity()) { $(this).addClass('was-validated'); return; }
  
  var formData = new FormData(this);
  $.ajax({
    url: '<?= BASE_URL ?>ajax/add_product.php',
    type: 'POST',
    data: formData,
    processData: false, contentType: false,
    success: function(res){
      if(res.status==='success') {
        $('#msgBox').html(`<div class="alert alert-success">${res.message}</div>`);
        $('#productForm')[0].reset();
      } else {
        $('#msgBox').html(`<div class="alert alert-danger">${res.message}</div>`);
      }
    },
    error: ()=> $('#msgBox').html(`<div class="alert alert-danger">Server error</div>`)
  });
});
</script>
</body>
</html>
