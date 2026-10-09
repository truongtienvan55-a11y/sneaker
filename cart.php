<?php
require 'config/db.php';
require 'includes/functions.php';

$base_url = '';
$page_title = 'Giỏ hàng';

// ---- Xử lý cập nhật số lượng ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    $cart = getCart();
    foreach ($_POST['qty'] as $productId => $qty) {
        $qty = (int) $qty;
        if ($qty <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $qty;
        }
    }
    $_SESSION['cart'] = $cart;
    header('Location: cart.php');
    exit;
}

// ---- Xóa 1 sản phẩm khỏi giỏ ----
if (isset($_GET['remove'])) {
    $cart = getCart();
    unset($cart[(int) $_GET['remove']]);
    $_SESSION['cart'] = $cart;
    header('Location: cart.php');
    exit;
}

$cart = getCart();
$items = [];
$total = 0;

if (!empty($cart)) {
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $productsById = [];
    foreach ($stmt->fetchAll() as $row) {
        $productsById[$row['id']] = $row;
    }

    foreach ($cart as $pid => $qty) {
        if (!isset($productsById[$pid])) continue;
        $prod = $productsById[$pid];
        $price = $prod['sale_price'] ?: $prod['price'];
        $subtotal = $price * $qty;
        $total += $subtotal;
        $items[] = ['product' => $prod, 'qty' => $qty, 'price' => $price, 'subtotal' => $subtotal];
    }
}

require 'includes/header.php';
?>

<div class="container">
    <section class="section">
        <div class="section-head">
            <div><span class="eyebrow">Mua sắm</span><h2>Giỏ hàng của bạn</h2></div>
        </div>

        <?php if (empty($items)): ?>
            <div class="empty-state">
                <h2>Giỏ hàng đang trống</h2>
                <p>Hãy chọn vài đôi giày ưng ý nhé!</p>
                <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
        <?php else: ?>
            <form method="post">
                <table class="cart-table">
                    <thead>
                        <tr><th>Sản phẩm</th><th>Đơn giá</th><th>Số lượng</th><th>Tạm tính</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td>
                                <div class="cart-product">
                                    <img src="<?php echo e($item['product']['image']); ?>" alt="">
                                    <span><?php echo e($item['product']['name']); ?></span>
                                </div>
                            </td>
                            <td class="price-now"><?php echo formatPrice($item['price']); ?></td>
                            <td>
                                <input type="number" class="cart-qty-input" name="qty[<?php echo $item['product']['id']; ?>]"
                                       value="<?php echo $item['qty']; ?>" min="0" max="<?php echo (int) $item['product']['stock']; ?>"
                                       style="width:60px; background:var(--surface-2); border:1px solid var(--border); color:var(--text); padding:6px; border-radius:4px;">
                            </td>
                            <td class="price-now"><?php echo formatPrice($item['subtotal']); ?></td>
                            <td><a href="cart.php?remove=<?php echo $item['product']['id']; ?>" class="remove-link confirm-delete">Xóa</a></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="update_cart" class="btn btn-outline">Cập nhật giỏ hàng</button>
            </form>

            <br>
            <div class="cart-summary">
                <div class="row"><span>Tạm tính</span><span><?php echo formatPrice($total); ?></span></div>
                <div class="row"><span>Phí vận chuyển</span><span>Miễn phí</span></div>
                <div class="row total"><span>Tổng cộng</span><span><?php echo formatPrice($total); ?></span></div>
                <a href="checkout.php" class="btn btn-primary btn-block">Tiến hành đặt hàng</a>
            </div>
        <?php endif; ?>
    </section>
</div>

<?php require 'includes/footer.php'; ?>
