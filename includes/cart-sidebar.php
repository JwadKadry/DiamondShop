<?php
require './config/db.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("User not logged in");
}

$sql = "
    SELECT sc.id AS cart_id, sc.quantity, sc.price, 
           p.title, p.image_1
    FROM shopping_cart sc
    JOIN products p ON sc.product_id = p.id
    WHERE sc.user_id = ?
";

$stmt = $conn->prepare($sql);

// Debug if prepare fails
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);

$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$count = 0;

$stmt->close();
?>

<!-- Shopping Cart sidebar start -->
<div class="shopping-cart">
    <div class="shopping-cart-top">
        <div class="shopping-cart-header">
            <h5 class="font-body--xxl-500">Shopping Cart (<span class="count"><?= $result->num_rows ?></span>)</h5>
            <button class="close">
                <svg width="45" height="45" viewBox="0 0 45 45" fill="none">
                    <circle cx="22.5" cy="22.5" r="22.5" fill="white" />
                    <path d="M28.75 16.25L16.25 28.75" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" />
                    <path d="M16.25 16.25L28.75 28.75" stroke="#1A1A1A" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()):
                $subtotal = $row['price'] * $row['quantity'];
                $total += $subtotal;
                $count++;
            ?>
            <div class="shopping-cart__product-content">
                <div class="shopping-cart__product-content-item">
                    <div class="img-wrapper">
                        <img src="<?= htmlspecialchars($row['image_1']) ?>" alt="product" />
                    </div>
                    <div class="text-content">
                        <h5 class="font-body--md-400"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="font-body--md-400"><?= $row['quantity'] ?> x 
                            <span class="font-body--md-500"><?= number_format($row['price'], 2) ?></span>
                        </p>
                    </div>
                </div>
                <button class="delete-item" data-id="<?= $row['cart_id'] ?>">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M12 23C18.0748 23 23 18.0748 23 12C23 5.92525 18.0748 1 12 1C5.92525 1 1 5.92525 1 12C1 18.0748 5.92525 23 12 23Z" stroke="#CCCCCC" stroke-miterlimit="10" />
                        <path d="M16 8L8 16" stroke="#666666" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M16 16L8 8" stroke="#666666" stroke-width="1.5" stroke-linecap="round" />
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
        <div class="cart">
            <a href="./cart.php" class="button button--lg w-100">go to cart</a>
        </div>
    </div>
</div>
<!-- Shopping Cart sidebar end -->

<!-- jQuery AJAX script -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
$(document).ready(function () {
    $('.delete-item').click(function () {
        var cartId = $(this).data('id');
        var $item = $(this).closest('.shopping-cart__product-content');

        $.ajax({
            url: './ajax/delete_cart_item.php',
            type: 'POST',
            data: { cart_id: cartId },
            success: function () {
                // Remove the item from DOM
                $item.remove();

                // Update all item elements
                var remainingItems = $('.shopping-cart__product-content').length;

                // Update "Shopping Cart (X)" count
                $('.shopping-cart-header .count').text(remainingItems);

                // Update "X Product(s)" text
                $('.shopping-cart-product-info .product-count')
                    .text(`${remainingItems} Product${remainingItems !== 1 ? 's' : ''}`);

                // Recalculate total price
                var total = 0;
                $('.shopping-cart__product-content').each(function () {
                    var quantity = parseFloat($(this).find('.text-content p').text().split(' x ')[0]) || 1;
                    var priceText = $(this).find('.text-content span').text().replace(/,/g, '');
                    var price = parseFloat(priceText) || 0;
                    total += quantity * price;
                });

                $('.shopping-cart-product-info .product-price').text(`$${total.toFixed(2)}`);

                // If cart is empty, show message
                if (remainingItems === 0) {
                    $('.shopping-cart-top').append('<p class="font-body--md-400 text-center py-4">Your cart is empty.</p>');
                }
            },
            error: function () {
                alert('Failed to delete item.');
            }
        });
    });
});
</script>
