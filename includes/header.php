<?php
// $base_url dùng để trang trong thư mục con (nếu có) trỏ về gốc đúng đường dẫn assets
$base_url = $base_url ?? '';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? e($page_title) . ' — SneakerHub' : 'SneakerHub — Giày thể thao chính hãng'; ?></title>
<link rel="stylesheet" href="<?php echo $base_url; ?>assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo $base_url; ?>index.php" class="logo">Sneaker<span>Hub</span></a>

        <nav class="main-nav">
            <ul>
                <li><a href="<?php echo $base_url; ?>index.php">Trang chủ</a></li>
                <?php
                // Danh mục lấy trực tiếp từ CSDL để hiện trên menu
                $navCats = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
                foreach ($navCats as $c):
                ?>
                <li><a href="<?php echo $base_url; ?>category.php?slug=<?php echo e($c['slug']); ?>"><?php echo e($c['name']); ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="header-actions">
            <form class="search-form" action="<?php echo $base_url; ?>search.php" method="get">
                <input type="text" name="q" placeholder="Tìm sản phẩm..." value="<?php echo e($_GET['q'] ?? ''); ?>">
                <button type="submit">Tìm</button>
            </form>

            <a href="<?php echo $base_url; ?>cart.php" class="cart-link">
                🛒 Giỏ hàng
                <?php if (getCartCount() > 0): ?>
                    <span class="cart-badge"><?php echo getCartCount(); ?></span>
                <?php endif; ?>
            </a>

            <div class="user-menu">
                <?php if (isLoggedIn()): ?>
                    <span>Chào, <?php echo e($_SESSION['full_name']); ?></span>
                    <?php if (isAdmin()): ?><a href="<?php echo $base_url; ?>admin/index.php">Quản trị</a><?php endif; ?>
                    <a href="<?php echo $base_url; ?>logout.php" class="btn-mini">Đăng xuất</a>
                <?php else: ?>
                    <a href="<?php echo $base_url; ?>login.php" class="btn-mini">Đăng nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
