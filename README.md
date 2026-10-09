# SneakerHub — Đồ án Web bán hàng (PHP + MySQL)

Website bán giày thể thao: xem sản phẩm, giỏ hàng, đặt hàng, đăng ký/đăng nhập,
và trang quản trị (CRUD sản phẩm/danh mục, quản lý đơn hàng).

## Công nghệ dùng
- Frontend: HTML, CSS thuần, JavaScript thuần (không framework)
- Backend: PHP thuần (PDO, không framework)
- Database: MySQL

## Cấu trúc thư mục
```
shop/
├── admin/            -> Trang quản trị (thêm/sửa/xóa sản phẩm, danh mục, đơn hàng)
├── assets/css, js     -> Giao diện và tương tác
├── config/db.php      -> Cấu hình kết nối CSDL (SỬA Ở ĐÂY khi deploy)
├── database/shop.sql  -> File SQL để import CSDL
├── includes/          -> Header, footer, hàm dùng chung
└── *.php              -> Các trang cho khách hàng (trang chủ, giỏ hàng, đặt hàng...)
```

## 1. Cài đặt chạy trên máy (XAMPP/Laragon)
1. Copy thư mục `shop` vào `htdocs` (XAMPP) hoặc `www` (Laragon).
2. Mở phpMyAdmin → tạo CSDL (hoặc để file SQL tự tạo) → Import file `database/shop.sql`.
3. Mở `config/db.php`, kiểm tra lại DB_HOST/DB_USER/DB_PASS cho khớp XAMPP của bạn
   (mặc định XAMPP: user `root`, password rỗng `''`).
4. Truy cập `http://localhost/shop/index.php`.

## 2. Tài khoản có sẵn
- Trang quản trị: `http://localhost/shop/admin/login.php`
  - Email: `admin@shop.com`
  - Mật khẩu: `admin123`
- Khách hàng: tự đăng ký tại `register.php`

## 3. Upload lên Hosting (để được cộng điểm)
1. Vào hosting (000webhost/InfinityFree/Hostinger... loại hỗ trợ PHP + MySQL),
   tạo 1 Database MySQL mới (host sẽ cho bạn: tên DB, user, password).
2. Vào phpMyAdmin của hosting → Import file `database/shop.sql`
   (nếu host không cho tạo DB bằng câu lệnh `CREATE DATABASE`, hãy xóa dòng
   `CREATE DATABASE...` và `USE shop_db;` ở đầu file, rồi import thẳng vào DB host đã tạo sẵn).
3. Sửa file `config/db.php`:
   ```php
   define('DB_HOST', 'địa chỉ host DB bạn được cấp'); // vd: localhost hoặc sql123.hostname.com
   define('DB_NAME', 'tên database');
   define('DB_USER', 'username database');
   define('DB_PASS', 'password database');
   ```
4. Upload toàn bộ nội dung thư mục `shop/` lên thư mục `public_html` (hoặc `htdocs`) qua File Manager / FTP.
5. Truy cập domain host cấp cho bạn, ví dụ: `http://ten-mien-cua-ban.infinityfreeapp.com/index.php`

## 4. Ghi chú
- Hình ảnh sản phẩm dùng link `https://picsum.photos/...` (ảnh demo ngẫu nhiên) —
  bạn có thể thay bằng link ảnh thật của mình trong trang quản trị (Thêm/Sửa sản phẩm).
- Giỏ hàng lưu theo session (không cần đăng nhập để thêm vào giỏ), nhưng phải
  đăng nhập mới đặt hàng được (để lưu đơn hàng gắn với tài khoản).
- Mật khẩu được mã hóa bằng `password_hash()` (bcrypt), không lưu plain text.
- Code có transaction (`beginTransaction`/`commit`/`rollBack`) khi tạo đơn hàng
  để đảm bảo toàn vẹn dữ liệu giữa bảng `orders` và `order_items`.
