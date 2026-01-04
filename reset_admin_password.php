<?php
/**
 * Script reset mật khẩu admin
 * Chạy: php reset_admin_password.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

echo "=== RESET MẬT KHẨU ADMIN ===\n\n";

// Tìm user admin
$admin = User::where('email', 'admin@bookstore.vn')->first();

if (!$admin) {
    echo "⚠️ Không tìm thấy user admin@bookstore.vn\n";
    echo "Đang tạo user admin mới...\n\n";
    
    // Tạo mới
    $admin = new User();
    $admin->ho_ten = 'Administrator';
    $admin->email = 'admin@bookstore.vn';
    $admin->vai_tro = 'admin';
}

// Reset mật khẩu - KHÔNG dùng mutator, hash trực tiếp
$newPassword = 'admin123';
$hashedPassword = Hash::make($newPassword);

// Update trực tiếp vào database để tránh double hash
DB::table('users')
    ->where('email', 'admin@bookstore.vn')
    ->update(['mat_khau' => $hashedPassword]);

// Hoặc nếu là user mới
if (!$admin->exists) {
    DB::table('users')->insert([
        'ho_ten' => 'Administrator',
        'email' => 'admin@bookstore.vn',
        'mat_khau' => $hashedPassword,
        'vai_tro' => 'admin',
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

echo "✓ Đã reset mật khẩu admin!\n\n";
echo "Thông tin đăng nhập:\n";
echo "  Email: admin@bookstore.vn\n";
echo "  Mật khẩu: admin123\n\n";

// Verify
$admin = User::where('email', 'admin@bookstore.vn')->first();
echo "Kiểm tra lại:\n";
echo "  Hash trong DB: " . substr($admin->mat_khau, 0, 30) . "...\n";
echo "  Hash::check('admin123'): " . (Hash::check('admin123', $admin->mat_khau) ? 'TRUE ✓' : 'FALSE ✗') . "\n";

// Test Auth::attempt
echo "\nTest Auth::attempt:\n";
$result = \Auth::attempt(['email' => 'admin@bookstore.vn', 'password' => 'admin123']);
echo "  Kết quả: " . ($result ? 'THÀNH CÔNG ✓' : 'THẤT BẠI ✗') . "\n";

echo "\n=== HOÀN TẤT ===\n";
