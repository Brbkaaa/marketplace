<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$db = (new Database())->getConnection();
$stmt = $db->query("SELECT p.*, u.name AS seller_name FROM products p JOIN users u ON p.seller_id = u.id ORDER BY p.created_at DESC LIMIT 6");
$featured = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Home - Marketplace';
require_once 'includes/header.php';
?>

<section class="hero">
    <h1>Welcome to Marketplace</h1>
    <p>Buy and sell products with ease</p>
    <a href="shop.php" class="btn" style="background:white;color:hsl(210,70%,50%);">Browse Shop</a>
</section>

<h2>Latest Products</h2>
<div class="product-grid">
    <?php if (empty($featured)): ?>
        <p>No products yet. Be the first to sell!</p>
    <?php else: ?>
        <?php foreach ($featured as $product): ?>
            <article class="product-card">
                <figure>
                    <img src="/marketplace/uploads/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['title']) ?>">
                </figure>
                <h3><?= htmlspecialchars($product['title']) ?></h3>
                <p class="price">$<?= number_format($product['price'], 2) ?></p>
                <p class="seller">by <?= htmlspecialchars($product['seller_name']) ?></p>
                <div class="card-actions">
                    <a href="product.php?id=<?= $product['id'] ?>" class="btn btn-sm">View</a>
                    <?php if (isLoggedIn()): ?>
                        <a href="cart.php?add=<?= $product['id'] ?>&ref=index.php" class="btn btn-sm btn-cart">🛒 Add</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
