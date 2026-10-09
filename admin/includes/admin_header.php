<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($page_title) ? e($page_title) . ' — Quản trị' : 'Quản trị'; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-layout">
    <aside class="admin-sidebar">
        <div class="admin-logo">Sneaker<span>Hub</span> Admin</div>
        <nav class="admin-nav">
            <a href="index.php" class="<?php echo $current=='index.php'?'active':''; ?>">📊 Tổng quan</a>
            <a href="products.php" class="<?php echo in_array($current,['products.php','product-form.php'])?'active':''; ?>">👟 Sản phẩm</a>
            <a href="categories.php" class="<?php echo in_array($current,['categories.php','category-form.php'])?'active':''; ?>">🏷️ Danh mục</a>
            <a href="orders.php" class="<?php echo in_array($current,['orders.php','order-detail.php'])?'active':''; ?>">🧾 Đơn hàng</a>
            <div class="back-site">
                <a href="../index.php">← Về trang web</a>
                <a href="logout.php">Đăng xuất</a>
            </div>
        </nav>
    </aside>
    <main class="admin-main">
