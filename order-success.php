<?php
require 'config/db.php';
require 'includes/functions.php';
requireLogin();

$base_url = '';
$page_title = 'Đặt hàng thành công';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: index.php');
    exit;
}

require 'includes/header.php';
?>

<div class="container">
    <div class="success-wrap">
        <div class="success-icon">✓</div>
        <h1>Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã mua hàng. Mã đơn hàng của bạn là <strong>#<?php echo $order['id']; ?></strong>.
        Tổng tiền: <strong><?php echo formatPrice($order['total']); ?></strong>. Chúng tôi sẽ liên hệ để xác nhận giao hàng sớm nhất.</p>
        <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
