<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$db = (new Database())->getConnection();
$categoryFilter = $_GET['category'] ?? '';

$sql = "SELECT p.*, u.name AS seller_name FROM products p JOIN users u ON p.seller_id = u.id WHERE 1=1";
$params = [];

if ($categoryFilter) {
    $sql .= " AND p.id IN (SELECT product_id FROM product_categories WHERE category_id = ?)";
    $params[] = $categoryFilter;
}
$sql .= " ORDER BY p.created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Shop';
require_once 'includes/header.php';
?>

<h2>Shop</h2>

<form method="GET" style="margin-bottom:20px;display:flex;gap:10px;flex-wrap:wrap;">
    <input type="text" id="searchInput" placeholder="Search products..." style="padding:8px 14px;border:1px solid #ddd;border-radius:6px;flex:1;min-width:200px;">
    <select name="category" onchange="this.form.submit()" style="padding:8px;border-radius:6px;border:1px solid #ddd;">
        <option value="">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $categoryFilter == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
        <?php endforeach; ?>
    </select>
</form>

<div class="product-grid">
    <?php if (empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
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
                        <a href="cart.php?add=<?= $product['id'] ?>&ref=shop.php" class="btn btn-sm btn-cart">🛒 Add</a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
