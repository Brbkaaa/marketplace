<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole(['seller', 'admin']);

$db = (new Database())->getConnection();
$id = (int)($_GET['id'] ?? 0);

$stmt = $db->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) { header('Location: dashboard.php'); exit(); }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $categories = $_POST['categories'] ?? [];

    if (empty($title) || $price <= 0) {
        $error = 'Title and valid price required.';
    } else {
        $image = $product['image'];
        if (!empty($_FILES['image']['name'])) {
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['image']['size'] <= 2*1024*1024) {
                $image = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
            }
        }

        $db->prepare("UPDATE products SET title=?, description=?, price=?, image=? WHERE id=?")->execute([$title, $description, $price, $image, $id]);
        $db->prepare("DELETE FROM product_categories WHERE product_id=?")->execute([$id]);
        $catStmt = $db->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
        foreach ($categories as $catId) { $catStmt->execute([$id, (int)$catId]); }

        header('Location: dashboard.php');
        exit();
    }
}

$allCats = $db->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
$selectedCats = $db->prepare("SELECT category_id FROM product_categories WHERE product_id = ?");
$selectedCats->execute([$id]);
$selectedCats = $selectedCats->fetchAll(PDO::FETCH_COLUMN);

$pageTitle = 'Edit Product';
require_once '../includes/header.php';
?>

<section class="auth-form">
    <h2>Edit Product</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Title</label>
        <input type="text" name="title" required value="<?= htmlspecialchars($product['title']) ?>">
        <label>Description</label>
        <textarea name="description" rows="4" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;"><?= htmlspecialchars($product['description']) ?></textarea>
        <label>Price ($)</label>
        <input type="number" name="price" step="0.01" min="0.01" required value="<?= $product['price'] ?>">
        <label>Categories</label>
        <?php foreach ($allCats as $cat): ?>
            <label style="font-weight:normal;display:inline-block;margin-right:15px;">
                <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" <?= in_array($cat['id'], $selectedCats)?'checked':'' ?>> <?= htmlspecialchars($cat['name']) ?>
            </label>
        <?php endforeach; ?>
        <label>New Image (optional)</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
        <button type="submit">Save Changes</button>
    </form>
</section>

<?php require_once '../includes/footer.php'; ?>
