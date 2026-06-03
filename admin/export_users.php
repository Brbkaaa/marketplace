<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('admin');

$db = (new Database())->getConnection();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = $db->query("SELECT id, name, email, role, created_at FROM users ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    
    $content = "=== Marketplace Users Export ===\n";
    $content .= "Generated: " . date('Y-m-d H:i:s') . "\n\n";
    
    foreach ($users as $u) {
        $content .= "ID: {$u['id']} | Name: {$u['name']} | Email: {$u['email']} | Role: {$u['role']} | Joined: {$u['created_at']}\n";
    }

    $filepath = '../uploads/users_export.txt';
    file_put_contents($filepath, $content);
    $message = 'Users exported to uploads/users_export.txt';
}

$pageTitle = 'Export Users';
require_once '../includes/header.php';
?>

<section class="auth-form">
    <h2>Export Users to File</h2>
    <?php if ($message): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <p>This will write all user information to a text file.</p>
    <form method="POST">
        <button type="submit">Export Now</button>
    </form>
    <?php if (file_exists('../uploads/users_export.txt')): ?>
        <h3 style="margin-top:20px;">File Contents:</h3>
        <pre style="background:#f4f4f4;padding:15px;border-radius:6px;overflow-x:auto;margin-top:10px;"><?= htmlspecialchars(file_get_contents('../uploads/users_export.txt')) ?></pre>
    <?php endif; ?>
</section>

<?php require_once '../includes/footer.php'; ?>
