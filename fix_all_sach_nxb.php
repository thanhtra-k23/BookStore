<?php

/**
 * Sửa tất cả sách có NXB không tồn tại
 */

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 SỬA TẤT CẢ SÁCH CÓ NXB KHÔNG TỒN TẠI\n";
echo str_repeat("=", 50) . "\n\n";

try {
    // Tìm tất cả sách
    $allSach = App\Models\Sach::all();
    echo "Tổng số sách: " . $allSach->count() . "\n";
    
    // Tìm NXB mặc định
    $defaultNxb = App\Models\NhaXuatBan::first();
    if (!$defaultNxb) {
        echo "❌ Không có NXB nào trong hệ thống\n";
        exit;
    }
    
    echo "NXB mặc định: {$defaultNxb->ten_nxb} (ID: {$defaultNxb->ma_nxb})\n\n";
    
    $fixedCount = 0;
    
    foreach ($allSach as $sach) {
        // Kiểm tra NXB có tồn tại không
        $nxb = $sach->nhaXuatBan;
        
        if (!$nxb) {
            echo "Sửa sách ID {$sach->ma_sach}: {$sach->ten_sach}\n";
            echo "  Mã NXB cũ: {$sach->ma_nxb} (không tồn tại)\n";
            
            $sach->ma_nxb = $defaultNxb->ma_nxb;
            $sach->save();
            
            echo "  ✅ Đã cập nhật sang NXB: {$defaultNxb->ten_nxb}\n\n";
            $fixedCount++;
        }
    }
    
    echo "🎯 KẾT QUẢ:\n";
    echo "- Đã sửa {$fixedCount} sách\n";
    echo "- Tất cả sách giờ đều có NXB hợp lệ\n";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 50) . "\n";