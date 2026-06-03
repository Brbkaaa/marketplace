<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('admin');

$db = (new Database())->getConnection();
$userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$productCount = $db->query("SELECT COUNT(*) FROM products")->fetchColumn();
$orderCount = $db->query("SELECT COUNT(*) FROM orders")->fetchColumn();

$pageTitle = 'Admin Dashboard';
require_once '../includes/header.php';
?>

<h2>Admin Dashboard</h2>

<div class="admin-stats">
    <div class="stat-card"><h3><?= $userCount ?></h3><p>Users</p></div>
    <div class="stat-card"><h3><?= $productCount ?></h3><p>Products</p></div>
    <div class="stat-card"><h3><?= $orderCount ?></h3><p>Orders</p></div>
</div>

<div style="margin-top:30px;">
    <a href="manage_users.php" class="btn">Manage Users</a>
    <a href="manage_products.php" class="btn">Manage Products</a>
    <a href="manage_orders.php" class="btn">Manage Orders</a>
    <a href="export_users.php" class="btn">Export Users to File</a>
</div>

<?php require_once '../includes/footer.php'; ?>
