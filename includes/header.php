<?php
$cartCount = 0;
$cartTotal = 0;

if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../config/db.php';

    $user_id = $_SESSION['user_id'];
    $cartCount = 0;
    $cartTotal = 0;

    $sql = "
        SELECT sc.quantity, sc.price 
        FROM shopping_cart sc
        WHERE sc.user_id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $cartCount += $row['quantity'];
        $cartTotal += $row['quantity'] * $row['price'];
    }

    $stmt->close();
}

?>


<header class="header header--one">
    <?php if (!isset($_SESSION['user_id'])) { ?>
        <div class="header__top">
            <div class="container">
                <div class="header__top-content">
                    <div class="header__top-left"></div>
                    <div class="header__top-right">
                        <div class="header__in">
                            <a href="<?php echo BASE_URL . 'login.php'; ?>">Login</a>
                            <span>/</span>
                            <a href="<?php echo BASE_URL . 'register.php'; ?>">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="header__center">
        <div class="container">
            <div class="header__center-content">
                <div class="header__brand">
                    <button class="header__sidebar-btn">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 12H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3 6H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M3 18H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                    <a href="<?php echo BASE_URL . 'index.php'; ?>">
                        <img src="./assets/images/logo.png" alt="brand-logo" style="height: 70px; width: auto;" />
                    </a>
                </div>
                <form action="search_product.php" method="GET" class="search-form">
                    <div class="header__input-form">
                        <input type="text" name="query" placeholder="Search products..." />
                        <span class="search-icon">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.16667 16.3333C12.8486 16.3333 15.8333 13.3486 15.8333 9.66667C15.8333 5.98477 12.8486 3 9.16667 3C5.48477 3 2.5 5.98477 2.5 9.66667C2.5 13.3486 5.48477 16.3333 9.16667 16.3333Z"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path d="M17.4999 18L13.8749 14.375" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <button type="submit" class="search-btn button button--md">Search</button>
                    </div>
                </form>
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="header__cart">
                    <div class="header__cart-item">
                        <div class="header__cart-item-content" id="cart-bag">
                            <button class="cart-bag">
                                <svg width="34" height="35" viewBox="0 0 34 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.3333 14.6667H7.08333L4.25 30.25H29.75L26.9167 14.6667H22.6667M11.3333 14.6667V10.4167C11.3333 7.28705 13.8704 4.75 17 4.75V4.75C20.1296 4.75 22.6667 7.28705 22.6667 10.4167V14.6667M11.3333 14.6667H22.6667M11.3333 14.6667V18.9167M22.6667 14.6667V18.9167"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <span class="item-number"><?php echo $cartCount; ?></span>
                            </button>
                            <div class="header__cart-item-content-info">
                                <h5>Shopping cart:</h5>
                                <span class="price">$<?php echo number_format($cartTotal, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="header__logout" style="margin-left: 20px;">
                        <a href="<?php echo BASE_URL . 'logout.php'; ?>" title="Logout" style="display: flex; align-items: center;">
                            <svg style="color: black;" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="header__bottom">
        <div class="container">
            <div class="header__bottom-content">
                <ul class="header__navigation-menu">
                    <li class="header__navigation-menu-link">
                        <a href="<?php echo BASE_URL . 'index.php'; ?>">Home</a>
                    </li>
                    <li class="header__navigation-menu-link">
                        <a href="<?php echo BASE_URL . 'collection.php'; ?>">Collection</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <li class="header__navigation-menu-link">
                            <a href="<?php echo BASE_URL . 'myaccount.php'; ?>">My Account</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="header__sidebar">
        <button class="header__cross">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <div class="header__mobile-sidebar">
            <div class="header__mobile-top">
                <form action="#">
                    <div class="header__mobile-input">
                        <input type="text" placeholder="Search" />
                        <button class="search-btn">
                            <svg width="20" height="21" viewBox="0 0 20 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.16667 16.3333C12.8486 16.3333 15.8333 13.3486 15.8333 9.66667C15.8333 5.98477 12.8486 3 9.16667 3C5.48477 3 2.5 5.98477 2.5 9.66667C2.5 13.3486 5.48477 16.3333 9.16667 16.3333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M17.4999 18L13.8749 14.375" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </div>
                </form>
                <ul class="header__mobile-menu">
                    <li class="header__mobile-menu-item">
                        <a href="<?php echo BASE_URL . 'index.php'; ?>" class="header__mobile-menu-item-link">Home</a>
                    </li>
                    <li class="header__mobile-menu-item">
                        <a href="<?php echo BASE_URL . 'collection.php'; ?>" class="header__mobile-menu-item-link">Collection</a>
                    </li>
                    <?php if (!isset($_SESSION['user_id'])) { ?>
                        <li class="header__mobile-menu-item">
                            <a href="<?php echo BASE_URL . 'myaccount.php'; ?>" class="header__mobile-menu-item-link">My Account</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="header__mobile-bottom">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <div class="header__mobile-user">
                        <div class="header__mobile-user--img">
                            <img src="./assets/images/user/img-07.png" alt="user" />
                        </div>
                        <div class="header__mobile-user--info">
                            <h2 class="font-body--lg-500"><?php echo $_SESSION['username'] ?? 'User'; ?></h2>
                            <p class="font-body--md-400"><?php echo $_SESSION['email'] ?? ''; ?></p>
                        </div>
                        <a href="<?php echo BASE_URL . 'logout.php'; ?>" class="button button--md button--disable mt-2">Logout</a>
                    </div>
                <?php } else { ?>
                    <div class="header__mobile-action">
                        <a href="#" class="button button--md">Login</a>
                        <a href="#" class="button button--md button--disable">Register</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</header>
