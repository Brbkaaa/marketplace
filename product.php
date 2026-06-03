<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$db = (new Database())->getConnection();
$id = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT p.*, u.name AS seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE p.id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) { header('Location: shop.php'); exit(); }

$catStmt = $db->prepare("SELECT c.name FROM categories c JOIN product_categories pc ON c.id = pc.category_id WHERE pc.product_id = ?");
$catStmt->execute([$id]);
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = $product['title'];
require_once 'includes/header.php';
?>

<div class="product-detail">
    <img src="/marketplace/uploads/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['title']) ?>">
    <div class="product-info">
        <h1><?= htmlspecialchars($product['title']) ?></h1>
        <p class="price">$<?= number_format($product['price'], 2) ?></p>
        <p>Sold by: <strong><?= htmlspecialchars($product['seller_name']) ?></strong></p>
        <?php if ($categories): ?>
            <p>Categories: <?= htmlspecialchars(implode(', ', $categories)) ?></p>
        <?php endif; ?>
        <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <?php if (isLoggedIn()): ?>
            <a href="cart.php?add=<?= $product['id'] ?>" class="btn btn-cart">Add to Cart</a>
        <?php else: ?>
            <a href="login.php" class="btn">Login to Buy</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
