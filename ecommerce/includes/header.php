<?php
require_once __DIR__ . '/../database/connection.php';
require_once __DIR__ . '/../includes/auth.php';

$cartCount = isLoggedIn() ? getCartCount($conn, $_SESSION['user_id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - MyShop' : 'MyShop' ?></title>
    <link rel="stylesheet" href="/ecommerce/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="navbar">
    <div class="nav-container">
        <a href="/ecommerce/index.php" class="nav-logo">🛒 MyShop</a>
        <div class="nav-links">
            <a href="/ecommerce/index.php">Home</a>
            <a href="/ecommerce/pages/products.php">Products</a>
            <?php if (isLoggedIn()): ?>
                <a href="/ecommerce/pages/cart.php">Cart 
                    <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
                <?php if (isAdmin()): ?>
                    <a href="/ecommerce/admin/dashboard.php" class="btn-nav-admin">Admin Panel</a>
                <?php endif; ?>
                <a href="/ecommerce/pages/logout.php" class="btn-nav-logout">Logout (<?= $_SESSION['username'] ?>)</a>
            <?php else: ?>
                <a href="/ecommerce/pages/login.php">Login</a>
                <a href="/ecommerce/pages/register.php" class="btn-nav-register">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="main-content">
