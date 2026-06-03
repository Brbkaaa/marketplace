<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('admin');

$db = (new Database())->getConnection();

// Update status
if (isset($_POST['update_status'])) {
    $orderId = (int)$_POST['order_id'];
    $status = $_POST['status'];
    if (in_array($status, ['pending', 'completed', 'cancelled'])) {
        $db->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$status, $orderId]);
    }
    header('Location: manage_orders.php');
    exit();
}

$orders = $db->query("SELECT o.*, u.name AS buyer_name, u.email AS buyer_email FROM orders o JOIN users u ON o.buyer_id = u.id ORDER BY o.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Manage Orders';
require_once '../includes/header.php';
?>

<h2>Manage Orders</h2>

<div class="table-wrap">
<table>
    <tr><th>Order #</th><th>Buyer</th><th>Total</th><th>Status</th><th>Date</th></tr>
    <?php foreach ($orders as $o): ?>
    <tr>
        <td>#<?= $o['id'] ?></td>
        <td><?= htmlspecialchars($o['buyer_name']) ?><br><small><?= htmlspecialchars($o['buyer_email']) ?></small></td>
        <td>$<?= number_format($o['total'], 2) ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                <input type="hidden" name="update_status" value="1">
                <select name="status" onchange="this.form.submit()">
                    <option value="pending" <?= $o['status']==='pending'?'selected':'' ?>>Pending</option>
                    <option value="completed" <?= $o['status']==='completed'?'selected':'' ?>>Completed</option>
                    <option value="cancelled" <?= $o['status']==='cancelled'?'selected':'' ?>>Cancelled</option>
                </select>
            </form>
        </td>
        <td><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php require_once '../includes/footer.php'; ?>
