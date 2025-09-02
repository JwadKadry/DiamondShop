<?php
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: login.php');
    exit;
}
$user = [];
// Fetch user info from database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Handle image upload
    $profile_image_path = '';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $image_tmp = $_FILES['profile_image']['tmp_name'];
        $image_name = basename($_FILES['profile_image']['name']);
        $target_dir = "./uploads/users/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $target_file = $target_dir . time() . '_' . $image_name;
        $filename = time() . '_' . $image_name;
        if (move_uploaded_file($image_tmp, $target_file)) {
            $profile_image_path = $filename;
        }
    }

    if ($profile_image_path) {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, address = ?, profile_image = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $username, $email, $address, $profile_image_path, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, address = ? WHERE id = ?");
        $stmt->bind_param("sssi", $username, $email, $address, $user_id);
    }

    $stmt->execute();
}
?>

<div class="container">
    <div class="dashboard__content-card">
        <div class="dashboard__content-card-header">
        <h5 class="font-body--xxl-500">Edit Account</h5>
        </div>
        <div class="dashboard__content-card-body">
            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-7 order-lg-0 order-2">
                        
                            <div class="contact-form__content">
                                <div class="contact-form-input">
                                    <label for="fname1">Username </label>
                                    <input
                                        type="text"
                                        id="username"
                                        name="username"
                                        value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                                        placeholder="Username"
                                    />
                                </div>
                                
                                <div class="contact-form-input">
                                    <label for="email1">Email </label>
                                    <input
                                    type="email"
                                    id="email1"
                                    name="email"
                                    placeholder="dianne.russell@gmail.com"
                                    value="<?= htmlspecialchars($user['email']) ?>"
                                    />
                                </div>
                            
                                <div class="contact-form-input">
                                    <label for="address">Address</label>
                                    <input
                                    type="text"
                                    id="address"
                                    name="address"
                                    placeholder="Wales"
                                    value="<?= htmlspecialchars($user['address']) ?>"
                                    />
                                </div>
                                <div class="contact-form-btn">
                                    <button class="button button--md" type="submit">
                                    Save Changes
                                    </button>
                                </div>
                            </div>
                        
                    </div>
                    <div class="col-lg-5 order-lg-0 order-1">
                        
                        <div class="dashboard__content-card-img">
                            <div>
                                <div class="dashboard__content-img-wrapper">
                                    <div id="imagePreview" style="background-image: url('<?= $user['profile_image'] ? './uploads/users/' . $user['profile_image'] : '/assets/images/user/img-07.png' ?>');"></div>
                                </div>
                                <div class="upload-image button button--outline" style="width: 100%;">
                                    <input type='file' name="profile_image" accept=".png, .jpg, .jpeg" id="imageUpload"/>
                                    <label for="imageUpload">Choose Image</label>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>