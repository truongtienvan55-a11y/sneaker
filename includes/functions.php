<?php
/**
 * Các hàm tiện ích dùng chung trong toàn bộ website
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Định dạng tiền VNĐ
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . 'đ';
}

// Kiểm tra người dùng đã đăng nhập chưa
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Kiểm tra người dùng có phải admin không
function isAdmin() {
    return isLoggedIn() && ($_SESSION['role'] ?? '') === 'admin';
}

// Bắt buộc đăng nhập, nếu chưa thì chuyển hướng
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

// Bắt buộc quyền admin
function requireAdmin() {
    if (!isAdmin()) {
        header('Location: ../login.php');
        exit;
    }
}

// Chuyển chuỗi thành slug (không dấu, gạch ngang)
function toSlug($str) {
    $str = mb_strtolower($str, 'UTF-8');
    $vietnamese = [
        'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
        'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
        'ì','í','ị','ỉ','ĩ',
        'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
        'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
        'ỳ','ý','ỵ','ỷ','ỹ','đ'
    ];
    $latin = [
        'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
        'e','e','e','e','e','e','e','e','e','e','e',
        'i','i','i','i','i',
        'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
        'u','u','u','u','u','u','u','u','u','u','u',
        'y','y','y','y','y','d'
    ];
    $str = str_replace($vietnamese, $latin, $str);
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    $str = preg_replace('/[\s-]+/', '-', trim($str));
    return $str;
}

// Lấy giỏ hàng từ session (dạng: [product_id => quantity])
function getCart() {
    return $_SESSION['cart'] ?? [];
}

// Tổng số lượng sản phẩm trong giỏ
function getCartCount() {
    $cart = getCart();
    return array_sum($cart);
}

// Escape output an toàn chống XSS
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
