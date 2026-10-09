<?php
require '../config/db.php';
require '../includes/functions.php';
requireAdmin();

$page_title = 'Sản phẩm';

// Xóa sản phẩm
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([(int) $_GET['delete']]);
    header('Location: products.php?deleted=1');
    exit;
}

$products = $pdo->query("SELECT p.*, c.name AS cat_name FROM products p
                          JOIN categories c ON c.id = p.category_id
                          ORDER BY p.created_at DESC")->fetchAll();

require 'includes/admin_header.php';
?>

<div class="admin-topbar">
    <h1>Sản phẩm</h1>
    <a href="product-form.php" class="btn btn-primary">+ Thêm sản phẩm</a>
</div>

<?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">Đã xóa sản phẩm.</div><?php endif; ?>
<?php if (isset($_GET['saved'])): ?><div class="alert alert-success">Đã lưu sản phẩm.</div><?php endif; ?>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th></th><th>Tên sản phẩm</th><th>Danh mục</th><th>Giá</th><th>Tồn kho</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><img src="<?php echo e($p['image']); ?>" alt=""></td>
                <td><?php echo e($p['name']); ?></td>
                <td><?php echo e($p['cat_name']); ?></td>
                <td><?php echo formatPrice($p['sale_price'] ?: $p['price']); ?></td>
                <td><?php echo (int) $p['stock']; ?></td>
                <td>
                    <a href="product-form.php?id=<?php echo $p['id']; ?>" class="btn btn-outline btn-sm">Sửa</a>
                    <a href="products.php?delete=<?php echo $p['id']; ?>" class="btn btn-danger btn-sm confirm-delete">Xóa</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
            <tr><td colspan="6">Chưa có sản phẩm nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/admin_footer.php'; ?>
