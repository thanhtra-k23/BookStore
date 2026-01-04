<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sach;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RevenueDataSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('vai_tro', 'customer')->get();
        $sachs = Sach::all();
        
        if ($users->isEmpty() || $sachs->isEmpty()) {
            $this->command->warn('⚠️ Không có users hoặc sách');
            return;
        }

        $trangThais = ['cho_xac_nhan', 'da_xac_nhan', 'dang_chuan_bi', 'dang_giao', 'da_giao'];
        $phuongThucThanhToan = ['cod', 'bank_transfer', 'momo', 'vnpay'];
        
        // Tạo đơn hàng cho 12 tháng gần đây
        $totalOrders = 0;
        $totalRevenue = 0;
        
        for ($monthsAgo = 0; $monthsAgo <= 11; $monthsAgo++) {
            $ordersThisMonth = rand(15, 35); // 15-35 đơn mỗi tháng
            
            for ($i = 0; $i < $ordersThisMonth; $i++) {
                $user = $users->random();
                // Đơn cũ hơn thì nhiều đơn đã giao hơn
                $trangThai = $monthsAgo > 0 ? 'da_giao' : $trangThais[array_rand($trangThais)];
                $soSanPham = rand(1, 5);
                $selectedSachs = $sachs->random(min($soSanPham, $sachs->count()));
                
                $tongTien = 0;
                $chiTiets = [];
                
                foreach ($selectedSachs as $sach) {
                    $soLuong = rand(1, 3);
                    $giaBan = $sach->gia_ban;
                    $giaKhuyenMai = $sach->gia_khuyen_mai;
                    $giaThucTe = $giaKhuyenMai ?? $giaBan;
                    $thanhTien = $giaThucTe * $soLuong;
                    $tongTien += $thanhTien;
                    
                    $chiTiets[] = [
                        'ma_sach' => $sach->ma_sach,
                        'so_luong' => $soLuong,
                        'gia_ban' => $giaBan,
                        'gia_khuyen_mai' => $giaKhuyenMai,
                        'thanh_tien' => $thanhTien,
                    ];
                }
                
                $tienGiamGia = rand(0, 1) ? rand(10000, 100000) : 0;
                $phiVanChuyen = rand(0, 1) ? 30000 : 0;
                $tongThanhToan = max(0, $tongTien - $tienGiamGia + $phiVanChuyen);
                
                // Tạo ngày ngẫu nhiên trong tháng
                $date = now()->subMonths($monthsAgo)->subDays(rand(0, 27));
                $maDonHangUnique = 'DH' . $date->format('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                
                // Kiểm tra mã đơn hàng đã tồn tại chưa
                while (DB::table('don_hang')->where('ma_don_hang_unique', $maDonHangUnique)->exists()) {
                    $maDonHangUnique = 'DH' . $date->format('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                }
                
                try {
                    $donHangId = DB::table('don_hang')->insertGetId([
                        'ma_don_hang_unique' => $maDonHangUnique,
                        'ma_nguoi_dung' => $user->id,
                        'tong_tien' => $tongTien,
                        'tien_giam_gia' => $tienGiamGia,
                        'phi_van_chuyen' => $phiVanChuyen,
                        'tong_thanh_toan' => $tongThanhToan,
                        'ten_nguoi_nhan' => $user->ho_ten ?? 'Khách hàng',
                        'so_dien_thoai_nguoi_nhan' => $user->so_dien_thoai ?? '0901234567',
                        'dia_chi_giao_hang' => $user->dia_chi ?? '123 Đường ABC, TP.HCM',
                        'ghi_chu' => null,
                        'phuong_thuc_thanh_toan' => $phuongThucThanhToan[array_rand($phuongThucThanhToan)],
                        'trang_thai' => $trangThai,
                        'trang_thai_thanh_toan' => $trangThai === 'da_giao' ? 'da_thanh_toan' : 'chua_thanh_toan',
                        'ngay_dat_hang' => $date,
                        'ngay_giao_hang' => $trangThai === 'da_giao' ? $date->copy()->addDays(rand(1, 5)) : null,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                    
                    foreach ($chiTiets as $ct) {
                        DB::table('chi_tiet_don_hang')->insert([
                            'ma_don_hang' => $donHangId,
                            'ma_sach' => $ct['ma_sach'],
                            'so_luong' => $ct['so_luong'],
                            'gia_ban' => $ct['gia_ban'],
                            'gia_khuyen_mai' => $ct['gia_khuyen_mai'],
                            'thanh_tien' => $ct['thanh_tien'],
                            'created_at' => $date,
                            'updated_at' => $date,
                        ]);
                    }
                    
                    $totalOrders++;
                    if ($trangThai !== 'da_huy') {
                        $totalRevenue += $tongThanhToan;
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        // Thêm đơn hàng hôm nay
        for ($i = 0; $i < rand(3, 8); $i++) {
            $user = $users->random();
            $trangThai = $trangThais[array_rand(array_slice($trangThais, 0, 3))]; // Chỉ cho_xac_nhan, da_xac_nhan, dang_chuan_bi
            $soSanPham = rand(1, 4);
            $selectedSachs = $sachs->random(min($soSanPham, $sachs->count()));
            
            $tongTien = 0;
            $chiTiets = [];
            
            foreach ($selectedSachs as $sach) {
                $soLuong = rand(1, 2);
                $giaBan = $sach->gia_ban;
                $giaKhuyenMai = $sach->gia_khuyen_mai;
                $giaThucTe = $giaKhuyenMai ?? $giaBan;
                $thanhTien = $giaThucTe * $soLuong;
                $tongTien += $thanhTien;
                
                $chiTiets[] = [
                    'ma_sach' => $sach->ma_sach,
                    'so_luong' => $soLuong,
                    'gia_ban' => $giaBan,
                    'gia_khuyen_mai' => $giaKhuyenMai,
                    'thanh_tien' => $thanhTien,
                ];
            }
            
            $tienGiamGia = rand(0, 1) ? rand(10000, 50000) : 0;
            $phiVanChuyen = 30000;
            $tongThanhToan = max(0, $tongTien - $tienGiamGia + $phiVanChuyen);
            
            $maDonHangUnique = 'DH' . now()->format('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            while (DB::table('don_hang')->where('ma_don_hang_unique', $maDonHangUnique)->exists()) {
                $maDonHangUnique = 'DH' . now()->format('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            
            try {
                $donHangId = DB::table('don_hang')->insertGetId([
                    'ma_don_hang_unique' => $maDonHangUnique,
                    'ma_nguoi_dung' => $user->id,
                    'tong_tien' => $tongTien,
                    'tien_giam_gia' => $tienGiamGia,
                    'phi_van_chuyen' => $phiVanChuyen,
                    'tong_thanh_toan' => $tongThanhToan,
                    'ten_nguoi_nhan' => $user->ho_ten ?? 'Khách hàng',
                    'so_dien_thoai_nguoi_nhan' => $user->so_dien_thoai ?? '0901234567',
                    'dia_chi_giao_hang' => $user->dia_chi ?? '123 Đường ABC, TP.HCM',
                    'phuong_thuc_thanh_toan' => 'cod',
                    'trang_thai' => $trangThai,
                    'trang_thai_thanh_toan' => 'chua_thanh_toan',
                    'ngay_dat_hang' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                foreach ($chiTiets as $ct) {
                    DB::table('chi_tiet_don_hang')->insert([
                        'ma_don_hang' => $donHangId,
                        'ma_sach' => $ct['ma_sach'],
                        'so_luong' => $ct['so_luong'],
                        'gia_ban' => $ct['gia_ban'],
                        'gia_khuyen_mai' => $ct['gia_khuyen_mai'],
                        'thanh_tien' => $ct['thanh_tien'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                
                $totalOrders++;
                $totalRevenue += $tongThanhToan;
            } catch (\Exception $e) {
                continue;
            }
        }
        
        $this->command->info("📊 Đã tạo {$totalOrders} đơn hàng");
        $this->command->info("💰 Tổng doanh thu: " . number_format($totalRevenue) . "đ");
    }
}
