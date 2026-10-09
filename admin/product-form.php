<?php
require '../config/db.php';
require '../includes/functions.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$product = ['name'=>'','description'=>'','price'=>'','sale_price'=>'','image'=>'','stock'=>0,'category_id'=>''];
$errors = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $found = $stmt->fetch();
    if ($found) $product = $found;
}

$page_title = $id ? 'Sửa sản phẩm' : 'Thêm sản phẩm';
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = (float) ($_POST['price'] ?? 0);
    $sale_price = $_POST['sale_price'] !== '' ? (float) $_POST['sale_price'] : null;
    $image = trim($_POST['image'] ?? '');
    $stock = (int) ($_POST['stock'] ?? 0);

    if ($name === '') $errors[] = 'Vui lòng nhập tên sản phẩm.';
    if ($category_id <= 0) $errors[] = 'Vui lòng chọn danh mục.';
    if ($price <= 0) $errors[] = 'Giá sản phẩm phải lớn hơn 0.';

    if (empty($errors)) {
        $slug = toSlug($name) . '-' . substr(md5(microtime()), 0, 5);

        if ($id) {
            $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, description=?, price=?, sale_price=?, image=?, stock=? WHERE id=?");
            $stmt->execute([$name, $category_id, $description, $price, $sale_price, $image, $stock, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (name, slug, category_id, description, price, sale_price, image, stock)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $slug, $category_id, $description, $price, $sale_price, $image, $stock]);
        }

        header('Location: products.php?saved=1');
        exit;
    }

    $product = array_merge($product, compact('name','category_id','description','price','sale_price','image','stock'));
}

require 'includes/admin_header.php';
?>

<div class="admin-topbar"><h1><?php echo $id ? 'Sửa sản phẩm' : 'Thêm sản phẩm mới'; ?></h1></div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error"><?php echo implode('<br>', array_map('e', $errors)); ?></div>
<?php endif; ?>

<div class="admin-card">
    <form method="post">
        <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" name="name" required value="<?php echo e($product['name']); ?>">
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Danh mục</label>
                <select name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $c['id']==$product['category_id']?'selected':''; ?>><?php echo e($c['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Tồn kho</label>
                <input type="number" name="stock" min="0" value="<?php echo (int) $product['stock']; ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Giá gốc (đ)</label>
                <input type="number" name="price" min="0" required value="<?php echo e($product['price']); ?>">
            </div>
            <div class="form-group">
                <label>Giá khuyến mãi (để trống nếu không giảm giá)</label>
                <input type="number" name="sale_price" min="0" value="<?php echo e($product['sale_price']); ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Link hình ảnh</label>
            <input type="text" name="image" placeholder="https://..." value="<?php echo e($product['image']); ?>">
        </div>

        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" rows="4"><?php echo e($product['description']); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
        <a href="products.php" class="btn btn-outline">Hủy</a>
    </form>
</div>

<?php require 'includes/admin_footer.php'; ?>
