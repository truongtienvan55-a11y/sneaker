<?php
require 'config/db.php';
require 'includes/functions.php';
requireLogin(); // Bắt buộc đăng nhập mới được đặt hàng

$base_url = '';
$page_title = 'Thanh toán';

$cart = getCart();
if (empty($cart)) {
    header('Location: cart.php');
    exit;
}

// Lấy chi tiết sản phẩm trong giỏ
$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($ids);
$productsById = [];
foreach ($stmt->fetchAll() as $row) $productsById[$row['id']] = $row;

$items = [];
$total = 0;
foreach ($cart as $pid => $qty) {
    if (!isset($productsById[$pid])) continue;
    $prod = $productsById[$pid];
    $price = $prod['sale_price'] ?: $prod['price'];
    $subtotal = $price * $qty;
    $total += $subtotal;
    $items[] = ['product' => $prod, 'qty' => $qty, 'price' => $price, 'subtotal' => $subtotal];
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $payment_method = $_POST['payment_method'] ?? 'cod';

    if ($full_name === '') $errors[] = 'Vui lòng nhập họ tên.';
    if (!preg_match('/^[0-9]{9,11}$/', $phone)) $errors[] = 'Số điện thoại không hợp lệ.';
    if ($address === '') $errors[] = 'Vui lòng nhập địa chỉ giao hàng.';

    if (empty($errors)) {
        // Dùng transaction để đảm bảo toàn vẹn dữ liệu (đơn hàng + chi tiết đơn hàng)
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO orders (user_id, full_name, phone, address, note, payment_method, total, status)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$_SESSION['user_id'], $full_name, $phone, $address, $note, $payment_method, $total]);
            $orderId = $pdo->lastInsertId();

            $itemStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                                        VALUES (?, ?, ?, ?, ?)");
            $stockStmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");

            foreach ($items as $item) {
                $itemStmt->execute([$orderId, $item['product']['id'], $item['product']['name'], $item['price'], $item['qty']]);
                $stockStmt->execute([$item['qty'], $item['product']['id']]);
            }

            $pdo->commit();

            unset($_SESSION['cart']); // Xóa giỏ hàng sau khi đặt thành công
            header('Location: order-success.php?id=' . $orderId);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Có lỗi xảy ra, vui lòng thử lại.';
        }
    }
}

require 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-head">
            <div><span class="eyebrow">Bước cuối</span><h2>Thông tin giao hàng</h2></div>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?php echo implode('<br>', array_map('e', $errors)); ?></div>
        <?php endif; ?>

        <div class="checkout-grid">
            <div class="form-card">
                <form method="post">
                    <div class="form-group">
                        <label>Họ và tên</label>
                        <input type="text" name="full_name" value="<?php echo e($_POST['full_name'] ?? $_SESSION['full_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Số điện thoại</label>
                        <input type="text" name="phone" value="<?php echo e($_POST['phone'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Địa chỉ giao hàng</label>
                        <textarea name="address" rows="3" required><?php echo e($_POST['address'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Ghi chú (tùy chọn)</label>
                        <textarea name="note" rows="2"><?php echo e($_POST['note'] ?? ''); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Phương thức thanh toán</label>
                        <div class="radio-row">
                            <label><input type="radio" name="payment_method" value="cod" checked> Thanh toán khi nhận hàng (COD)</label>
                            <label><input type="radio" name="payment_method" value="bank_transfer"> Chuyển khoản ngân hàng</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Đặt hàng</button>
                </form>
            </div>

            <div class="cart-summary" style="max-width:none; margin-left:0;">
                <?php foreach ($items as $item): ?>
                    <div class="row">
                        <span><?php echo e($item['product']['name']); ?> × <?php echo $item['qty']; ?></span>
                        <span><?php echo formatPrice($item['subtotal']); ?></span>
                    </div>
                <?php endforeach; ?>
                <div class="row total"><span>Tổng cộng</span><span><?php echo formatPrice($total); ?></span></div>
            </div>
        </div>
    </section>
</div>

<?php require 'includes/footer.php'; ?>
