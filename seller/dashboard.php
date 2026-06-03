<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole(['seller', 'admin']);

$db = (new Database())->getConnection();
$stmt = $db->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Seller Dashboard';
require_once '../includes/header.php';
?>

<h2>My Products</h2>
<a href="add_product.php" class="btn">+ Add New Product</a>

<?php if (empty($products)): ?>
    <p style="margin-top:20px;">You haven't added any products yet.</p>
<?php else: ?>
    <div class="table-wrap">
    <table>
        <tr><th>Image</th><th>Title</th><th>Price</th><th>Date</th><th>Actions</th></tr>
        <?php foreach ($products as $p): ?>
        <tr>
            <td><img src="/marketplace/uploads/<?= htmlspecialchars($p['image'] ?? 'placeholder.jpg') ?>" style="width:50px;height:50px;object-fit:cover;border-radius:4px;"></td>
            <td><?= htmlspecialchars($p['title']) ?></td>
            <td>$<?= number_format($p['price'], 2) ?></td>
            <td><?= date('M d, Y', strtotime($p['created_at'])) ?></td>
            <td>
                <a href="edit_product.php?id=<?= $p['id'] ?>">Edit</a> |
                <a href="delete_product.php?id=<?= $p['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>
