<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TheLoai;
use App\Models\TacGia;
use App\Models\NhaXuatBan;
use App\Models\Sach;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample categories
        $theLoai1 = TheLoai::create([
            'ten_the_loai' => 'Văn học',
            'duong_dan' => 'van-hoc',
            'mo_ta' => 'Sách văn học'
        ]);

        $theLoai2 = TheLoai::create([
            'ten_the_loai' => 'Khoa học',
            'duong_dan' => 'khoa-hoc',
            'mo_ta' => 'Sách khoa học'
        ]);

        // Create sample authors
        $tacGia1 = TacGia::create([
            'ten_tac_gia' => 'Nguyễn Du',
            'duong_dan' => 'nguyen-du',
            'tieu_su' => 'Đại thi hào Việt Nam'
        ]);

        $tacGia2 = TacGia::create([
            'ten_tac_gia' => 'Nam Cao',
            'duong_dan' => 'nam-cao',
            'tieu_su' => 'Nhà văn hiện thực'
        ]);

        // Create sample publishers
        $nxb1 = NhaXuatBan::create([
            'ten_nxb' => 'NXB Văn học',
            'duong_dan' => 'nxb-van-hoc',
            'dia_chi' => 'Hà Nội'
        ]);

        $nxb2 = NhaXuatBan::create([
            'ten_nxb' => 'NXB Giáo dục',
            'duong_dan' => 'nxb-giao-duc',
            'dia_chi' => 'TP.HCM'
        ]);

        // Create sample books
        Sach::create([
            'ten_sach' => 'Truyện Kiều',
            'duong_dan' => 'truyen-kieu',
            'mo_ta' => 'Tác phẩm bất hủ của Nguyễn Du',
            'gia_ban' => 150000,
            'so_luong_ton' => 100,
            'ma_the_loai' => $theLoai1->ma_the_loai,
            'ma_tac_gia' => $tacGia1->ma_tac_gia,
            'ma_nxb' => $nxb1->ma_nxb,
            'trang_thai' => true
        ]);

        Sach::create([
            'ten_sach' => 'Chí Phèo',
            'duong_dan' => 'chi-pheo',
            'mo_ta' => 'Truyện ngắn nổi tiếng của Nam Cao',
            'gia_ban' => 80000,
            'so_luong_ton' => 50,
            'ma_the_loai' => $theLoai1->ma_the_loai,
            'ma_tac_gia' => $tacGia2->ma_tac_gia,
            'ma_nxb' => $nxb1->ma_nxb,
            'trang_thai' => true
        ]);

        Sach::create([
            'ten_sach' => 'Vật lý đại cương',
            'duong_dan' => 'vat-ly-dai-cuong',
            'mo_ta' => 'Giáo trình vật lý cơ bản',
            'gia_ban' => 200000,
            'gia_khuyen_mai' => 180000,
            'so_luong_ton' => 30,
            'ma_the_loai' => $theLoai2->ma_the_loai,
            'ma_tac_gia' => $tacGia1->ma_tac_gia,
            'ma_nxb' => $nxb2->ma_nxb,
            'trang_thai' => true
        ]);
    }
}