<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="logo" style="margin-bottom:12px;">Sneaker<span>Hub</span></div>
                <p>Đồ án web bán hàng — xây dựng với PHP thuần &amp; MySQL. Giao diện, giỏ hàng, đặt hàng và trang quản trị đầy đủ.</p>
            </div>
            <div>
                <h4>Danh mục</h4>
                <ul>
                    <?php $footCats = $pdo->query("SELECT * FROM categories ORDER BY name LIMIT 5")->fetchAll(); ?>
                    <?php foreach ($footCats as $c): ?>
                    <li><a href="<?php echo $base_url ?? ''; ?>category.php?slug=<?php echo e($c['slug']); ?>"><?php echo e($c['name']); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h4>Hỗ trợ</h4>
                <ul>
                    <li><a href="#">Chính sách đổi trả</a></li>
                    <li><a href="#">Hướng dẫn mua hàng</a></li>
                    <li><a href="#">Liên hệ</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">&copy; <?php echo date('Y'); ?> SneakerHub. Đồ án môn học — mọi hình ảnh chỉ mang tính minh họa.</div>
    </div>
</footer>
<script src="<?php echo $base_url ?? ''; ?>assets/js/main.js"></script>
</body>
</html>
