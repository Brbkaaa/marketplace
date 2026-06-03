<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
requireLogin();

$db = (new Database())->getConnection();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Add item
if (isset($_GET['add'])) {
    $id = (int)$_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    $back = $_GET['ref'] ?? 'cart.php';
    header('Location: ' . $back);
    exit();
}

// Remove item
if (isset($_GET['remove'])) {
    unset($_SESSION['cart'][(int)$_GET['remove']]);
    header('Location: cart.php');
    exit();
}

// Fetch cart products
$cartItems = [];
$total = 0;
if (!empty($_SESSION['cart'])) {
    $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
    $stmt = $db->query("SELECT * FROM products WHERE id IN ($ids)");
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($cartItems as &$item) {
        $item['qty'] = $_SESSION['cart'][$item['id']];
        $item['subtotal'] = $item['price'] * $item['qty'];
        $total += $item['subtotal'];
    }
}

$pageTitle = 'Cart';
require_once 'includes/header.php';
?>

<h2>Shopping Cart</h2>

<?php if (empty($cartItems)): ?>
    <p>Your cart is empty. <a href="shop.php">Browse products</a></p>
<?php else: ?>
    <?php foreach ($cartItems as $item): ?>
        <div class="cart-item">
            <img src="/marketplace/uploads/<?= htmlspecialchars($item['image'] ?? 'placeholder.jpg') ?>" alt="">
            <div class="info">
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p>$<?= number_format($item['price'], 2) ?> × <?= $item['qty'] ?></p>
            </div>
            <p><strong>$<?= number_format($item['subtotal'], 2) ?></strong></p>
            <a href="cart.php?remove=<?= $item['id'] ?>" class="btn btn-sm" style="background:#dc3545;">Remove</a>
        </div>
    <?php endforeach; ?>

    <div class="cart-total">Total: $<?= number_format($total, 2) ?></div>
    <a href="checkout.php" class="btn" style="margin-top:15px;">Place Order</a>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
