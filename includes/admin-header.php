<?php
if (!isset($_SESSION)) {
    session_start();
}

$currentFile = basename($_SERVER['PHP_SELF']);
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
                        <img src="../assets/images/logo.png" alt="brand-logo" style="height: 70px; width: auto;" />
                    </a>
                </div>

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
                        <a href="<?php echo BASE_URL . 'admin/products.php'; ?>">
                            Products
                            <span class="drop-icon">
                                <svg
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                >
                                <path
                                    d="M3.33332 5.66667L7.99999 10.3333L12.6667 5.66667"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                </svg>
                            </span>
                        </a>
                        <ul class="header__navigation-drop-menu">
                            <li class="header__navigation-drop-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/add_product.php'; ?>">Add Product</a>
                            </li>
                        </ul>
                    </li>
                    <li class="header__navigation-menu-link">
                        <a href="<?php echo BASE_URL . 'admin/brands.php'; ?>">
                            Brands
                            <span class="drop-icon">
                                <svg
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                >
                                <path
                                    d="M3.33332 5.66667L7.99999 10.3333L12.6667 5.66667"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                </svg>
                            </span>
                        </a>
                        <ul class="header__navigation-drop-menu">
                            <li class="header__navigation-drop-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/add_brand.php'; ?>">Add Brand</a>
                            </li>
                        </ul>
                    </li>
                    <li class="header__navigation-menu-link">
                        <a href="<?php echo BASE_URL . 'admin/categories.php'; ?>">
                            Category
                            <span class="drop-icon">
                                <svg
                                width="16"
                                height="16"
                                viewBox="0 0 16 16"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                >
                                <path
                                    d="M3.33332 5.66667L7.99999 10.3333L12.6667 5.66667"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                </svg>
                            </span>
                        </a>
                        <ul class="header__navigation-drop-menu">
                            <li class="header__navigation-drop-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/add_category.php'; ?>">Add Category</a>
                            </li>
                        </ul>
                    </li>
                    <li class="header__navigation-menu-link">
                        <a href="<?php echo BASE_URL . 'admin/all_orders.php'; ?>">All Orders</a>
                    </li>
                    <li class="header__navigation-menu-link">
                        <a href="<?php echo BASE_URL . 'admin/users.php'; ?>">Users</a>
                    </li>
                    <li class="header__navigation-menu-link">
                        <a href="<?php echo BASE_URL . 'admin/all_payments.php'; ?>">Payments</a>
                    </li>
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
                        <a href="javascript:void(0);" class="header__mobile-menu-item-link">Products
                            <span class="drop-icon">
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 16 16"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                    d="M3.33332 5.66667L7.99999 10.3333L12.6667 5.66667"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                        </a>
                        <ul class="header__mobile-dropdown-menu">
                            <li class="header__mobile-dropdown-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/products.php'; ?>">Products</a>
                            </li>
                            <li class="header__mobile-dropdown-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/add_product.php'; ?>">Add Product</a>
                            </li>
                        </ul>
                    </li>
                    <li class="header__mobile-menu-item">
                        <a href="javascript:void(0);" class="header__mobile-menu-item-link">Brands
                            <span class="drop-icon">
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 16 16"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                    d="M3.33332 5.66667L7.99999 10.3333L12.6667 5.66667"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                        </a>
                        <ul class="header__mobile-dropdown-menu">
                            <li class="header__mobile-dropdown-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/brands.php'; ?>">Brands</a>
                            </li>
                            <li class="header__mobile-dropdown-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/add_brand.php'; ?>">Add Brand</a>
                            </li>
                        </ul>
                    </li>
                    <li class="header__mobile-menu-item">
                        <a href="javascript:void(0);" class="header__mobile-menu-item-link">Category
                            <span class="drop-icon">
                                <svg
                                    width="16"
                                    height="16"
                                    viewBox="0 0 16 16"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                    d="M3.33332 5.66667L7.99999 10.3333L12.6667 5.66667"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                        </a>
                        <ul class="header__mobile-dropdown-menu">
                            <li class="header__mobile-dropdown-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/categories.php'; ?>">Category</a>
                            </li>
                            <li class="header__mobile-dropdown-menu-link">
                                <a href="<?php echo BASE_URL . 'admin/add_category.php'; ?>">Add Category</a>
                            </li>
                        </ul>
                    </li>
                    <li class="header__mobile-menu-item">
                        <a href="<?php echo BASE_URL . 'admin/all_orders.php'; ?>" class="header__mobile-menu-item-link">All Orders
                        </a>
                        
                    </li>
                    <li class="header__mobile-menu-item">
                        <a href="<?php echo BASE_URL . 'admin/users.php'; ?>" class="header__mobile-menu-item-link">Users
                        </a>
                        
                    </li>
                    <li class="header__mobile-menu-item">
                        <a href="<?php echo BASE_URL . 'admin/all_payments.php'; ?>" class="header__mobile-menu-item-link">Payments
                        </a>
                        
                    </li>
                </ul>
            </div>
            <div class="header__mobile-bottom">
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <div class="header__mobile-user">
                        <div class="header__mobile-user--img">
                            <img src="../assets/images/user/img-03.png" alt="user" />
                        </div>
                        <div class="header__mobile-user--info">
                            <h2 class="font-body--lg-500"><?php echo $_SESSION['username'] ?? 'User'; ?></h2>
                            <p class="font-body--md-400"><?php echo $_SESSION['email'] ?? ''; ?></p>
                        </div>
                        
                    </div>
                    <div class="mx-3 mt-3">
                        <a href="<?php echo BASE_URL . 'logout.php'; ?>" class="button button--md button--disable mt-2">Logout</a>
                    <div>
                <?php } else { ?>
                    <div class="header__mobile-action">
                        <a href=<?php echo BASE_URL . 'login.php'; ?> class="button button--md">Login</a>
                        <a href=<?php echo BASE_URL . 'register.php'; ?> class="button button--md button--disable">Register</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</header>
