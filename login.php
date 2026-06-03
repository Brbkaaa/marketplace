<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$error = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'timeout') {
    $error = 'Session expired due to inactivity. Please login again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } else {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['last_activity'] = time();

            setcookie('user_role', $user['role'], [
                'expires' => time() + 3600,
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            // Remember me cookie (7 days)
            if ($remember) {
                setcookie('remember_token', $user['id'], [
                    'expires' => time() + 7 * 24 * 3600,
                    'path' => '/',
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);
            }

            header('Location: index.php');
            exit();
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

$pageTitle = 'Login';
require_once 'includes/header.php';
?>

<section class="auth-form">
    <h2>Login</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="POST">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" placeholder="you@example.com" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Your password" required>

        <label style="font-weight:normal;margin-top:15px;display:inline-flex;align-items:center;gap:6px;">
            <input type="checkbox" name="remember" style="width:auto;"> Remember me for 7 days
        </label>

        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</section>

<?php require_once 'includes/footer.php'; ?>
