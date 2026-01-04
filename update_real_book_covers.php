<?php
/**
 * Script cập nhật hình ảnh bìa sách thực tế
 * Sử dụng placeholder với tên sách và màu sắc theo thể loại
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Sach;

echo "=== CẬP NHẬT HÌNH ẢNH BÌA SÁCH ===\n\n";

// Màu sắc theo thể loại
$categoryColors = [
    'Văn học' => ['8B4513', 'F5DEB3'],      // Nâu - Lúa mì
    'Kinh tế' => ['1E3A5F', 'FFFFFF'],      // Xanh đậm - Trắng
    'Kỹ năng sống' => ['2E8B57', 'FFFFFF'], // Xanh lá - Trắng
    'Khoa học' => ['4B0082', 'FFFFFF'],     // Tím - Trắng
    'Thiếu nhi' => ['FF6B6B', 'FFFFFF'],    // Hồng - Trắng
    'Tâm lý' => ['6B5B95', 'FFFFFF'],       // Tím nhạt - Trắng
    'Lịch sử' => ['8B0000', 'FFD700'],      // Đỏ đậm - Vàng
    'Công nghệ' => ['2C3E50', '3498DB'],    // Xám đậm - Xanh dương
];

// Hình ảnh bìa sách thực tế (từ các nguồn miễn phí)
$realBookCovers = [
    // Văn học Việt Nam
    'Truyện Kiều' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Truyen_Kieu_cover.jpg/220px-Truyen_Kieu_cover.jpg',
    'Dế Mèn Phiêu Lưu Ký' => 'https://upload.wikimedia.org/wikipedia/vi/thumb/8/8e/De_Men_phieu_luu_ky.jpg/220px-De_Men_phieu_luu_ky.jpg',
    'Chí Phèo' => 'https://upload.wikimedia.org/wikipedia/vi/5/5e/Chi_Pheo_bia_sach.jpg',
    
    // Sách nổi tiếng quốc tế
    'Đắc Nhân Tâm' => 'https://upload.wikimedia.org/wikipedia/en/a/a2/How_to_Win_Friends_and_Influence_People.jpg',
    'Nhà Giả Kim' => 'https://upload.wikimedia.org/wikipedia/en/c/c4/TheAlchemist.jpg',
    'Harry Potter và Hòn Đá Phù Thủy' => 'https://upload.wikimedia.org/wikipedia/en/6/6b/Harry_Potter_and_the_Philosopher%27s_Stone_Book_Cover.jpg',
    'Hoàng Tử Bé' => 'https://upload.wikimedia.org/wikipedia/en/0/05/Littleprince.JPG',
    'Sapiens: Lược Sử Loài Người' => 'https://upload.wikimedia.org/wikipedia/en/0/06/%E1%B8%B2omo_Deus_%28Hebrew%29.jpg',
];

$books = Sach::with('theLoai')->get();
$updated = 0;

foreach ($books as $book) {
    $tenSach = $book->ten_sach;
    $theLoai = $book->theLoai->ten_the_loai ?? 'Khác';
    
    // Kiểm tra xem có hình ảnh thực không
    if (isset($realBookCovers[$tenSach])) {
        $imageUrl = $realBookCovers[$tenSach];
    } else {
        // Tạo placeholder với tên sách
        $colors = $categoryColors[$theLoai] ?? ['607D8B', 'FFFFFF'];
        $bgColor = $colors[0];
        $textColor = $colors[1];
        
        // Sử dụng placehold.co với text
        $title = urlencode($tenSach);
        $imageUrl = "https://placehold.co/300x400/{$bgColor}/{$textColor}?text={$title}&font=roboto";
    }
    
    $book->hinh_anh = $imageUrl;
    $book->save();
    echo "✓ {$tenSach}\n";
    $updated++;
}

echo "\n=== HOÀN THÀNH ===\n";
echo "Đã cập nhật {$updated} sách.\n";
