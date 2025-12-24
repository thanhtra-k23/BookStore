<?php

return [
    /*
    |--------------------------------------------------------------------------
    | MySQL Optimization Configuration
    |--------------------------------------------------------------------------
    |
    | Cấu hình tối ưu hóa cho MySQL database
    |
    */

    'connection' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'nha_sach_laravel'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => 'InnoDB',
        'options' => [
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Eloquent ORM Settings
    |--------------------------------------------------------------------------
    */
    
    'eloquent' => [
        // Eager loading relationships để tránh N+1 queries
        'default_with' => [
            'sach' => ['tacGia', 'theLoai', 'nhaXuatBan'],
            'don_hang' => ['nguoiDung', 'chiTietDonHangs.sach'],
            'danh_gia' => ['nguoiDung', 'sach'],
        ],
        
        // Pagination settings
        'per_page' => 12,
        'max_per_page' => 100,
        
        // Cache settings
        'cache_queries' => env('CACHE_QUERIES', true),
        'cache_duration' => 3600, // 1 hour
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Indexes
    |--------------------------------------------------------------------------
    */
    
    'indexes' => [
        'sach' => [
            'primary' => 'ma_sach',
            'foreign' => ['ma_the_loai', 'ma_tac_gia', 'ma_nxb'],
            'search' => ['ten_sach', 'mo_ta'],
            'status' => ['trang_thai', 'so_luong_ton'],
            'price' => ['gia_ban', 'gia_khuyen_mai'],
            'popularity' => ['luot_xem', 'diem_trung_binh'],
        ],
        
        'don_hang' => [
            'primary' => 'ma_don_hang',
            'foreign' => ['ma_nguoi_dung'],
            'status' => ['trang_thai'],
            'date' => ['ngay_dat_hang', 'created_at'],
        ],
        
        'gio_hang' => [
            'composite' => ['ma_nguoi_dung', 'ma_sach'],
        ],
        
        'yeu_thich' => [
            'composite' => ['ma_nguoi_dung', 'ma_sach'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Query Optimization
    |--------------------------------------------------------------------------
    */
    
    'query_optimization' => [
        // Sử dụng select specific columns thay vì select *
        'select_specific_columns' => true,
        
        // Limit default queries
        'default_limit' => 1000,
        
        // Use database transactions
        'use_transactions' => true,
        
        // Batch operations
        'batch_size' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Monitoring
    |--------------------------------------------------------------------------
    */
    
    'monitoring' => [
        'log_slow_queries' => env('LOG_SLOW_QUERIES', true),
        'slow_query_threshold' => 1000, // milliseconds
        'log_query_count' => env('LOG_QUERY_COUNT', false),
    ],
];