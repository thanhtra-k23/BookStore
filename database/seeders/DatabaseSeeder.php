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
        User::create([
            'ho_ten' => 'Admin User',
            'email' => 'admin@bookstore.vn',
            'mat_khau' => Hash::make('admin123'),
            'vai_tro' => 'admin',
            'so_dien_thoai' => '0787905089',
            'dia_chi' => 'Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long',
            'xac_minh_email_luc' => now()
        ]);

        // Tạo customer user
        User::create([
            'ho_ten' => 'Test Customer',
            'email' => 'customer@bookstore.vn',
            'mat_khau' => Hash::make('customer123'),
            'vai_tro' => 'customer',
            'so_dien_thoai' => '0987654321',
            'dia_chi' => 'Khóm 9, Phường Nguyệt Hóa, Tỉnh Vĩnh Long',
            'xac_minh_email_luc' => now()
        ]);

        // Gọi các seeder khác
        $this->call([
            SampleDataSeeder::class,
        ]);
    }
}
