<?php

/**
 * Script kiểm tra nhanh một số trang chính
 */

echo "🧪 KIỂM TRA NHANH CÁC TRANG CHÍNH\n";
echo "=================================\n\n";

$baseUrl = 'http://127.0.0.1:8000';

// Chỉ test một số trang chính
$urls = [
    '/' => 'Trang chủ',
    '/pure' => 'Trang chủ Pure Blade',
    '/login' => 'Đăng nhập',
    '/about' => 'Giới thiệu',
    '/api/cart/count' => 'API Cart Count',
];

foreach ($urls as $path => $name) {
    $url = $baseUrl . $path;
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 5,
            'ignore_errors' => true
        ]
    ]);
    
    $startTime = microtime(true);
    $response = @file_get_contents($url, false, $context);
    $endTime = microtime(true);
    
    $responseTime = round(($endTime - $startTime) * 1000, 2);
    
    if ($response === false) {
        echo "❌ {$name}: Không thể kết nối\n";
    } else {
        // Kiểm tra HTTP status
        $httpCode = 200;
        if (isset($http_response_header)) {
            foreach ($http_response_header as $header) {
                if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $header, $matches)) {
                    $httpCode = (int)$matches[1];
                    break;
                }
            }
        }
        
        if ($httpCode >= 400) {
            echo "❌ {$name}: HTTP {$httpCode} ({$responseTime}ms)\n";
        } else {
            echo "✅ {$name}: OK ({$responseTime}ms)\n";
        }
    }
}

echo "\n🏁 Hoàn thành!\n";