<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== THÊM DỮ LIỆU SÁCH MẪU ===\n";

try {
    // Thêm tác giả Nam Cao nếu chưa có
    $namCao = \App\Models\TacGia::where('ten_tac_gia', 'Nam Cao')->first();
    if (!$namCao) {
        $namCao = \App\Models\TacGia::create([
            'ten_tac_gia' => 'Nam Cao',
            'duong_dan' => 'nam-cao',
            'tieu_su' => 'Nhà văn hiện thực Việt Nam',
            'ngay_sinh' => '1915-10-29',
            'ngay_mat' => '1951-11-30',
            'quoc_tich' => 'Việt Nam'
        ]);
        echo "Đã thêm tác giả Nam Cao (ID: {$namCao->ma_tac_gia})\n";
    }

    // Thêm sách "Chí Phèo"
    $chiPheo = \App\Models\Sach::where('ten_sach', 'Chí Phèo')->first();
    if (!$chiPheo) {
        $chiPheo = \App\Models\Sach::create([
            'ten_sach' => 'Chí Phèo',
            'duong_dan' => 'chi-pheo',
            'mo_ta' => 'Tác phẩm nổi tiếng của Nam Cao về số phận con người trong xã hội cũ',
            'gia_ban' => 45000,
            'gia_khuyen_mai' => 35000,
            'so_luong_ton' => 50,
            'nam_xuat_ban' => 1941,
            'ma_the_loai' => 1, // Văn học
            'ma_tac_gia' => $namCao->ma_tac_gia,
            'ma_nxb' => 1, // NXB Văn học
            'trang_thai' => 'active'
        ]);
        echo "Đã thêm sách Chí Phèo (ID: {$chiPheo->ma_sach})\n";
    }

    // Thêm sách "Lão Hạc"
    $laoHac = \App\Models\Sach::where('ten_sach', 'Lão Hạc')->first();
    if (!$laoHac) {
        $laoHac = \App\Models\Sach::create([
            'ten_sach' => 'Lão Hạc',
            'duong_dan' => 'lao-hac',
            'mo_ta' => 'Truyện ngắn nổi tiếng của Nam Cao về tình cha con và nghèo khó',
            'gia_ban' => 40000,
            'so_luong_ton' => 30,
            'nam_xuat_ban' => 1943,
            'ma_the_loai' => 1, // Văn học
            'ma_tac_gia' => $namCao->ma_tac_gia,
            'ma_nxb' => 1, // NXB Văn học
            'trang_thai' => 'active'
        ]);
        echo "Đã thêm sách Lão Hạc (ID: {$laoHac->ma_sach})\n";
    }

    echo "\n=== KIỂM TRA DỮ LIỆU SAU KHI THÊM ===\n";
    
    $allBooks = \App\Models\Sach::with(['tacGia', 'theLoai'])->get();
    echo "Tổng số sách: " . $allBooks->count() . "\n";
    
    foreach ($allBooks as $book) {
        echo "- {$book->ten_sach} - {$book->tacGia->ten_tac_gia} - {$book->theLoai->ten_the_loai}\n";
    }

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (line " . $e->getLine() . ")\n";
}