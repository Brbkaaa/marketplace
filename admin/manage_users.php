<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('admin');

$db = (new Database())->getConnection();

// Delete user
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== $_SESSION['user_id']) {
        $db->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    }
    header('Location: manage_users.php');
    exit();
}

// Change role
if (isset($_POST['change_role'])) {
    $id = (int)$_POST['user_id'];
    $role = $_POST['new_role'];
    if (in_array($role, ['buyer', 'seller', 'admin']) && $id !== $_SESSION['user_id']) {
        $db->prepare("UPDATE users SET role = ? WHERE id = ?")->execute([$role, $id]);
    }
    header('Location: manage_users.php');
    exit();
}

$users = $db->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Manage Users';
require_once '../includes/header.php';
?>

<h2>Manage Users</h2>

<div class="table-wrap">
<table>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= htmlspecialchars($u['name']) ?></td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                <select name="new_role" onchange="this.form.submit()" <?= $u['id'] === $_SESSION['user_id'] ? 'disabled' : '' ?>>
                    <option value="buyer" <?= $u['role']==='buyer'?'selected':'' ?>>Buyer</option>
                    <option value="seller" <?= $u['role']==='seller'?'selected':'' ?>>Seller</option>
                    <option value="admin" <?= $u['role']==='admin'?'selected':'' ?>>Admin</option>
                </select>
                <input type="hidden" name="change_role" value="1">
            </form>
        </td>
        <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
        <td>
            <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                <a href="user_notes.php?id=<?= $u['id'] ?>">Notes</a> |
                <a href="manage_users.php?delete=<?= $u['id'] ?>" onclick="return confirm('Delete this user?')">Delete</a>
            <?php else: ?>
                (you)
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php require_once '../includes/footer.php'; ?>
