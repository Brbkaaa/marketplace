<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';
requireLogin();

$db = (new Database())->getConnection();
$error = '';
$success = '';

// Password change
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $stmt = $db->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($current, $user['password_hash'])) {
        $error = 'Current password is incorrect.';
    } elseif (strlen($new) < 6) {
        $error = 'New password must be at least 6 characters.';
    } elseif ($new !== $confirm) {
        $error = 'New passwords do not match.';
    } else {
        $hash = password_hash($new, PASSWORD_DEFAULT);
        $db->prepare("UPDATE users SET password_hash = ? WHERE id = ?")->execute([$hash, $_SESSION['user_id']]);
        $success = 'Password changed successfully!';
    }
}

$stmt = $db->prepare("SELECT * FROM orders WHERE buyer_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'My Profile';
require_once 'includes/header.php';
?>

<h2>My Profile</h2>
<div class="auth-form" style="max-width:600px;">
    <p><strong>Name:</strong> <?= htmlspecialchars($_SESSION['user_name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user_email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($_SESSION['role']) ?></p>
</div>

<div class="auth-form" style="max-width:600px;margin-top:30px;">
    <h2>Change Password</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
    <form method="POST">
        <label for="current_password">Current Password</label>
        <input type="password" id="current_password" name="current_password" required>
        <label for="new_password">New Password</label>
        <input type="password" id="new_password" name="new_password" required minlength="6">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
        <button type="submit">Change Password</button>
    </form>
</div>

<h2 style="margin-top:30px;">My Orders</h2>
<?php if (empty($orders)): ?>
    <p>No orders yet.</p>
<?php else: ?>
    <div class="table-wrap">
    <table>
        <tr><th>Order #</th><th>Total</th><th>Status</th><th>Date</th></tr>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td>#<?= $o['id'] ?></td>
            <td>$<?= number_format($o['total'], 2) ?></td>
            <td><?= htmlspecialchars($o['status']) ?></td>
            <td><?= date('M d, Y', strtotime($o['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
