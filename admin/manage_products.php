<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('admin');

$db = (new Database())->getConnection();

// Delete product
if (isset($_GET['delete'])) {
    $db->prepare("DELETE FROM products WHERE id = ?")->execute([(int)$_GET['delete']]);
    header('Location: manage_products.php');
    exit();
}

$products = $db->query("SELECT p.*, u.name AS seller_name FROM products p JOIN users u ON p.seller_id = u.id ORDER BY p.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Manage Products';
require_once '../includes/header.php';
?>

<h2>Manage Products</h2>

<div class="table-wrap">
<table>
    <tr><th>ID</th><th>Title</th><th>Price</th><th>Seller</th><th>Date</th><th>Actions</th></tr>
    <?php foreach ($products as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['title']) ?></td>
        <td>$<?= number_format($p['price'], 2) ?></td>
        <td><?= htmlspecialchars($p['seller_name']) ?></td>
        <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
        <td><a href="manage_products.php?delete=<?= $p['id'] ?>" onclick="return confirm('Delete?')">Delete</a></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php require_once '../includes/footer.php'; ?>
