<?php
require '../config/db.php';
require '../includes/functions.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit;
}

$page_title = 'Đơn hàng #' . $id;
$statuses = ['pending'=>'Chờ xử lý','confirmed'=>'Đã xác nhận','shipping'=>'Đang giao','completed'=>'Hoàn thành','cancelled'=>'Đã hủy'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newStatus = $_POST['status'] ?? 'pending';
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);
    header('Location: order-detail.php?id=' . $id . '&updated=1');
    exit;
}

$itemsStmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
$itemsStmt->execute([$id]);
$items = $itemsStmt->fetchAll();

require 'includes/admin_header.php';
?>

<div class="admin-topbar"><h1>Đơn hàng #<?php echo $order['id']; ?></h1></div>

<?php if (isset($_GET['updated'])): ?><div class="alert alert-success">Đã cập nhật trạng thái đơn hàng.</div><?php endif; ?>

<div class="form-row">
    <div class="admin-card">
        <h3 style="margin-top:0;">Thông tin giao hàng</h3>
        <p><strong>Khách hàng:</strong> <?php echo e($order['full_name']); ?></p>
        <p><strong>Điện thoại:</strong> <?php echo e($order['phone']); ?></p>
        <p><strong>Địa chỉ:</strong> <?php echo e($order['address']); ?></p>
        <p><strong>Ghi chú:</strong> <?php echo e($order['note']) ?: '—'; ?></p>
        <p><strong>Thanh toán:</strong> <?php echo $order['payment_method'] === 'cod' ? 'COD' : 'Chuyển khoản'; ?></p>
        <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
    </div>

    <div class="admin-card">
        <h3 style="margin-top:0;">Cập nhật trạng thái</h3>
        <form method="post">
            <div class="form-group">
                <select name="status">
                    <?php foreach ($statuses as $key => $label): ?>
                        <option value="<?php echo $key; ?>" <?php echo $order['status']===$key?'selected':''; ?>><?php echo $label; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
</div>

<div class="admin-card">
    <h3 style="margin-top:0;">Sản phẩm đã đặt</h3>
    <table class="admin-table">
        <thead><tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Thành tiền</th></tr></thead>
        <tbody>
        <?php foreach ($items as $it): ?>
            <tr>
                <td><?php echo e($it['product_name']); ?></td>
                <td><?php echo formatPrice($it['price']); ?></td>
                <td><?php echo (int) $it['quantity']; ?></td>
                <td><?php echo formatPrice($it['price'] * $it['quantity']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr><td colspan="3" style="text-align:right; font-weight:700;">Tổng cộng</td><td style="font-weight:700;"><?php echo formatPrice($order['total']); ?></td></tr>
        </tfoot>
    </table>
</div>

<?php require 'includes/admin_footer.php'; ?>
