<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TẠO DỮ LIỆU MẪU MÃ GIẢM GIÁ ===\n\n";

try {
    // Xóa dữ liệu cũ nếu có
    \App\Models\MaGiamGia::truncate();
    
    // Tạo các mã giảm giá mẫu
    $discountCodes = [
        [
            'ma_code' => 'WELCOME10',
            'ten_ma_giam_gia' => 'Chào mừng khách hàng mới',
            'mo_ta' => 'Giảm giá 10% cho khách hàng mới đăng ký',
            'loai_giam_gia' => 'phan_tram',
            'gia_tri_giam' => 10,
            'gia_tri_don_hang_toi_thieu' => 100000,
            'gia_tri_giam_toi_da' => 50000,
            'so_luong' => 100,
            'da_su_dung' => 15,
            'gioi_han_su_dung_moi_user' => 1,
            'ngay_bat_dau' => now()->subDays(10),
            'ngay_ket_thuc' => now()->addDays(20),
            'trang_thai' => true
        ],
        [
            'ma_code' => 'SALE50K',
            'ten_ma_giam_gia' => 'Giảm giá 50K',
            'mo_ta' => 'Giảm ngay 50.000đ cho đơn hàng từ 500.000đ',
            'loai_giam_gia' => 'so_tien',
            'gia_tri_giam' => 50000,
            'gia_tri_don_hang_toi_thieu' => 500000,
            'gia_tri_giam_toi_da' => null,
            'so_luong' => 50,
            'da_su_dung' => 8,
            'gioi_han_su_dung_moi_user' => 2,
            'ngay_bat_dau' => now()->subDays(5),
            'ngay_ket_thuc' => now()->addDays(15),
            'trang_thai' => true
        ],
        [
            'ma_code' => 'FREESHIP',
            'ten_ma_giam_gia' => 'Miễn phí vận chuyển',
            'mo_ta' => 'Miễn phí vận chuyển cho đơn hàng từ 200.000đ',
            'loai_giam_gia' => 'so_tien',
            'gia_tri_giam' => 30000,
            'gia_tri_don_hang_toi_thieu' => 200000,
            'gia_tri_giam_toi_da' => null,
            'so_luong' => null, // Không giới hạn
            'da_su_dung' => 25,
            'gioi_han_su_dung_moi_user' => 3,
            'ngay_bat_dau' => now()->subDays(30),
            'ngay_ket_thuc' => now()->addDays(30),
            'trang_thai' => true
        ],
        [
            'ma_code' => 'BIGDEAL20',
            'ten_ma_giam_gia' => 'Khuyến mãi lớn 20%',
            'mo_ta' => 'Giảm giá 20% cho đơn hàng từ 1.000.000đ - Chỉ áp dụng cuối tuần',
            'loai_giam_gia' => 'phan_tram',
            'gia_tri_giam' => 20,
            'gia_tri_don_hang_toi_thieu' => 1000000,
            'gia_tri_giam_toi_da' => 200000,
            'so_luong' => 20,
            'da_su_dung' => 3,
            'gioi_han_su_dung_moi_user' => 1,
            'ngay_bat_dau' => now()->addDays(1),
            'ngay_ket_thuc' => now()->addDays(7),
            'trang_thai' => true
        ],
        [
            'ma_code' => 'EXPIRED15',
            'ten_ma_giam_gia' => 'Mã đã hết hạn',
            'mo_ta' => 'Mã giảm giá 15% đã hết hạn sử dụng',
            'loai_giam_gia' => 'phan_tram',
            'gia_tri_giam' => 15,
            'gia_tri_don_hang_toi_thieu' => 300000,
            'gia_tri_giam_toi_da' => 100000,
            'so_luong' => 30,
            'da_su_dung' => 30, // Đã hết lượt
            'gioi_han_su_dung_moi_user' => 1,
            'ngay_bat_dau' => now()->subDays(20),
            'ngay_ket_thuc' => now()->subDays(5), // Đã hết hạn
            'trang_thai' => false
        ],
        [
            'ma_code' => 'INACTIVE25',
            'ten_ma_giam_gia' => 'Mã không hoạt động',
            'mo_ta' => 'Mã giảm giá 25% tạm thời không hoạt động',
            'loai_giam_gia' => 'phan_tram',
            'gia_tri_giam' => 25,
            'gia_tri_don_hang_toi_thieu' => 800000,
            'gia_tri_giam_toi_da' => 300000,
            'so_luong' => 10,
            'da_su_dung' => 0,
            'gioi_han_su_dung_moi_user' => 1,
            'ngay_bat_dau' => now()->subDays(2),
            'ngay_ket_thuc' => now()->addDays(10),
            'trang_thai' => false // Không hoạt động
        ]
    ];

    foreach ($discountCodes as $index => $data) {
        $maGiamGia = \App\Models\MaGiamGia::create($data);
        echo "✅ Đã tạo mã giảm giá: {$maGiamGia->ma_code} - {$maGiamGia->ten_ma_giam_gia}\n";
    }

    echo "\n=== THỐNG KÊ SAU KHI TẠO ===\n";
    
    $total = \App\Models\MaGiamGia::count();
    $active = \App\Models\MaGiamGia::where('trang_thai', true)->count();
    $available = \App\Models\MaGiamGia::available()->count();
    $expired = \App\Models\MaGiamGia::where('ngay_ket_thuc', '<', now())->count();
    
    echo "Tổng số mã: $total\n";
    echo "Đang hoạt động: $active\n";
    echo "Có thể sử dụng: $available\n";
    echo "Đã hết hạn: $expired\n";

    echo "\n=== CHI TIẾT CÁC MÃ ===\n";
    
    $allCodes = \App\Models\MaGiamGia::all();
    foreach ($allCodes as $code) {
        echo "- {$code->ma_code}: {$code->gia_tri_giam_text} ";
        echo "({$code->getStatusText()}) ";
        echo "[{$code->da_su_dung}" . ($code->so_luong ? "/{$code->so_luong}" : "/∞") . "]\n";
    }

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (line " . $e->getLine() . ")\n";
}