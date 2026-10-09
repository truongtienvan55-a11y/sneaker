<?php
require '../config/db.php';
require '../includes/functions.php';
requireAdmin();

$page_title = 'Danh mục';

if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([(int) $_GET['delete']]);
    header('Location: categories.php?deleted=1');
    exit;
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS product_count
                            FROM categories c ORDER BY c.name")->fetchAll();

require 'includes/admin_header.php';
?>

<div class="admin-topbar">
    <h1>Danh mục</h1>
    <a href="category-form.php" class="btn btn-primary">+ Thêm danh mục</a>
</div>

<?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">Đã xóa danh mục.</div><?php endif; ?>
<?php if (isset($_GET['saved'])): ?><div class="alert alert-success">Đã lưu danh mục.</div><?php endif; ?>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Tên danh mục</th><th>Slug</th><th>Số sản phẩm</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($categories as $c): ?>
            <tr>
                <td><?php echo e($c['name']); ?></td>
                <td><?php echo e($c['slug']); ?></td>
                <td><?php echo (int) $c['product_count']; ?></td>
                <td>
                    <a href="category-form.php?id=<?php echo $c['id']; ?>" class="btn btn-outline btn-sm">Sửa</a>
                    <a href="categories.php?delete=<?php echo $c['id']; ?>" class="btn btn-danger btn-sm confirm-delete">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/admin_footer.php'; ?>
