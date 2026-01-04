<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MoreCustomersSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['ho_ten' => 'Trương Minh Tuấn', 'email' => 'truongminhtuan@gmail.com', 'sdt' => '0901111111', 'dia_chi' => '12 Lê Duẩn, Q.1, TP.HCM'],
            ['ho_ten' => 'Lý Thị Mai', 'email' => 'lythimai@gmail.com', 'sdt' => '0902222222', 'dia_chi' => '45 Pasteur, Q.3, TP.HCM'],
            ['ho_ten' => 'Võ Văn Hùng', 'email' => 'vovanhung@gmail.com', 'sdt' => '0903333333', 'dia_chi' => '78 Nguyễn Thị Minh Khai, Q.1, TP.HCM'],
            ['ho_ten' => 'Đinh Thị Ngọc', 'email' => 'dinhthingoc@gmail.com', 'sdt' => '0904444444', 'dia_chi' => '23 Cống Quỳnh, Q.1, TP.HCM'],
            ['ho_ten' => 'Phan Văn Đức', 'email' => 'phanvanduc@gmail.com', 'sdt' => '0905555555', 'dia_chi' => '56 Bùi Viện, Q.1, TP.HCM'],
            ['ho_ten' => 'Huỳnh Thị Lan', 'email' => 'huynhthilan@gmail.com', 'sdt' => '0906666666', 'dia_chi' => '89 Trần Quang Khải, Q.1, TP.HCM'],
            ['ho_ten' => 'Nguyễn Hoàng Nam', 'email' => 'nguyenhoangnam@gmail.com', 'sdt' => '0907777777', 'dia_chi' => '34 Đinh Tiên Hoàng, Q.Bình Thạnh, TP.HCM'],
            ['ho_ten' => 'Trần Thị Hương', 'email' => 'tranthihuong@gmail.com', 'sdt' => '0908888888', 'dia_chi' => '67 Xô Viết Nghệ Tĩnh, Q.Bình Thạnh, TP.HCM'],
            ['ho_ten' => 'Lê Minh Quân', 'email' => 'leminhquan@gmail.com', 'sdt' => '0909999999', 'dia_chi' => '90 Phan Xích Long, Q.Phú Nhuận, TP.HCM'],
            ['ho_ten' => 'Phạm Thị Thảo', 'email' => 'phamthithao@gmail.com', 'sdt' => '0910000001', 'dia_chi' => '123 Hoàng Văn Thụ, Q.Phú Nhuận, TP.HCM'],
            ['ho_ten' => 'Đặng Văn Minh', 'email' => 'dangvanminh@gmail.com', 'sdt' => '0910000002', 'dia_chi' => '456 Nguyễn Văn Trỗi, Q.Phú Nhuận, TP.HCM'],
            ['ho_ten' => 'Vũ Thị Hạnh', 'email' => 'vuthihanh@gmail.com', 'sdt' => '0910000003', 'dia_chi' => '789 Cách Mạng Tháng 8, Q.Tân Bình, TP.HCM'],
            ['ho_ten' => 'Bùi Văn Long', 'email' => 'buivanlong@gmail.com', 'sdt' => '0910000004', 'dia_chi' => '321 Trường Chinh, Q.Tân Bình, TP.HCM'],
            ['ho_ten' => 'Đỗ Thị Thanh', 'email' => 'dothithanh@gmail.com', 'sdt' => '0910000005', 'dia_chi' => '654 Lý Thường Kiệt, Q.Tân Bình, TP.HCM'],
            ['ho_ten' => 'Hoàng Văn Phúc', 'email' => 'hoangvanphuc@gmail.com', 'sdt' => '0910000006', 'dia_chi' => '987 Âu Cơ, Q.Tân Phú, TP.HCM'],
            ['ho_ten' => 'Ngô Thị Yến', 'email' => 'ngothiyen@gmail.com', 'sdt' => '0910000007', 'dia_chi' => '147 Hòa Bình, Q.Tân Phú, TP.HCM'],
            ['ho_ten' => 'Trịnh Văn Tài', 'email' => 'trinhvantai@gmail.com', 'sdt' => '0910000008', 'dia_chi' => '258 Lũy Bán Bích, Q.Tân Phú, TP.HCM'],
            ['ho_ten' => 'Mai Thị Linh', 'email' => 'maithilinh@gmail.com', 'sdt' => '0910000009', 'dia_chi' => '369 Tân Kỳ Tân Quý, Q.Tân Phú, TP.HCM'],
            ['ho_ten' => 'Cao Văn Thắng', 'email' => 'caovanthang@gmail.com', 'sdt' => '0910000010', 'dia_chi' => '741 Quang Trung, Q.Gò Vấp, TP.HCM'],
            ['ho_ten' => 'Lương Thị Hồng', 'email' => 'luongthihong@gmail.com', 'sdt' => '0910000011', 'dia_chi' => '852 Nguyễn Oanh, Q.Gò Vấp, TP.HCM'],
        ];

        $count = 0;
        foreach ($customers as $c) {
            $exists = User::where('email', $c['email'])->exists();
            if (!$exists) {
                User::create([
                    'ho_ten' => $c['ho_ten'],
                    'email' => $c['email'],
                    'mat_khau' => Hash::make('password123'),
                    'so_dien_thoai' => $c['sdt'],
                    'dia_chi' => $c['dia_chi'],
                    'vai_tro' => 'customer',
                    'xac_minh_email_luc' => now(),
                ]);
                $count++;
            }
        }
        
        $this->command->info("👥 Đã thêm {$count} khách hàng mới. Tổng users: " . User::count());
    }
}
