<?php
require 'config/db.php';
require 'includes/functions.php';

$base_url = '';
$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->execute([$slug]);
$category = $stmt->fetch();

if (!$category) {
    header('Location: index.php');
    exit;
}

$page_title = $category['name'];

$stmt = $pdo->prepare("SELECT p.*, c.name AS cat_name FROM products p
                        JOIN categories c ON c.id = p.category_id
                        WHERE p.category_id = ? ORDER BY p.created_at DESC");
$stmt->execute([$category['id']]);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

require 'includes/header.php';
?>

<div class="container">
    <div class="breadcrumb"><a href="index.php">Trang chủ</a> / <?php echo e($category['name']); ?></div>

    <section class="section" style="padding-top:0;">
        <div class="section-head">
            <div>
                <span class="eyebrow">Danh mục</span>
                <h2><?php echo e($category['name']); ?></h2>
            </div>
        </div>

        <div class="cat-chips">
            <a href="index.php" class="cat-chip">Tất cả</a>
            <?php foreach ($categories as $c): ?>
                <a href="category.php?slug=<?php echo e($c['slug']); ?>" class="cat-chip <?php echo $c['id']==$category['id']?'active':''; ?>"><?php echo e($c['name']); ?></a>
            <?php endforeach; ?>
        </div>

        <div class="product-grid">
            <?php foreach ($products as $p): ?>
                <?php include 'includes/product-card.php'; ?>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
                <p>Danh mục này chưa có sản phẩm.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php require 'includes/footer.php'; ?>
