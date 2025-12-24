<?php

// Script để sửa tất cả route names trong views
$viewsPath = 'resources/views';

// Danh sách các route cần sửa
$routeReplacements = [
    // Sách
    'sach.index' => 'admin.sach.index',
    'sach.create' => 'admin.sach.create', 
    'sach.store' => 'admin.sach.store',
    'sach.show' => 'admin.sach.show',
    'sach.edit' => 'admin.sach.edit',
    'sach.update' => 'admin.sach.update',
    'sach.destroy' => 'admin.sach.destroy',
    'sach.bulk-action' => 'admin.sach.bulk-action',
    'sach.toggle-status' => 'admin.sach.toggle-status',
    
    // Thể loại
    'theloai.index' => 'admin.theloai.index',
    'theloai.create' => 'admin.theloai.create',
    'theloai.store' => 'admin.theloai.store', 
    'theloai.show' => 'admin.theloai.show',
    'theloai.edit' => 'admin.theloai.edit',
    'theloai.update' => 'admin.theloai.update',
    'theloai.destroy' => 'admin.theloai.destroy',
    
    // Tác giả
    'tacgia.index' => 'admin.tacgia.index',
    'tacgia.create' => 'admin.tacgia.create',
    'tacgia.store' => 'admin.tacgia.store',
    'tacgia.show' => 'admin.tacgia.show',
    'tacgia.edit' => 'admin.tacgia.edit',
    'tacgia.update' => 'admin.tacgia.update',
    'tacgia.destroy' => 'admin.tacgia.destroy',
    'tacgia.bulk-action' => 'admin.tacgia.bulk-action',
    'tacgia.toggle-status' => 'admin.tacgia.toggle-status',
    'tacgia.export' => 'admin.tacgia.export',
    'tacgia.store' => 'admin.tacgia.store',
    'tacgia.show' => 'admin.tacgia.show', 
    'tacgia.edit' => 'admin.tacgia.edit',
    'tacgia.update' => 'admin.tacgia.update',
    'tacgia.destroy' => 'admin.tacgia.destroy',
    
    // Nhà xuất bản
    'nhaxuatban.index' => 'admin.nhaxuatban.index',
    'nhaxuatban.create' => 'admin.nhaxuatban.create',
    'nhaxuatban.store' => 'admin.nhaxuatban.store',
    'nhaxuatban.show' => 'admin.nhaxuatban.show',
    'nhaxuatban.edit' => 'admin.nhaxuatban.edit',
    'nhaxuatban.update' => 'admin.nhaxuatban.update',
    'nhaxuatban.destroy' => 'admin.nhaxuatban.destroy',
    'nhaxuatban.bulk-action' => 'admin.nhaxuatban.bulk-action',
    'nhaxuatban.toggle-status' => 'admin.nhaxuatban.toggle-status',
    'nhaxuatban.export' => 'admin.nhaxuatban.export', 
    'nhaxuatban.update' => 'admin.nhaxuatban.update',
    'nhaxuatban.destroy' => 'admin.nhaxuatban.destroy',
    
    // Đơn hàng
    'donhang.index' => 'admin.donhang.index',
    'donhang.create' => 'admin.donhang.create',
    'donhang.store' => 'admin.donhang.store',
    'donhang.show' => 'admin.donhang.show',
    'donhang.edit' => 'admin.donhang.edit',
    'donhang.update' => 'admin.donhang.update', 
    'donhang.destroy' => 'admin.donhang.destroy',
    
    // Chi tiết đơn hàng
    'chitietdonhang.index' => 'admin.chitietdonhang.index',
    'chitietdonhang.create' => 'admin.chitietdonhang.create',
    'chitietdonhang.store' => 'admin.chitietdonhang.store',
    'chitietdonhang.show' => 'admin.chitietdonhang.show',
    'chitietdonhang.edit' => 'admin.chitietdonhang.edit',
    'chitietdonhang.update' => 'admin.chitietdonhang.update',
    'chitietdonhang.destroy' => 'admin.chitietdonhang.destroy',
    
    // Người dùng
    'nguoidung.index' => 'admin.nguoidung.index',
    'nguoidung.create' => 'admin.nguoidung.create',
    'nguoidung.store' => 'admin.nguoidung.store',
    'nguoidung.show' => 'admin.nguoidung.show',
    'nguoidung.edit' => 'admin.nguoidung.edit',
    'nguoidung.update' => 'admin.nguoidung.update',
    'nguoidung.destroy' => 'admin.nguoidung.destroy',
    'nguoidung.bulk-action' => 'admin.nguoidung.bulk-action',
    'nguoidung.export' => 'admin.nguoidung.export',
    
    // Mã giảm giá
    'magiamgia.index' => 'admin.magiamgia.index',
    'magiamgia.create' => 'admin.magiamgia.create',
    'magiamgia.store' => 'admin.magiamgia.store',
    'magiamgia.show' => 'admin.magiamgia.show',
    'magiamgia.edit' => 'admin.magiamgia.edit',
    'magiamgia.update' => 'admin.magiamgia.update',
    'magiamgia.destroy' => 'admin.magiamgia.destroy',
    'magiamgia.bulk-action' => 'admin.magiamgia.bulk-action',
    'magiamgia.export' => 'admin.magiamgia.export',
    'nguoidung.show' => 'admin.nguoidung.show',
    'nguoidung.edit' => 'admin.nguoidung.edit',
    'nguoidung.update' => 'admin.nguoidung.update',
    'nguoidung.destroy' => 'admin.nguoidung.destroy',
    
    // Mã giảm giá
    'magiamgia.index' => 'admin.magiamgia.index',
    'magiamgia.create' => 'admin.magiamgia.create',
    'magiamgia.store' => 'admin.magiamgia.store',
    'magiamgia.show' => 'admin.magiamgia.show',
    'magiamgia.edit' => 'admin.magiamgia.edit',
    'magiamgia.update' => 'admin.magiamgia.update',
    'magiamgia.destroy' => 'admin.magiamgia.destroy',
];

function replaceInFile($filePath, $replacements) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    $content = file_get_contents($filePath);
    $originalContent = $content;
    
    foreach ($replacements as $old => $new) {
        // Thay thế route('old') thành route('new')
        $content = str_replace("route('{$old}')", "route('{$new}')", $content);
        // Thay thế route("old") thành route("new") 
        $content = str_replace('route("' . $old . '")', 'route("' . $new . '")', $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($filePath, $content);
        return true;
    }
    
    return false;
}

function scanDirectory($dir, $replacements) {
    $files = glob($dir . '/*.blade.php');
    $changedFiles = [];
    
    foreach ($files as $file) {
        if (replaceInFile($file, $replacements)) {
            $changedFiles[] = $file;
        }
    }
    
    // Scan subdirectories
    $subdirs = glob($dir . '/*', GLOB_ONLYDIR);
    foreach ($subdirs as $subdir) {
        $changedFiles = array_merge($changedFiles, scanDirectory($subdir, $replacements));
    }
    
    return $changedFiles;
}

echo "🔧 FIXING ROUTE NAMES IN VIEWS\n";
echo "==============================\n";

$changedFiles = scanDirectory($viewsPath, $routeReplacements);

if (empty($changedFiles)) {
    echo "✅ No files needed to be changed.\n";
} else {
    echo "✅ Fixed " . count($changedFiles) . " files:\n";
    foreach ($changedFiles as $file) {
        echo "   - " . str_replace($viewsPath . '/', '', $file) . "\n";
    }
}

echo "\n🎉 Route fixing completed!\n";