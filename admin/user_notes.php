<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole('admin');

$db = (new Database())->getConnection();
$userId = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) { header('Location: manage_users.php'); exit(); }

$notesFile = '../uploads/user_notes_' . $userId . '.txt';
$success = '';

// Write note
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = trim($_POST['note'] ?? '');
    if ($note) {
        $entry = "[" . date('Y-m-d H:i:s') . "] " . $note . "\n";
        file_put_contents($notesFile, $entry, FILE_APPEND);
        $success = 'Note saved.';
    }
}

// Read existing notes
$notes = file_exists($notesFile) ? file_get_contents($notesFile) : '';

$pageTitle = 'User Notes';
require_once '../includes/header.php';
?>

<section class="auth-form" style="max-width:700px;">
    <h2>Notes: <?= htmlspecialchars($user['name']) ?></h2>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Role:</strong> <?= $user['role'] ?></p>
    <p><strong>Joined:</strong> <?= date('M d, Y', strtotime($user['created_at'])) ?></p>

    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <form method="POST" style="margin-top:20px;">
        <label for="note">Add Note</label>
        <textarea id="note" name="note" rows="3" required style="width:100%;padding:10px;border:2px solid #e8e8f0;border-radius:12px;" placeholder="Write a note about this user..."></textarea>
        <button type="submit">Save Note</button>
    </form>

    <?php if ($notes): ?>
        <h3 style="margin-top:25px;">Saved Notes:</h3>
        <pre style="background:#f8f8fc;padding:15px;border-radius:12px;margin-top:10px;white-space:pre-wrap;border:1px solid #e8e8f0;"><?= htmlspecialchars($notes) ?></pre>
    <?php else: ?>
        <p style="margin-top:20px;color:#888;">No notes yet.</p>
    <?php endif; ?>

    <a href="manage_users.php" class="btn" style="margin-top:20px;">← Back to Users</a>
</section>

<?php require_once '../includes/footer.php'; ?>
