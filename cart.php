<?php
session_start();
require './config/db.php';
require './config/constants.php';

$user_id = $_SESSION['user_id'] ?? null;

// Build query string dynamically
if ($user_id) {
    $query = "
        SELECT sc.id AS cart_id, sc.quantity, sc.price, 
               p.title, p.image_1, p.id AS product_id 
        FROM shopping_cart sc
        JOIN products p ON sc.product_id = p.id
        WHERE sc.user_id = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    // continue processing result...
} else {
    // Optional: Handle case when user is not logged in
    echo json_encode(["error" => "User not logged in."]);
    exit;
}

// Check if prepare succeeded
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->execute();
$cart_items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Shopping Cart | Diamond Shop</title>
        <link rel="icon" type="image/png" href="./assets/images/favicon.ico" />
        <link rel="stylesheet" href="./assets/lib/css/swiper-bundle.min.css" />
        <link rel="stylesheet" href="./assets/lib/css/bvselect.css" />
        <link rel="stylesheet" href="./assets/lib/css/bootstrap.min.css" />
        <link rel="stylesheet" href="./assets/css/style.css" />
    </head>

    <body>
        <div class="loader">
            <div class="loader-icon">
                <img src="./assets/images/loader.gif" alt="loader" />
            </div>
        </div>
        
        <!-- Header Section start -->
        <?php include './includes/header.php'; ?>
        <!-- Header  Section start -->

        <!-- breedcrumb section start  -->
        <div class="section breedcrumb">
            <div class="breedcrumb__img-wrapper">
                <img src="./assets/images/banner/breedcrumb.jpg" alt="breedcrumb" />
                <div class="container">
                <ul class="breedcrumb__content">
                    <li>
                    <a href="javascript:void(0);">
                        <svg
                        width="18"
                        height="19"
                        viewBox="0 0 18 19"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        >
                        <path
                            d="M1 8L9 1L17 8V18H12V14C12 13.2044 11.6839 12.4413 11.1213 11.8787C10.5587 11.3161 9.79565 11 9 11C8.20435 11 7.44129 11.3161 6.87868 11.8787C6.31607 12.4413 6 13.2044 6 14V18H1V8Z"
                            stroke="#808080"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        </svg>
                        <span> > </span>
                    </a>
                    </li>
                    <li class="active">
                    <a href="javascript: void(0);">Shopping cart</a>
                    </li>
                </ul>
                </div>
            </div>
        </div>
        <!-- breedcrumb section end   -->

        <!-- Shopping Cart Section Start   -->
        <section class="shoping-cart section section--xl">
            <div class="container">
                <div class="section__head justify-content-center">
                    <h2 class="section--title-four font-title--sm">My Shopping Cart</h2>
                </div>
                <div class="row shoping-cart__content">
                    <div class="col-lg-8">
                        <div class="cart-table">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="cart-table-title">Product</th>
                                            <th scope="col" class="cart-table-title">Price</th>
                                            <th scope="col" class="cart-table-title">Quantity</th>
                                            <th scope="col" class="cart-table-title">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartTableBody">
                                        <?php
                                        $total = 0;
                                        if ($cart_items->num_rows > 0):
                                            while ($row = $cart_items->fetch_assoc()):
                                                $subtotal = $row['price'] * $row['quantity'];
                                                $total += $subtotal;
                                        ?>
                                        <tr data-cart-id="<?= $row['cart_id'] ?>">
                                            <!-- Product item -->
                                            <td class="cart-table-item align-middle">
                                                <a href="product-details.html" class="cart-table__product-item">
                                                    <div class="cart-table__product-item-img">
                                                        <img src="<?= htmlspecialchars($row['image_1']) ?>" alt="product" />
                                                    </div>
                                                    <h5 class="font-body--lg-400"><?= htmlspecialchars($row['title']) ?></h5>
                                                </a>
                                            </td>
                                            <!-- Price -->
                                            <td class="cart-table-item order-date align-middle">
                                                $<span class="price"><?= htmlspecialchars($row['price']) ?></span>
                                            </td>
                                            <!-- Quantity -->
                                            <td class="cart-table-item order-total align-middle">
                                                <div class="counter-btn-wrapper">
                                                    <button class="counter-btn-dec counter-btn" onclick="decrement1(this)">-</button>
                                                    <input
                                                        type="number"
                                                        class="counter-btn-counter"
                                                        min="1"
                                                        max="1000"
                                                        value="<?= $row['quantity'] ?>"
                                                        onchange="updateSubtotal(this)"
                                                    />
                                                    <button class="counter-btn-inc counter-btn" onclick="increment1(this)">+</button>
                                                </div>
                                            </td>
                                            <!-- Subtotal -->
                                            <td class="cart-table-item order-subtotal align-middle">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="font-body--md-500">$<span class="subtotal"><?= number_format($subtotal, 2) ?></span></p>
                                                    <button class="delete-item" onclick="removeRow(this)">
                                                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none">
                                                            <path d="M12 23.5C18.0748 23.5 23 18.5748 23 12.5C23 6.42525 18.0748 1.5 12 1.5C5.92525 1.5 1 6.42525 1 12.5C1 18.5748 5.92525 23.5 12 23.5Z" stroke="#CCCCCC" />
                                                            <path d="M16 8.5L8 16.5" stroke="#666666" stroke-width="1.5" stroke-linecap="round" />
                                                            <path d="M16 16.5L8 8.5" stroke="#666666" stroke-width="1.5" stroke-linecap="round" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <p class="font-body--lg-400 text-muted">🛒 Your cart is currently empty.</p>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Action Buttons -->
                            <div class="cart-table-action-btn d-flex">
                                <a href="./collection.php" class="button button--md shop">Return to Shop</a>
                                <?php if ($cart_items->num_rows > 0): ?>
                                    <a href="javascript:void(0);" class="button button--md update" onclick="updateCart()">Update to Cart</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="bill-card">
                            <div class="bill-card__content">
                                <div class="bill-card__header">
                                    <h2 class="bill-card__header-title font-body--xxl-500">Order Summary</h2>
                                </div>
                                <div class="bill-card__body">
                                    <?php if ($cart_items->num_rows > 0): ?>
                                    <!-- memo -->
                                    <div class="bill-card__memo">
                                        <!-- Subtotal -->
                                        <div class="bill-card__memo-item">
                                            <p class="font-body--md-400">Subtotal:</p>
                                            <span class="font-body--md-500" id="cartSubtotal">$<?= number_format($total, 2) ?></span>
                                        </div>
                                        <!-- Shipping -->
                                        <div class="bill-card__memo-item shipping">
                                            <p class="font-body--md-400">Shipping:</p>
                                            <span class="font-body--md-500">Free</span>
                                        </div>
                                        <!-- Total -->
                                        <div class="bill-card__memo-item total">
                                            <p class="font-body--lg-400">Total:</p>
                                            <span class="font-body--xl-500" id="cartTotal">$<?= number_format($total, 2) ?></span>
                                        </div>
                                    </div>
                                    <form action="payment_option.php">
                                        <button class="button button--lg w-100 mt-4" type="submit">Place Order</button>
                                    </form>
                                    <?php else: ?>
                                    <div class="text-center py-4">
                                        <p class="font-body--md-400 text-muted">No summary available. Your cart is empty.</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- Shopping Cart Section End    -->

        <!--Footer Section Start  -->
        <?php include './includes/footer.php'; ?>
        <?php if (isset($_SESSION['user_id'])): ?>
        <!-- Shopping Cart sidebar  start  -->
        <?php include './includes/cart-sidebar.php'; ?>
        <!-- Shopping Cart sidebar  end -->
        <?php endif; ?>
        
        <!-- Shopping Cart sidebar  end -->
        <script src="./assets/lib/js/jquery.min.js"></script>
        <script src="./assets/lib/js/swiper-bundle.min.js"></script>
        <script src="./assets/lib/js/bvselect.js"></script>
        <script src="./assets/lib/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/js/main.js"></script>

        <script>
            function updateCartTotals() {
                let total = 0;
                document.querySelectorAll('.subtotal').forEach(subtotalElement => {
                    const rawText = subtotalElement.textContent.replace(/,/g, ''); // remove commas
                    const subtotal = parseFloat(rawText);
                    console.log(subtotal);
                    total += subtotal;
                });
                console.log(total);
                document.getElementById('cartSubtotal').textContent = `$${total.toFixed(2)}`;
                document.getElementById('cartTotal').textContent = `$${total.toFixed(2)}`;
            }

            function increment1(button) {
                let input = button.previousElementSibling;
                if (input.value < 1000) {
                    input.value = parseInt(input.value) + 1;
                    updateSubtotal(input);
                }
            }

            function decrement1(button) {
                let input = button.nextElementSibling;
                if (input.value > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateSubtotal(input);
                }
            }

            function updateSubtotal(input) {
                
                const tr = input.closest('tr');
                const price = parseFloat(tr.querySelector('.price').textContent);
                const quantity = parseInt(input.value);
                const subtotal = price * quantity;
                console.log(subtotal)
                tr.querySelector('.subtotal').textContent = subtotal.toFixed(2);
                updateCartTotals();
            }

            function removeRow(button) {
                const tr = button.closest('tr');
                const cartId = tr.getAttribute('data-cart-id');

                $.ajax({
                    url: './ajax/delete_cart_item.php',
                    type: 'POST',
                    data: { cart_id: cartId },
                    success: function (response) {
                        if (response.trim() === 'success') {
                            tr.remove();
                            updateCartTotals();
                        } else {
                            alert('Failed to delete cart item.');
                        }
                    },
                    error: function () {
                        alert('AJAX error. Please try again.');
                    }
                });
            }
            function updateCart() {
                const items = [];

                document.querySelectorAll('#cartTableBody tr').forEach(row => {
                    const cartId = row.getAttribute('data-cart-id');
                    const quantity = row.querySelector('.counter-btn-counter').value;

                    items.push({ cart_id: cartId, quantity: quantity });
                });

                $.ajax({
                    url: './ajax/update_cart.php',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(items),
                    success: function (response) {
                        if (response.trim() === 'success') {
                            alert('Cart updated successfully.');
                            location.reload(); // reload page to reflect new prices/subtotals
                        } else {
                            alert('Failed to update cart.');
                        }
                    },
                    error: function () {
                        alert('AJAX error while updating cart.');
                    }
                });
            }

        </script>
    </body>
</html>