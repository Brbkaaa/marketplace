<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Marketplace' ?></title>
    <link rel="stylesheet" href="/marketplace/css/styles.css">
</head>
<body>
<header class="header">
    <div class="container">
        <a href="/marketplace/index.php" class="logo">🛒 Marketplace</a>
        <nav class="nav" id="mainNav">
            <a href="/marketplace/index.php">Home</a>
            <a href="/marketplace/shop.php">Shop</a>
            <?php if (isLoggedIn()): ?>
                <a href="/marketplace/cart.php">Cart</a>
                <?php if ($_SESSION['role'] === 'seller'): ?>
                    <a href="/marketplace/seller/dashboard.php">Seller Panel</a>
                <?php endif; ?>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/marketplace/admin/dashboard.php">Admin Panel</a>
                <?php endif; ?>
                <a href="/marketplace/profile.php"><?= htmlspecialchars($_SESSION['user_name']) ?></a>
                <a href="/marketplace/logout.php">Logout</a>
            <?php else: ?>
                <a href="/marketplace/login.php">Login</a>
                <a href="/marketplace/register.php">Register</a>
            <?php endif; ?>
        </nav>
        <button class="nav-toggle" id="navToggle">☰</button>
        <button class="dark-toggle" id="darkToggle">🌙</button>
    </div>
</header>
<main class="main">
