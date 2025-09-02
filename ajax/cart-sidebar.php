<?php
session_start();
require '../config/db.php';

// Handle AJAX delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['cart_id']) && $_POST['action'] === 'delete') {
    $cart_id = (int) $_POST['cart_id'];
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $stmt = $conn->prepare("DELETE FROM shopping_cart WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cart_id, $user_id);
        $stmt->execute();
    }

    exit;
}

// Load cart items
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    $stmt = $conn->prepare("
        SELECT sc.id AS cart_id, sc.quantity, sc.price, 
               p.name, p.image_1
        FROM shopping_cart sc
        JOIN products p ON sc.product_id = p.id
        WHERE sc.user_id = ?
    ");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $items = $stmt->get_result();

    $total = 0;
    $count = $items->num_rows;
} else {
    // Optional: return or show empty cart if user not logged in
    $items = [];
    $total = 0;
    $count = 0;
}
?>

<div class="shopping-cart">
    <div class="shopping-cart-top">
        <div class="shopping-cart-header">
            <h5 class="font-body--xxl-500">Shopping Cart (<span class="count"><?= $count ?></span>)</h5>
            <button class="close">
                <svg width="45" height="45" viewBox="0 0 45 45" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="22.5" cy="22.5" r="22.5" fill="white" />
                    <path d="M28.75 16.25L16.25 28.75" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16.25 16.25L28.75 28.75" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        <?php if ($count > 0): ?>
            <?php while ($row = $items->fetch_assoc()):
                $subtotal = $row['price'] * $row['quantity'];
                $total += $subtotal;
            ?>
            <div class="shopping-cart__product-content">
                <div class="shopping-cart__product-content-item">
                    <div class="img-wrapper">
                        <img src="<?= htmlspecialchars($row['image_1']) ?>" alt="product" />
                    </div>
                    <div class="text-content">
                        <h5 class="font-body--md-400"><?= htmlspecialchars($row['name']) ?></h5>
                        <p class="font-body--md-400"><?= $row['quantity'] ?> x 
                            <span class="font-body--md-500"><?= number_format($row['price'], 2) ?></span>
                        </p>
                    </div>
                </div>
                <button class="delete-item" data-id="<?= $row['cart_id'] ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 23C18.0748 23 23 18.0748 23 12C23 5.92525 18.0748 1 12 1C5.92525 1 1 5.92525 1 12C1 18.0748 5.92525 23 12 23Z" stroke="#CCCCCC" stroke-miterlimit="10" />
                        <path d="M16 8L8 16" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M16 16L8 8" stroke="#666666" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="font-body--md-400 text-center py-4">Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <div class="shopping-cart-bottom">
        <div class="shopping-cart-product-info">
            <p class="product-count font-body--lg-400"><?= $count ?> Product<?= $count !== 1 ? 's' : '' ?></p>
            <span class="product-price font-body--lg-500">$<?= number_format($total, 2) ?></span>
        </div>

        <form action="#">
            <button class="button button--lg w-100">Checkout</button>
            <button class="button button--lg button--disable w-100">go to cart</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function () {
    $('.delete-item').on('click', function () {
        var cartId = $(this).data('id');

        $.ajax({
            url: 'ajax/cart-sidebar.php',
            type: 'POST',
            data: { action: 'delete', cart_id: cartId },
            success: function () {
                $('.shopping-cart').load('ajax/cart-sidebar.php .shopping-cart > *');
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });
});
</script>
