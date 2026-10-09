<?php
require 'config/db.php';
require 'includes/functions.php';

$base_url = '';
$q = trim($_GET['q'] ?? '');
$page_title = 'Kết quả tìm kiếm';

$products = [];
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT p.*, c.name AS cat_name FROM products p
                            JOIN categories c ON c.id = p.category_id
                            WHERE p.name LIKE ? ORDER BY p.created_at DESC");
    $stmt->execute(['%' . $q . '%']);
    $products = $stmt->fetchAll();
}

require 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-head">
            <div>
                <span class="eyebrow">Tìm kiếm</span>
                <h2>Kết quả cho "<?php echo e($q); ?>"</h2>
            </div>
        </div>

        <div class="product-grid">
            <?php foreach ($products as $p): ?>
                <?php include 'includes/product-card.php'; ?>
            <?php endforeach; ?>
            <?php if (empty($products)): ?>
                <p>Không tìm thấy sản phẩm phù hợp.</p>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php require 'includes/footer.php'; ?>
