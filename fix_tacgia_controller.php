<?php

// Script để sửa TacGiaController - thay anh_dai_dien thành hinh_anh

$controllerPath = 'app/Http/Controllers/TacGiaController.php';
$content = file_get_contents($controllerPath);

// Thay thế tất cả anh_dai_dien thành hinh_anh
$content = str_replace('anh_dai_dien', 'hinh_anh', $content);

// Ghi lại file
file_put_contents($controllerPath, $content);

echo "✅ Đã sửa TacGiaController - thay anh_dai_dien thành hinh_anh\n";