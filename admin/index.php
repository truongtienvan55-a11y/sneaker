<?php
require '../config/db.php';
require '../includes/functions.php';
requireAdmin();

$page_title = 'Tổng quan';

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
$totalRevenue = $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status != 'cancelled'")->fetchColumn();

$recentOrders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 6")->fetchAll();

require 'includes/admin_header.php';
?>

<div class="admin-topbar"><h1>Tổng quan</h1></div>

<div class="stat-grid">
    <div class="stat-card"><div class="label">Sản phẩm</div><div class="value"><?php echo $totalProducts; ?></div></div>
    <div class="stat-card"><div class="label">Đơn hàng</div><div class="value"><?php echo $totalOrders; ?></div></div>
    <div class="stat-card"><div class="label">Khách hàng</div><div class="value"><?php echo $totalUsers; ?></div></div>
    <div class="stat-card"><div class="label">Doanh thu</div><div class="value" style="font-size:24px;"><?php echo formatPrice($totalRevenue); ?></div></div>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">Đơn hàng gần đây</h3>
    <table class="admin-table">
        <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày đặt</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($recentOrders as $o): ?>
            <tr>
                <td>#<?php echo $o['id']; ?></td>
                <td><?php echo e($o['full_name']); ?></td>
                <td><?php echo formatPrice($o['total']); ?></td>
                <td><span class="badge badge-<?php echo $o['status']; ?>"><?php echo e($o['status']); ?></span></td>
                <td><?php echo date('d/m/Y H:i', strtotime($o['created_at'])); ?></td>
                <td><a href="order-detail.php?id=<?php echo $o['id']; ?>" class="btn btn-outline btn-sm">Xem</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($recentOrders)): ?>
            <tr><td colspan="6">Chưa có đơn hàng nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/admin_footer.php'; ?>
