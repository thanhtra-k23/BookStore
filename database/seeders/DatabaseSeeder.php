<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo admin user
        User::firstOrCreate(
            ['email' => 'admin@bookstore.vn'],
            [
                'ho_ten' => 'Admin BookStore',
                'mat_khau' => Hash::make('admin123'),
                'vai_tro' => 'admin',
                'so_dien_thoai' => '0787905089',
                'dia_chi' => 'Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long',
                'xac_minh_email_luc' => now()
            ]
        );

        // Tạo customer user mẫu
        User::firstOrCreate(
            ['email' => 'customer@bookstore.vn'],
            [
                'ho_ten' => 'Khách hàng Test',
                'mat_khau' => Hash::make('customer123'),
                'vai_tro' => 'customer',
                'so_dien_thoai' => '0987654321',
                'dia_chi' => 'Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long',
                'xac_minh_email_luc' => now()
            ]
        );

        // Gọi seeder dữ liệu đầy đủ
        $this->call([
            FullDataSeeder::class,
        ]);
    }
}
