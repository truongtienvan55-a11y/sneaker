<?php
/**
 * Component hiển thị 1 thẻ sản phẩm.
 * Biến cần có sẵn: $p (mảng dữ liệu sản phẩm), $base_url
 */
$base_url = $base_url ?? '';
?>
<a href="<?php echo $base_url; ?>product.php?slug=<?php echo e($p['slug']); ?>" class="product-card">
    <div class="product-thumb">
        <?php if (!empty($p['sale_price'])): ?>
            <div class="tag-sale">SALE</div>
        <?php endif; ?>
        <?php if ($p['stock'] <= 0): ?>
            <div class="tag-out">Hết hàng</div>
        <?php endif; ?>
        <img src="<?php echo e($p['image']); ?>" alt="<?php echo e($p['name']); ?>" loading="lazy">
    </div>
    <div class="product-info">
        <div class="product-cat"><?php echo e($p['cat_name'] ?? ''); ?></div>
        <div class="product-name"><?php echo e($p['name']); ?></div>
        <div class="product-price">
            <?php if (!empty($p['sale_price'])): ?>
                <span class="price-now"><?php echo formatPrice($p['sale_price']); ?></span>
                <span class="price-old"><?php echo formatPrice($p['price']); ?></span>
            <?php else: ?>
                <span class="price-now"><?php echo formatPrice($p['price']); ?></span>
            <?php endif; ?>
        </div>
    </div>
</a>
