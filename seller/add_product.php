<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';
requireRole(['seller', 'admin']);

$db = (new Database())->getConnection();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $categories = $_POST['categories'] ?? [];
    $image = null;

    if (empty($title) || $price <= 0) {
        $error = 'Title and valid price are required.';
    } else {
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                $error = 'Only JPG, PNG, WEBP files allowed.';
            } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                $error = 'File must be under 2MB.';
            } else {
                $image = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $image);
            }
        }

        if (!$error) {
            $stmt = $db->prepare("INSERT INTO products (seller_id, title, description, price, image) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $title, $description, $price, $image]);
            $productId = $db->lastInsertId();

            $catStmt = $db->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
            foreach ($categories as $catId) {
                $catStmt->execute([$productId, (int)$catId]);
            }
            $success = 'Product added!';
        }
    }
}

$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$pageTitle = 'Add Product';
require_once '../includes/header.php';
?>

<section class="auth-form">
    <h2>Add Product</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required placeholder="Product name">

        <label for="description">Description</label>
        <textarea id="description" name="description" rows="4" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;" placeholder="Describe your product"></textarea>

        <label for="price">Price ($)</label>
        <input type="number" id="price" name="price" step="0.01" min="0.01" required>

        <label>Categories</label>
        <?php foreach ($categories as $cat): ?>
            <label style="font-weight:normal;display:inline-block;margin-right:15px;">
                <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>"> <?= htmlspecialchars($cat['name']) ?>
            </label>
        <?php endforeach; ?>

        <label for="image">Image (JPG, PNG, WEBP, max 2MB)</label>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">

        <button type="submit">Add Product</button>
    </form>
</section>

<?php require_once '../includes/footer.php'; ?>
