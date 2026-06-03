<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole(['seller', 'admin']);

$db = (new Database())->getConnection();
$id = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

header('Location: dashboard.php');
exit();
