<?php
require 'config/db.php';
require 'includes/functions.php';

$base_url = '';
$page_title = 'Đăng ký';
$errors = [];

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($full_name === '') $errors[] = 'Vui lòng nhập họ tên.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
    if (strlen($password) < 6) $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
    if ($password !== $confirm) $errors[] = 'Mật khẩu xác nhận không khớp.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Email này đã được đăng ký.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'customer')");
            $stmt->execute([$full_name, $email, $hashed]);

            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = 'customer';

            header('Location: index.php');
            exit;
        }
    }
}

require 'includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1>Tạo tài khoản</h1>
        <p class="sub">Đăng ký để mua sắm và theo dõi đơn hàng</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?php echo implode('<br>', array_map('e', $errors)); ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Họ và tên</label>
                <input type="text" name="full_name" required value="<?php echo e($_POST['full_name'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?php echo e($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Xác nhận mật khẩu</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
        </form>

        <div class="auth-switch">Đã có tài khoản? <a href="login.php">Đăng nhập</a></div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
