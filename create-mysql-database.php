<?php

/**
 * Script tạo database MySQL mới và import dữ liệu
 * Sử dụng: php create-mysql-database.php
 */

echo "🗄️ TẠO DATABASE MYSQL MỚI CHO BOOKSTORE\n";
echo "======================================\n\n";

// Đọc cấu hình từ .env
function getEnvValue($key, $default = '') {
    $envFile = '.env';
    if (!file_exists($envFile)) {
        return $default;
    }
    
    $content = file_get_contents($envFile);
    $lines = explode("\n", $content);
    
    foreach ($lines as $line) {
        if (strpos($line, $key . '=') === 0) {
            return trim(str_replace($key . '=', '', $line));
        }
    }
    
    return $default;
}

$dbHost = getEnvValue('DB_HOST', '127.0.0.1');
$dbPort = getEnvValue('DB_PORT', '3306');
$dbName = getEnvValue('DB_DATABASE', 'nha_sach_laravel');
$dbUser = getEnvValue('DB_USERNAME', 'root');
$dbPass = getEnvValue('DB_PASSWORD', '');

echo "📋 Cấu hình Database:\n";
echo "   Host: {$dbHost}:{$dbPort}\n";
echo "   Database: {$dbName}\n";
echo "   Username: {$dbUser}\n\n";

