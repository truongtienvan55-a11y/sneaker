<?php
require '../config/db.php';
require '../includes/functions.php';
requireAdmin();

$page_title = 'Đơn hàng';

$statusFilter = $_GET['status'] ?? '';
$sql = "SELECT * FROM orders";
$params = [];
if ($statusFilter) {
    $sql .= " WHERE status = ?";
    $params[] = $statusFilter;
}
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

$statuses = ['pending'=>'Chờ xử lý','confirmed'=>'Đã xác nhận','shipping'=>'Đang giao','completed'=>'Hoàn thành','cancelled'=>'Đã hủy'];

require 'includes/admin_header.php';
?>

<div class="admin-topbar"><h1>Đơn hàng</h1></div>

<div class="cat-chips" style="margin-bottom:20px; display:flex; gap:10px;">
    <a href="orders.php" class="btn <?php echo $statusFilter===''?'btn-primary':'btn-outline'; ?> btn-sm">Tất cả</a>
    <?php foreach ($statuses as $key => $label): ?>
        <a href="orders.php?status=<?php echo $key; ?>" class="btn <?php echo $statusFilter===$key?'btn-primary':'btn-outline'; ?> btn-sm"><?php echo $label; ?></a>
    <?php endforeach; ?>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>SĐT</th><th>Tổng tiền</th><th>Trạng thái</th><th>Ngày đặt</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td>#<?php echo $o['id']; ?></td>
                <td><?php echo e($o['full_name']); ?></td>
                <td><?php echo e($o['phone']); ?></td>
                <td><?php echo formatPrice($o['total']); ?></td>
                <td><span class="badge badge-<?php echo $o['status']; ?>"><?php echo $statuses[$o['status']]; ?></span></td>
                <td><?php echo date('d/m/Y H:i', strtotime($o['created_at'])); ?></td>
                <td><a href="order-detail.php?id=<?php echo $o['id']; ?>" class="btn btn-outline btn-sm">Chi tiết</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($orders)): ?>
            <tr><td colspan="7">Không có đơn hàng nào.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require 'includes/admin_footer.php'; ?>
