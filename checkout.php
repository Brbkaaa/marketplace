<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
requireLogin();

$db = (new Database())->getConnection();

if (empty($_SESSION['cart'])) { header('Location: cart.php'); exit(); }

// Calculate total
$ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
$stmt = $db->query("SELECT * FROM products WHERE id IN ($ids)");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($products as $p) {
    $total += $p['price'] * $_SESSION['cart'][$p['id']];
}

// Create order
$stmt = $db->prepare("INSERT INTO orders (buyer_id, total) VALUES (?, ?)");
$stmt->execute([$_SESSION['user_id'], $total]);
$orderId = $db->lastInsertId();

// Create order items
$stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach ($products as $p) {
    $stmt->execute([$orderId, $p['id'], $_SESSION['cart'][$p['id']], $p['price']]);
}

// Clear cart
$_SESSION['cart'] = [];

$pageTitle = 'Order Confirmed';
require_once 'includes/header.php';
?>

<section class="auth-form" style="text-align:center;">
    <h2>✓ Order Placed!</h2>
    <p>Order #<?= $orderId ?></p>
    <p>Total: <strong>$<?= number_format($total, 2) ?></strong></p>
    <a href="shop.php" class="btn" style="margin-top:20px;">Continue Shopping</a>
</section>

<?php require_once 'includes/footer.php'; ?>
