<?php
require 'config/db.php';
require 'includes/functions.php';

$base_url = '';
$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("SELECT p.*, c.name AS cat_name, c.slug AS cat_slug FROM products p
                        JOIN categories c ON c.id = p.category_id
                        WHERE p.slug = ?");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: index.php');
    exit;
}

$page_title = $product['name'];

// ---- Xử lý thêm vào giỏ hàng ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $qty = max(1, (int) $_POST['quantity']);
    $cart = getCart();
    $cart[$product['id']] = ($cart[$product['id']] ?? 0) + $qty;
    $_SESSION['cart'] = $cart;
    header('Location: product.php?slug=' . urlencode($slug) . '&added=1');
    exit;
}

require 'includes/header.php';
?>

<div class="container">
    <div class="breadcrumb">
        <a href="index.php">Trang chủ</a> /
        <a href="category.php?slug=<?php echo e($product['cat_slug']); ?>"><?php echo e($product['cat_name']); ?></a> /
        <?php echo e($product['name']); ?>
    </div>

    <?php if (isset($_GET['added'])): ?>
        <div class="alert alert-success">Đã thêm sản phẩm vào giỏ hàng! <a href="cart.php" style="text-decoration:underline;">Xem giỏ hàng</a></div>
    <?php endif; ?>

    <div class="product-detail">
        <div class="detail-image">
            <?php if (!empty($product['sale_price'])): ?><div class="tag-sale">SALE</div><?php endif; ?>
            <img src="<?php echo e($product['image']); ?>" alt="<?php echo e($product['name']); ?>">
        </div>

        <div class="detail-info">
            <div class="product-cat"><?php echo e($product['cat_name']); ?></div>
            <h1><?php echo e($product['name']); ?></h1>

            <div class="detail-price">
                <?php if (!empty($product['sale_price'])): ?>
                    <span class="price-now"><?php echo formatPrice($product['sale_price']); ?></span>
                    <span class="price-old"><?php echo formatPrice($product['price']); ?></span>
                <?php else: ?>
                    <span class="price-now"><?php echo formatPrice($product['price']); ?></span>
                <?php endif; ?>
            </div>

            <?php if ($product['stock'] > 0): ?>
                <div class="stock-info">✓ Còn hàng (<?php echo (int) $product['stock']; ?> sản phẩm)</div>
            <?php else: ?>
                <div class="stock-info out">✕ Tạm hết hàng</div>
            <?php endif; ?>

            <form method="post">
                <div class="qty-row">
                    <div class="qty-box">
                        <button type="button" class="qty-minus">−</button>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo (int) $product['stock']; ?>">
                        <button type="button" class="qty-plus">+</button>
                    </div>
                    <button type="submit" name="add_to_cart" class="btn btn-primary" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                        Thêm vào giỏ hàng
                    </button>
                </div>
            </form>

            <div class="detail-desc"><?php echo nl2br(e($product['description'])); ?></div>
        </div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
