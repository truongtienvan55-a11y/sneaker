<?php
require '../config/db.php';
require '../includes/functions.php';

$errors = [];

if (isAdmin()) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['role'] = $user['role'];
        header('Location: index.php');
        exit;
    } else {
        $errors[] = 'Email hoặc mật khẩu không đúng, hoặc tài khoản không có quyền quản trị.';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Đăng nhập quản trị</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh;">
<div class="admin-card" style="width:380px;">
    <div class="admin-logo" style="margin-bottom:20px;">Sneaker<span>Hub</span> Admin</div>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error"><?php echo implode('<br>', array_map('e', $errors)); ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required value="admin@shop.com">
        </div>
        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;">Đăng nhập</button>
    </form>
</div>
</body>
</html>
