<?php
require '../config/db.php';
require '../includes/functions.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$category = ['name' => ''];
$errors = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) $category = $found;
}

$page_title = $id ? 'Sửa danh mục' : 'Thêm danh mục';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') $errors[] = 'Vui lòng nhập tên danh mục.';

    if (empty($errors)) {
        $slug = toSlug($name);
        if ($id) {
            $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=? WHERE id=?");
            $stmt->execute([$name, $slug, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
            $stmt->execute([$name, $slug]);
        }
        header('Location: categories.php?saved=1');
        exit;
    }
    $category['name'] = $name;
}

require 'includes/admin_header.php';
?>

<div class="admin-topbar"><h1><?php echo $id ? 'Sửa danh mục' : 'Thêm danh mục mới'; ?></h1></div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?php echo implode('<br>', array_map('e', $errors)); ?></div>
<?php endif; ?>

<div class="admin-card" style="max-width:480px;">
    <form method="post">
        <div class="form-group">
            <label>Tên danh mục</label>
            <input type="text" name="name" required value="<?php echo e($category['name']); ?>">
        </div>
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="categories.php" class="btn btn-outline">Hủy</a>
    </form>
</div>

<?php require 'includes/admin_footer.php'; ?>
