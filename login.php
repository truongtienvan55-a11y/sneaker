x<?php
require 'config/db.php';
require 'includes/functions.php';

$base_url = '';
$page_title = 'Đăng nhập';
$errors = [];

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: admin/index.php');
        } else {
            header('Location: index.php');
        }
        exit;
    } else {
        $errors[] = 'Email hoặc mật khẩu không đúng.';
    }
}

require 'includes/header.php';
?>

<div class="auth-wrap">
    <div class="auth-card">
        <h1>Đăng nhập</h1>
        <p class="sub">Chào mừng quay lại SneakerHub</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error"><?php echo implode('<br>', array_map('e', $errors)); ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required value="<?php echo e($_POST['email'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
        </form>

        <div class="auth-switch">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></div>
    </div>
</div>

<?php require 'includes/footer.php'; ?>
