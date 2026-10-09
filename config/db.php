<?php
/**
 * File cấu hình kết nối CSDL.
 * Khi đưa lên hosting, chỉ cần sửa 4 dòng thông tin bên dưới
 * cho khớp với thông tin database mà nhà cung cấp host cấp cho bạn.
 */

// ====== SỬA THÔNG TIN NÀY KHI DEPLOY LÊN HOST ======
define('DB_HOST', 'localhost');
define('DB_NAME', 'shop_db');
define('DB_USER', 'root');
define('DB_PASS', '');
// =====================================================

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Không thể kết nối cơ sở dữ liệu: ' . $e->getMessage());
}