try {
    // 1. Kết nối MySQL server
    echo "1. Kết nối MySQL server...\n";
    
    $dsn = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "✅ Kết nối MySQL thành công!\n\n";

    // 2. Tạo database
    echo "2. Tạo database...\n";
    
    $pdo->exec("DROP DATABASE IF EXISTS `{$dbName}`");
    echo "   🗑️ Xóa database cũ (nếu có)\n";
    
    $pdo->exec("CREATE DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "   ✅ Tạo database '{$dbName}' thành công!\n\n";

    // 3. Sử dụng database mới
    $pdo->exec("USE `{$dbName}`");

    // 4. Tạo các bảng
    echo "3. Tạo cấu trúc bảng...\n";
    
    // Bảng users
    $pdo->exec("
        CREATE TABLE users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ho_ten VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP NULL,
            mat_khau VARCHAR(255) NOT NULL,
            vai_tro ENUM('admin', 'customer') DEFAULT 'customer',
            so_dien_thoai VARCHAR(20) NULL,
            dia_chi TEXT NULL,
            ngay_sinh DATE NULL,
            gioi_tinh ENUM('nam', 'nu', 'khac') NULL,
            trang_thai ENUM('active', 'inactive') DEFAULT 'active',
            remember_token VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            INDEX idx_users_email (email),
            INDEX idx_users_role (vai_tro),
            INDEX idx_users_status (trang_thai)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng users\n";

    // Bảng the_loai
    $pdo->exec("
        CREATE TABLE the_loai (
            ma_the_loai INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ten_the_loai VARCHAR(255) NOT NULL,
            duong_dan VARCHAR(255) UNIQUE NOT NULL,
            mo_ta TEXT NULL,
            hinh_anh VARCHAR(255) NULL,
            trang_thai ENUM('active', 'inactive') DEFAULT 'active',
            thu_tu INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            INDEX idx_theloai_status (trang_thai),
            INDEX idx_theloai_slug (duong_dan)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng the_loai\n";

    // Bảng tac_gia
    $pdo->exec("
        CREATE TABLE tac_gia (
            ma_tac_gia INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ten_tac_gia VARCHAR(255) NOT NULL,
            duong_dan VARCHAR(255) UNIQUE NOT NULL,
            tieu_su TEXT NULL,
            hinh_anh VARCHAR(255) NULL,
            ngay_sinh DATE NULL,
            ngay_mat DATE NULL,
            quoc_tich VARCHAR(100) NULL,
            trang_thai ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            INDEX idx_tacgia_status (trang_thai),
            INDEX idx_tacgia_slug (duong_dan)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng tac_gia\n";

    // Bảng nha_xuat_ban
    $pdo->exec("
        CREATE TABLE nha_xuat_ban (
            ma_nxb INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ten_nxb VARCHAR(255) NOT NULL,
            duong_dan VARCHAR(255) UNIQUE NOT NULL,
            dia_chi TEXT NULL,
            so_dien_thoai VARCHAR(20) NULL,
            email VARCHAR(255) NULL,
            website VARCHAR(255) NULL,
            mo_ta TEXT NULL,
            trang_thai ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            INDEX idx_nxb_status (trang_thai),
            INDEX idx_nxb_slug (duong_dan)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng nha_xuat_ban\n";

    // Bảng sach
    $pdo->exec("
        CREATE TABLE sach (
            ma_sach INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ten_sach VARCHAR(255) NOT NULL,
            duong_dan VARCHAR(255) UNIQUE NOT NULL,
            mo_ta TEXT NULL,
            noi_dung LONGTEXT NULL,
            hinh_anh VARCHAR(255) NULL,
            gia_ban DECIMAL(10,0) NOT NULL,
            gia_khuyen_mai DECIMAL(10,0) NULL,
            so_luong_ton INT UNSIGNED DEFAULT 0,
            ngay_xuat_ban DATE NULL,
            nam_xuat_ban YEAR NULL,
            ma_the_loai INT UNSIGNED NULL,
            ma_tac_gia INT UNSIGNED NULL,
            ma_nxb INT UNSIGNED NULL,
            trang_thai ENUM('active', 'inactive') DEFAULT 'active',
            luot_xem INT UNSIGNED DEFAULT 0,
            diem_trung_binh DECIMAL(3,2) DEFAULT 0.00,
            so_luot_danh_gia INT UNSIGNED DEFAULT 0,
            noi_bat BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (ma_the_loai) REFERENCES the_loai(ma_the_loai) ON DELETE SET NULL,
            FOREIGN KEY (ma_tac_gia) REFERENCES tac_gia(ma_tac_gia) ON DELETE SET NULL,
            FOREIGN KEY (ma_nxb) REFERENCES nha_xuat_ban(ma_nxb) ON DELETE SET NULL,
            INDEX idx_sach_status (trang_thai),
            INDEX idx_sach_category (ma_the_loai),
            INDEX idx_sach_author (ma_tac_gia),
            INDEX idx_sach_publisher (ma_nxb),
            INDEX idx_sach_price (gia_ban),
            INDEX idx_sach_views (luot_xem),
            INDEX idx_sach_rating (diem_trung_binh),
            FULLTEXT idx_sach_search (ten_sach, mo_ta)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng sach\n";

    // Bảng don_hang
    $pdo->exec("
        CREATE TABLE don_hang (
            ma_don_hang INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_nguoi_dung BIGINT UNSIGNED NOT NULL,
            ngay_dat_hang TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            tong_tien DECIMAL(12,0) NOT NULL,
            trang_thai ENUM('pending', 'confirmed', 'shipping', 'delivered', 'cancelled') DEFAULT 'pending',
            phuong_thuc_thanh_toan ENUM('cod', 'bank_transfer', 'credit_card') DEFAULT 'cod',
            dia_chi_giao_hang TEXT NOT NULL,
            so_dien_thoai_giao_hang VARCHAR(20) NOT NULL,
            ten_nguoi_nhan VARCHAR(255) NOT NULL,
            ghi_chu TEXT NULL,
            ma_giam_gia VARCHAR(50) NULL,
            so_tien_giam DECIMAL(10,0) DEFAULT 0,
            phi_van_chuyen DECIMAL(10,0) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (ma_nguoi_dung) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_donhang_user (ma_nguoi_dung),
            INDEX idx_donhang_status (trang_thai),
            INDEX idx_donhang_date (ngay_dat_hang)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng don_hang\n";

    // Bảng chi_tiet_don_hang
    $pdo->exec("
        CREATE TABLE chi_tiet_don_hang (
            ma_chi_tiet INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_don_hang INT UNSIGNED NOT NULL,
            ma_sach INT UNSIGNED NOT NULL,
            so_luong INT UNSIGNED NOT NULL,
            gia_ban DECIMAL(10,0) NOT NULL,
            thanh_tien DECIMAL(12,0) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (ma_don_hang) REFERENCES don_hang(ma_don_hang) ON DELETE CASCADE,
            FOREIGN KEY (ma_sach) REFERENCES sach(ma_sach) ON DELETE CASCADE,
            INDEX idx_chitiet_order (ma_don_hang),
            INDEX idx_chitiet_book (ma_sach)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng chi_tiet_don_hang\n";

    // Bảng gio_hang
    $pdo->exec("
        CREATE TABLE gio_hang (
            ma_gio_hang INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_nguoi_dung BIGINT UNSIGNED NOT NULL,
            ma_sach INT UNSIGNED NOT NULL,
            so_luong INT UNSIGNED NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (ma_nguoi_dung) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (ma_sach) REFERENCES sach(ma_sach) ON DELETE CASCADE,
            UNIQUE KEY unique_user_book (ma_nguoi_dung, ma_sach),
            INDEX idx_giohang_user (ma_nguoi_dung)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng gio_hang\n";

    // Bảng yeu_thich
    $pdo->exec("
        CREATE TABLE yeu_thich (
            ma_yeu_thich INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_nguoi_dung BIGINT UNSIGNED NOT NULL,
            ma_sach INT UNSIGNED NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (ma_nguoi_dung) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (ma_sach) REFERENCES sach(ma_sach) ON DELETE CASCADE,
            UNIQUE KEY unique_user_book_favorite (ma_nguoi_dung, ma_sach),
            INDEX idx_yeuthich_user (ma_nguoi_dung)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng yeu_thich\n";

    // Bảng danh_gia
    $pdo->exec("
        CREATE TABLE danh_gia (
            ma_danh_gia INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_sach INT UNSIGNED NOT NULL,
            ma_nguoi_dung BIGINT UNSIGNED NOT NULL,
            diem_so TINYINT UNSIGNED NOT NULL CHECK (diem_so >= 1 AND diem_so <= 5),
            noi_dung TEXT NULL,
            trang_thai ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            FOREIGN KEY (ma_sach) REFERENCES sach(ma_sach) ON DELETE CASCADE,
            FOREIGN KEY (ma_nguoi_dung) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_user_book_review (ma_nguoi_dung, ma_sach),
            INDEX idx_danhgia_book (ma_sach),
            INDEX idx_danhgia_user (ma_nguoi_dung),
            INDEX idx_danhgia_status (trang_thai)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng danh_gia\n";

    // Bảng ma_giam_gia
    $pdo->exec("
        CREATE TABLE ma_giam_gia (
            ma_giam_gia INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            ma_code VARCHAR(50) UNIQUE NOT NULL,
            ten_ma_giam_gia VARCHAR(255) NOT NULL,
            mo_ta TEXT NULL,
            loai_giam_gia ENUM('percent', 'fixed') NOT NULL,
            gia_tri_giam DECIMAL(10,2) NOT NULL,
            gia_tri_don_hang_toi_thieu DECIMAL(10,0) DEFAULT 0,
            so_luong_toi_da INT UNSIGNED NULL,
            da_su_dung INT UNSIGNED DEFAULT 0,
            ngay_bat_dau DATE NOT NULL,
            ngay_ket_thuc DATE NOT NULL,
            trang_thai ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            INDEX idx_magiamgia_code (ma_code),
            INDEX idx_magiamgia_status (trang_thai),
            INDEX idx_magiamgia_dates (ngay_bat_dau, ngay_ket_thuc)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng ma_giam_gia\n";

    // Bảng migrations
    $pdo->exec("
        CREATE TABLE migrations (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255) NOT NULL,
            batch INT NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "   ✅ Bảng migrations\n\n";

    // 5. Thêm dữ liệu mẫu
    echo "4. Thêm dữ liệu mẫu...\n";
    
    // Admin user
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (ho_ten, email, mat_khau, vai_tro, trang_thai, email_verified_at) 
        VALUES ('Administrator', 'admin@bookstore.vn', '{$hashedPassword}', 'admin', 'active', NOW())
    ");
    echo "   ✅ Admin user: admin@bookstore.vn / admin123\n";

    // Customer user
    $hashedPassword = password_hash('customer123', PASSWORD_DEFAULT);
    $pdo->exec("
        INSERT INTO users (ho_ten, email, mat_khau, vai_tro, trang_thai, email_verified_at) 
        VALUES ('Khách hàng', 'customer@bookstore.vn', '{$hashedPassword}', 'customer', 'active', NOW())
    ");
    echo "   ✅ Customer user: customer@bookstore.vn / customer123\n";

    // Thể loại
    $categories = [
        ['Văn học', 'van-hoc', 'Sách văn học trong và ngoài nước'],
        ['Kinh tế', 'kinh-te', 'Sách về kinh tế, kinh doanh'],
        ['Công nghệ', 'cong-nghe', 'Sách về công nghệ thông tin'],
        ['Giáo dục', 'giao-duc', 'Sách giáo khoa và tham khảo'],
        ['Thiếu nhi', 'thieu-nhi', 'Sách dành cho trẻ em'],
        ['Tâm lý', 'tam-ly', 'Sách về tâm lý học']
    ];
    
    foreach ($categories as $cat) {
        $pdo->exec("
            INSERT INTO the_loai (ten_the_loai, duong_dan, mo_ta, trang_thai) 
            VALUES ('{$cat[0]}', '{$cat[1]}', '{$cat[2]}', 'active')
        ");
    }
    echo "   ✅ " . count($categories) . " thể loại\n";

    // Tác giả
    $authors = [
        ['Nguyễn Nhật Ánh', 'nguyen-nhat-anh', 'Nhà văn nổi tiếng Việt Nam'],
        ['Tô Hoài', 'to-hoai', 'Nhà văn Việt Nam'],
        ['Nam Cao', 'nam-cao', 'Nhà văn hiện thực'],
        ['Vũ Trọng Phụng', 'vu-trong-phung', 'Nhà văn hiện thực'],
        ['Haruki Murakami', 'haruki-murakami', 'Nhà văn Nhật Bản'],
        ['Paulo Coelho', 'paulo-coelho', 'Nhà văn Brazil']
    ];
    
    foreach ($authors as $author) {
        $pdo->exec("
            INSERT INTO tac_gia (ten_tac_gia, duong_dan, tieu_su, trang_thai) 
            VALUES ('{$author[0]}', '{$author[1]}', '{$author[2]}', 'active')
        ");
    }
    echo "   ✅ " . count($authors) . " tác giả\n";

    // Nhà xuất bản
    $publishers = [
        ['NXB Trẻ', 'nxb-tre', 'Nhà xuất bản Trẻ'],
        ['NXB Kim Đồng', 'nxb-kim-dong', 'Nhà xuất bản Kim Đồng'],
        ['NXB Văn học', 'nxb-van-hoc', 'Nhà xuất bản Văn học'],
        ['NXB Giáo dục', 'nxb-giao-duc', 'Nhà xuất bản Giáo dục Việt Nam']
    ];
    
    foreach ($publishers as $pub) {
        $pdo->exec("
            INSERT INTO nha_xuat_ban (ten_nxb, duong_dan, mo_ta, trang_thai) 
            VALUES ('{$pub[0]}', '{$pub[1]}', '{$pub[2]}', 'active')
        ");
    }
    echo "   ✅ " . count($publishers) . " nhà xuất bản\n";

    // Sách mẫu
    $books = [
        ['Tôi thấy hoa vàng trên cỏ xanh', 'toi-thay-hoa-vang-tren-co-xanh', 'Tiểu thuyết của Nguyễn Nhật Ánh', 150000, 120000, 50, 1, 1, 1],
        ['Dế Mèn phiêu lưu ký', 'de-men-phieu-luu-ky', 'Tác phẩm nổi tiếng của Tô Hoài', 80000, null, 30, 5, 2, 2],
        ['Chí Phèo', 'chi-pheo', 'Truyện ngắn của Nam Cao', 60000, 50000, 25, 1, 3, 3],
        ['Số đỏ', 'so-do', 'Tiểu thuyết của Vũ Trọng Phụng', 90000, null, 20, 1, 4, 3],
        ['Rừng Na Uy', 'rung-na-uy', 'Tiểu thuyết của Haruki Murakami', 200000, 180000, 15, 1, 5, 1],
        ['Nhà giả kim', 'nha-gia-kim', 'Tiểu thuyết của Paulo Coelho', 120000, 100000, 40, 1, 6, 1]
    ];
    
    foreach ($books as $book) {
        $giakm = $book[4] ? "'{$book[4]}'" : 'NULL';
        $pdo->exec("
            INSERT INTO sach (ten_sach, duong_dan, mo_ta, gia_ban, gia_khuyen_mai, so_luong_ton, ma_the_loai, ma_tac_gia, ma_nxb, trang_thai, luot_xem, nam_xuat_ban) 
            VALUES ('{$book[0]}', '{$book[1]}', '{$book[2]}', {$book[3]}, {$giakm}, {$book[5]}, {$book[6]}, {$book[7]}, {$book[8]}, 'active', " . rand(100, 1000) . ", " . rand(2020, 2024) . ")
        ");
    }
    echo "   ✅ " . count($books) . " sách mẫu\n";

    // Mã giảm giá
    $discounts = [
        ['WELCOME10', 'Chào mừng khách hàng mới', 'percent', 10, 100000, 100, '2024-01-01', '2024-12-31'],
        ['SALE20', 'Giảm giá 20%', 'percent', 20, 200000, 50, '2024-01-01', '2024-12-31'],
        ['FREESHIP', 'Miễn phí vận chuyển', 'fixed', 30000, 150000, 200, '2024-01-01', '2024-12-31']
    ];
    
    foreach ($discounts as $discount) {
        $pdo->exec("
            INSERT INTO ma_giam_gia (ma_code, ten_ma_giam_gia, mo_ta, loai_giam_gia, gia_tri_giam, gia_tri_don_hang_toi_thieu, so_luong_toi_da, ngay_bat_dau, ngay_ket_thuc, trang_thai) 
            VALUES ('{$discount[0]}', '{$discount[1]}', '{$discount[2]}', '{$discount[3]}', {$discount[4]}, {$discount[5]}, {$discount[6]}, '{$discount[7]}', '{$discount[8]}', 'active')
        ");
    }
    echo "   ✅ " . count($discounts) . " mã giảm giá\n\n";

    // 6. Thống kê
    echo "5. Thống kê database...\n";
    
    $tables = ['users', 'the_loai', 'tac_gia', 'nha_xuat_ban', 'sach', 'ma_giam_gia'];
    foreach ($tables as $table) {
        $result = $pdo->query("SELECT COUNT(*) as count FROM {$table}");
        $count = $result->fetch()['count'];
        echo "   📊 {$table}: {$count} records\n";
    }
    echo "\n";

    // 7. Tổng kết
    echo "🎉 TẠO DATABASE THÀNH CÔNG!\n";
    echo "==========================\n";
    echo "✅ Database: {$dbName}\n";
    echo "✅ Bảng: 11 bảng chính\n";
    echo "✅ Dữ liệu mẫu: Đã thêm\n";
    echo "✅ Indexes: Đã tối ưu\n\n";
    
    echo "👤 TÀI KHOẢN:\n";
    echo "   Admin: admin@bookstore.vn / admin123\n";
    echo "   Customer: customer@bookstore.vn / customer123\n\n";
    
    echo "🚀 KHỞI ĐỘNG:\n";
    echo "   php artisan serve\n";
    echo "   http://127.0.0.1:8000/pure\n\n";

} catch (PDOException $e) {
    echo "❌ LỖI DATABASE: " . $e->getMessage() . "\n\n";
    echo "📝 KIỂM TRA:\n";
    echo "   - MySQL server có đang chạy không?\n";
    echo "   - Thông tin kết nối có đúng không?\n";
    echo "   - User có quyền tạo database không?\n\n";
} catch (Exception $e) {
    echo "❌ LỖI: " . $e->getMessage() . "\n\n";
}

echo "🏁 Hoàn thành!\n";