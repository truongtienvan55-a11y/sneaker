-- =========================================================
-- CSDL cho website bán hàng (SneakerHub)
-- Import file này vào MySQL (phpMyAdmin -> Import, hoặc lệnh mysql)
-- =========================================================

CREATE DATABASE IF NOT EXISTS shop_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE shop_db;

-- ---------------------------------------------------------
-- Bảng người dùng
-- ---------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tài khoản admin mặc định: email admin@shop.com / mật khẩu: admin123
INSERT INTO users (full_name, email, password, role) VALUES
('Quản trị viên', 'admin@shop.com', '$2y$10$SIkKGp6faEgs0fXR2eQLy.wruC4o2Xg1zDEw8098uaI4w6wiFMZm2', 'admin');

-- ---------------------------------------------------------
-- Bảng danh mục sản phẩm
-- ---------------------------------------------------------
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

INSERT INTO categories (name, slug) VALUES
('Giày chạy bộ', 'giay-chay-bo'),
('Giày thể thao', 'giay-the-thao'),
('Giày sneaker cổ điển', 'sneaker-co-dien'),
('Dép & Sandal', 'dep-sandal'),
('Phụ kiện', 'phu-kien');

-- ---------------------------------------------------------
-- Bảng sản phẩm
-- ---------------------------------------------------------
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    description TEXT,
    price DECIMAL(12,0) NOT NULL,
    sale_price DECIMAL(12,0) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    stock INT NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO products (category_id, name, slug, description, price, sale_price, image, stock) VALUES
(1, 'Runner Pro X1', 'runner-pro-x1', 'Giày chạy bộ nhẹ, đế êm, thoáng khí, phù hợp chạy đường dài.', 1890000, 1590000, 'https://picsum.photos/seed/shoe1/600/600', 25),
(1, 'AirFlow Marathon', 'airflow-marathon', 'Công nghệ đệm khí giúp giảm chấn khi chạy tốc độ cao.', 2190000, NULL, 'https://picsum.photos/seed/shoe2/600/600', 18),
(2, 'Street Force 90', 'street-force-90', 'Giày thể thao phong cách đường phố, form rộng rãi.', 1450000, NULL, 'https://picsum.photos/seed/shoe3/600/600', 30),
(2, 'CourtKing Basketball', 'courtking-basketball', 'Giày bóng rổ cổ cao, hỗ trợ cổ chân chắc chắn.', 2450000, 2190000, 'https://picsum.photos/seed/shoe4/600/600', 12),
(3, 'Retro 88 Classic', 'retro-88-classic', 'Thiết kế cổ điển thập niên 80, chất liệu da lộn cao cấp.', 1990000, NULL, 'https://picsum.photos/seed/shoe5/600/600', 20),
(3, 'Heritage Canvas', 'heritage-canvas', 'Giày vải canvas phong cách vintage, dễ phối đồ.', 990000, 790000, 'https://picsum.photos/seed/shoe6/600/600', 40),
(4, 'Coastal Slide', 'coastal-slide', 'Dép quai ngang êm ái, dùng đi biển hoặc ở nhà.', 350000, NULL, 'https://picsum.photos/seed/shoe7/600/600', 50),
(4, 'Trail Sandal Pro', 'trail-sandal-pro', 'Sandal đi trekking, quai dán chắc chắn, đế bám tốt.', 590000, NULL, 'https://picsum.photos/seed/shoe8/600/600', 22),
(5, 'Vớ thể thao cao cấp (bộ 3)', 'vo-the-thao-cao-cap', 'Bộ 3 đôi vớ cotton thoáng khí, êm chân.', 150000, NULL, 'https://picsum.photos/seed/shoe9/600/600', 100),
(5, 'Dây giày dù phản quang', 'day-giay-du-phan-quang', 'Dây giày phản quang, bền, nhiều màu sắc.', 79000, NULL, 'https://picsum.photos/seed/shoe10/600/600', 80);

-- ---------------------------------------------------------
-- Bảng đơn hàng
-- ---------------------------------------------------------
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(255) NOT NULL,
    note VARCHAR(255) DEFAULT NULL,
    payment_method ENUM('cod','bank_transfer') NOT NULL DEFAULT 'cod',
    total DECIMAL(12,0) NOT NULL,
    status ENUM('pending','confirmed','shipping','completed','cancelled') NOT NULL DEFAULT 'pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------
-- Bảng chi tiết đơn hàng
-- ---------------------------------------------------------
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT DEFAULT NULL,
    product_name VARCHAR(200) NOT NULL,
    price DECIMAL(12,0) NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
) ENGINE=InnoDB;
