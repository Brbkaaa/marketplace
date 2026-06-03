<?php
session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    echo json_encode(['success' => true, 'count' => array_sum($_SESSION['cart'])]);
} else {
    echo json_encode(['success' => false]);
}
