<?php
/**
 * Script cập nhật hình ảnh bìa sách
 * Sử dụng Open Library Covers API cho sách quốc tế
 * Và placeholder đẹp cho sách Việt Nam
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Sach;

echo "=== CẬP NHẬT HÌNH ẢNH BÌA SÁCH ===\n\n";

// Hình ảnh bìa sách thực tế (Open Library Covers API - ISBN)
// Format: https://covers.openlibrary.org/b/isbn/{ISBN}-L.jpg
$bookCovers = [
    // Sách quốc tế nổi tiếng (có ISBN)
    'Đắc Nhân Tâm' => 'https://covers.openlibrary.org/b/isbn/9780671027032-L.jpg',
    'Nghĩ Giàu Làm Giàu' => 'https://covers.openlibrary.org/b/isbn/9781585424337-L.jpg',
    'Cha Giàu Cha Nghèo' => 'https://covers.openlibrary.org/b/isbn/9781612680194-L.jpg',
    'Nhà Giả Kim' => 'https://covers.openlibrary.org/b/isbn/9780062315007-L.jpg',
    'Harry Potter và Hòn Đá Phù Thủy' => 'https://covers.openlibrary.org/b/isbn/9780747532743-L.jpg',
    'Hoàng Tử Bé' => 'https://covers.openlibrary.org/b/isbn/9780156012195-L.jpg',
    'Sapiens: Lược Sử Loài Người' => 'https://covers.openlibrary.org/b/isbn/9780062316097-L.jpg',
    'Homo Deus: Lược Sử Tương Lai' => 'https://covers.openlibrary.org/b/isbn/9780062464316-L.jpg',
    'Lược Sử Thời Gian' => 'https://covers.openlibrary.org/b/isbn/9780553380163-L.jpg',
    'Tư Duy Nhanh Và Chậm' => 'https://covers.openlibrary.org/b/isbn/9780374533557-L.jpg',
    'Clean Code' => 'https://covers.openlibrary.org/b/isbn/9780132350884-L.jpg',
    'Sherlock Holmes Toàn Tập' => 'https://covers.openlibrary.org/b/isbn/9780553212419-L.jpg',
    'Tâm Lý Học Đám Đông' => 'https://covers.openlibrary.org/b/isbn/9780486419565-L.jpg',
    
    // Manga/Truyện tranh
    'Doraemon - Tập 1' => 'https://covers.openlibrary.org/b/isbn/9781569319871-L.jpg',
    'Conan - Tập 1' => 'https://covers.openlibrary.org/b/isbn/9781591163275-L.jpg',
];

// Màu sắc theo thể loại cho placeholder
$categoryColors = [
    'Văn học' => ['8B4513', 'F5DEB3'],
    'Kinh tế' => ['1E3A5F', 'FFFFFF'],
    'Kỹ năng sống' => ['2E8B57', 'FFFFFF'],
    'Khoa học' => ['4B0082', 'FFFFFF'],
    'Thiếu nhi' => ['FF6B6B', 'FFFFFF'],
    'Tâm lý' => ['6B5B95', 'FFFFFF'],
    'Lịch sử' => ['8B0000', 'FFD700'],
    'Công nghệ' => ['2C3E50', '3498DB'],
];

$books = Sach::with('theLoai')->get();
$updated = 0;

foreach ($books as $book) {
    $tenSach = $book->ten_sach;
    $theLoai = $book->theLoai->ten_the_loai ?? 'Khác';
    
    if (isset($bookCovers[$tenSach])) {
        // Sử dụng hình ảnh từ Open Library
        $imageUrl = $bookCovers[$tenSach];
        echo "📚 {$tenSach} (Open Library)\n";
    } else {
        // Tạo placeholder đẹp với tên sách
        $colors = $categoryColors[$theLoai] ?? ['607D8B', 'FFFFFF'];
        $bgColor = $colors[0];
        $textColor = $colors[1];
        
        // Cắt ngắn tên sách nếu quá dài
        $shortTitle = mb_strlen($tenSach) > 25 ? mb_substr($tenSach, 0, 22) . '...' : $tenSach;
        $title = urlencode($shortTitle);
        
        $imageUrl = "https://placehold.co/300x400/{$bgColor}/{$textColor}?text={$title}&font=roboto";
        echo "📖 {$tenSach} (Placeholder - {$theLoai})\n";
    }
    
    $book->hinh_anh = $imageUrl;
    $book->save();
    $updated++;
}

echo "\n=== HOÀN THÀNH ===\n";
echo "Đã cập nhật {$updated} sách.\n";
echo "\nGhi chú:\n";
echo "- Sách quốc tế: Hình bìa thực từ Open Library\n";
echo "- Sách Việt Nam: Placeholder với màu theo thể loại\n";
