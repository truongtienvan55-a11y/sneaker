<?php
require 'config/db.php';
require 'includes/functions.php';

$page_title = 'Trang chủ';
$base_url = '';

// Lấy danh mục để hiện bộ lọc nhanh
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

// Lấy sản phẩm mới nhất
$products = $pdo->query("SELECT p.*, c.name AS cat_name FROM products p
                          JOIN categories c ON c.id = p.category_id
                          ORDER BY p.created_at DESC")->fetchAll();

require 'includes/header.php';
?>

<section class="hero">
    <div class="hero-shape"></div>
    <div class="hero-inner">
        <span class="hero-eyebrow">Bộ sưu tập mới về</span>
        <h1>Bước đi<br>đúng chất.</h1>
        <p>Giày thể thao chính hãng, cập nhật xu hướng mới nhất. Miễn phí đổi trả trong 7 ngày, giao hàng toàn quốc.</p>
        <a href="#products" class="btn btn-primary">Khám phá ngay</a>
    </div>
</section>

<div class="container">
    <section class="section" id="products">
        <div class="section-head">
            <div>
                <span class="eyebrow">Cửa hàng</span>
                <h2>Tất cả sản phẩm</h2>
            </div>
        </div>

        <div class="cat-chips">
            <a href="index.php" class="cat-chip active">Tất cả</a>
            <?php foreach ($categories as $c): ?>
                <a href="category.php?slug=<?php echo e($c['slug']); ?>" class="cat-chip"><?php echo e($c['name']); ?></a>
            <?php endforeach; ?>
        </div>

        <div class="product-grid">
            <?php foreach ($products as $p): ?>
                <?php include 'includes/product-card.php'; ?>
            <?php endforeach; ?>

            <?php if (empty($products)): ?>
                <p>Chưa có sản phẩm nào.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php require 'includes/footer.php'; ?>
