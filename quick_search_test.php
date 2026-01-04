<?php
// Quick test search page

$urls = [
    'http://127.0.0.1:8000/search?q=Kiều',
    'http://127.0.0.1:8000/search?q=Nam+Cao',
    'http://127.0.0.1:8000/search?q=văn+học',
    'http://127.0.0.1:8000/search',
];

echo "=== TEST TRANG TÌM KIẾM ===\n\n";

foreach ($urls as $url) {
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "  HTTP: $httpCode\n";
    echo "  Size: " . strlen($response) . " bytes\n";
    
    // Tìm số kết quả
    if (preg_match('/Tìm thấy.*?<span>(\d+)<\/span>.*?kết quả/s', $response, $m)) {
        echo "  Kết quả: {$m[1]} sách ✓\n";
    } elseif (strpos($response, 'Không tìm thấy kết quả') !== false) {
        echo "  Kết quả: 0 sách (empty state)\n";
    } elseif (strpos($response, 'Vui lòng nhập từ khóa') !== false) {
        echo "  Kết quả: Chưa nhập từ khóa\n";
    } else {
        echo "  Kết quả: Không xác định\n";
    }
    
    // Kiểm tra lỗi
    if (strpos($response, 'Exception') !== false || strpos($response, 'Whoops') !== false) {
        echo "  ⚠️ CÓ LỖI!\n";
    }
    
    echo "\n";
}

echo "=== HOÀN TẤT ===\n";
