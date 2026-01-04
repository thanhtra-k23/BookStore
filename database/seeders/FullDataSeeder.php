<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TheLoai;
use App\Models\TacGia;
use App\Models\NhaXuatBan;
use App\Models\Sach;
use App\Models\NguoiDung;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\MaGiamGia;
use App\Models\DanhGia;
use App\Models\YeuThich;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FullDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedTheLoai();
        $this->seedTacGia();
        $this->seedNhaXuatBan();
        $this->seedSach();
        $this->seedNguoiDung();
        $this->seedMaGiamGia();
        $this->seedDonHang();
        $this->seedDanhGia();
        $this->seedYeuThich();
        
        $this->command->info('✅ Đã thêm dữ liệu mẫu đầy đủ!');
    }

    private function seedTheLoai()
    {
        $theLoais = [
            ['ten_the_loai' => 'Văn học Việt Nam', 'mo_ta' => 'Các tác phẩm văn học của các tác giả Việt Nam', 'hinh_anh' => 'categories/van-hoc-vn.jpg'],
            ['ten_the_loai' => 'Văn học nước ngoài', 'mo_ta' => 'Các tác phẩm văn học dịch từ nước ngoài', 'hinh_anh' => 'categories/van-hoc-nn.jpg'],
            ['ten_the_loai' => 'Kinh tế - Kinh doanh', 'mo_ta' => 'Sách về kinh tế, tài chính, khởi nghiệp', 'hinh_anh' => 'categories/kinh-te.jpg'],
            ['ten_the_loai' => 'Kỹ năng sống', 'mo_ta' => 'Sách phát triển bản thân, kỹ năng mềm', 'hinh_anh' => 'categories/ky-nang.jpg'],
            ['ten_the_loai' => 'Khoa học - Công nghệ', 'mo_ta' => 'Sách về khoa học tự nhiên và công nghệ', 'hinh_anh' => 'categories/khoa-hoc.jpg'],
            ['ten_the_loai' => 'Thiếu nhi', 'mo_ta' => 'Sách dành cho trẻ em và thiếu niên', 'hinh_anh' => 'categories/thieu-nhi.jpg'],
            ['ten_the_loai' => 'Tâm lý - Triết học', 'mo_ta' => 'Sách về tâm lý học và triết học', 'hinh_anh' => 'categories/tam-ly.jpg'],
            ['ten_the_loai' => 'Lịch sử - Địa lý', 'mo_ta' => 'Sách về lịch sử và địa lý thế giới', 'hinh_anh' => 'categories/lich-su.jpg'],
            ['ten_the_loai' => 'Giáo trình - Tham khảo', 'mo_ta' => 'Sách giáo khoa và tài liệu tham khảo', 'hinh_anh' => 'categories/giao-trinh.jpg'],
            ['ten_the_loai' => 'Truyện tranh - Manga', 'mo_ta' => 'Truyện tranh, manga, comic', 'hinh_anh' => 'categories/manga.jpg'],
        ];

        foreach ($theLoais as $tl) {
            TheLoai::firstOrCreate(
                ['ten_the_loai' => $tl['ten_the_loai']],
                [
                    'duong_dan' => Str::slug($tl['ten_the_loai']),
                    'mo_ta' => $tl['mo_ta'],
                    'hinh_anh' => $tl['hinh_anh'] ?? null,
                ]
            );
        }
        $this->command->info('📚 Đã thêm 10 thể loại sách');
    }

    private function seedTacGia()
    {
        $tacGias = [
            ['ten' => 'Nguyễn Du', 'tieu_su' => 'Đại thi hào dân tộc Việt Nam, tác giả Truyện Kiều', 'quoc_tich' => 'Việt Nam'],
            ['ten' => 'Nam Cao', 'tieu_su' => 'Nhà văn hiện thực xuất sắc của văn học Việt Nam', 'quoc_tich' => 'Việt Nam'],
            ['ten' => 'Nguyễn Nhật Ánh', 'tieu_su' => 'Nhà văn nổi tiếng với các tác phẩm dành cho tuổi trẻ', 'quoc_tich' => 'Việt Nam'],
            ['ten' => 'Tô Hoài', 'tieu_su' => 'Nhà văn với nhiều tác phẩm về thiên nhiên và con người', 'quoc_tich' => 'Việt Nam'],
            ['ten' => 'Paulo Coelho', 'tieu_su' => 'Nhà văn Brazil nổi tiếng với Nhà giả kim', 'quoc_tich' => 'Brazil'],
            ['ten' => 'Dale Carnegie', 'tieu_su' => 'Tác giả sách kỹ năng sống nổi tiếng thế giới', 'quoc_tich' => 'Mỹ'],
            ['ten' => 'Robert Kiyosaki', 'tieu_su' => 'Tác giả Cha giàu cha nghèo', 'quoc_tich' => 'Mỹ'],
            ['ten' => 'Haruki Murakami', 'tieu_su' => 'Nhà văn Nhật Bản nổi tiếng thế giới', 'quoc_tich' => 'Nhật Bản'],
            ['ten' => 'J.K. Rowling', 'tieu_su' => 'Tác giả series Harry Potter', 'quoc_tich' => 'Anh'],
            ['ten' => 'Yuval Noah Harari', 'tieu_su' => 'Tác giả Sapiens - Lược sử loài người', 'quoc_tich' => 'Israel'],
            ['ten' => 'Nguyễn Ngọc Tư', 'tieu_su' => 'Nhà văn miền Tây với giọng văn đặc trưng', 'quoc_tich' => 'Việt Nam'],
            ['ten' => 'Vũ Trọng Phụng', 'tieu_su' => 'Nhà văn hiện thực phê phán xuất sắc', 'quoc_tich' => 'Việt Nam'],
            ['ten' => 'Stephen Hawking', 'tieu_su' => 'Nhà vật lý lý thuyết nổi tiếng', 'quoc_tich' => 'Anh'],
            ['ten' => 'Eiichiro Oda', 'tieu_su' => 'Tác giả manga One Piece', 'quoc_tich' => 'Nhật Bản'],
            ['ten' => 'Gosho Aoyama', 'tieu_su' => 'Tác giả manga Conan', 'quoc_tich' => 'Nhật Bản'],
        ];

        foreach ($tacGias as $tg) {
            TacGia::firstOrCreate(
                ['ten_tac_gia' => $tg['ten']],
                [
                    'duong_dan' => Str::slug($tg['ten']),
                    'tieu_su' => $tg['tieu_su'],
                    'quoc_tich' => $tg['quoc_tich'] ?? null,
                ]
            );
        }
        $this->command->info('✍️ Đã thêm 15 tác giả');
    }

    private function seedNhaXuatBan()
    {
        $nxbs = [
            ['ten' => 'NXB Kim Đồng', 'dia_chi' => '55 Quang Trung, Hà Nội', 'so_dien_thoai' => '024 3943 4730', 'email' => 'info@nxbkimdong.com.vn'],
            ['ten' => 'NXB Trẻ', 'dia_chi' => '161B Lý Chính Thắng, Q.3, TP.HCM', 'so_dien_thoai' => '028 3930 5859', 'email' => 'hopthubandoc@nxbtre.com.vn'],
            ['ten' => 'NXB Văn học', 'dia_chi' => '18 Nguyễn Trường Tộ, Hà Nội', 'so_dien_thoai' => '024 3716 4855', 'email' => 'nxbvanhoc@gmail.com'],
            ['ten' => 'NXB Giáo dục Việt Nam', 'dia_chi' => '81 Trần Hưng Đạo, Hà Nội', 'so_dien_thoai' => '024 3822 0801', 'email' => 'nxbgd@moet.gov.vn'],
            ['ten' => 'NXB Tổng hợp TP.HCM', 'dia_chi' => '62 Nguyễn Thị Minh Khai, Q.1, TP.HCM', 'so_dien_thoai' => '028 3829 6764', 'email' => 'tonghop@nxbhcm.com.vn'],
            ['ten' => 'NXB Lao động Xã hội', 'dia_chi' => '175 Giảng Võ, Hà Nội', 'so_dien_thoai' => '024 3851 5380', 'email' => 'nxblaodong@gmail.com'],
            ['ten' => 'NXB Thế giới', 'dia_chi' => '46 Trần Hưng Đạo, Hà Nội', 'so_dien_thoai' => '024 3825 3841', 'email' => 'thegioi@thegioipublishers.vn'],
            ['ten' => 'NXB Hội Nhà văn', 'dia_chi' => '65 Nguyễn Du, Hà Nội', 'so_dien_thoai' => '024 3822 2135', 'email' => 'nxbhoinhavan@gmail.com'],
            ['ten' => 'Alpha Books', 'dia_chi' => 'Tầng 3, Tòa nhà VCCI, Hà Nội', 'so_dien_thoai' => '024 3974 2468', 'email' => 'info@alphabooks.vn'],
            ['ten' => 'First News Trí Việt', 'dia_chi' => '11H Nguyễn Thị Minh Khai, Q.1, TP.HCM', 'so_dien_thoai' => '028 3822 7979', 'email' => 'info@firstnews.com.vn'],
        ];

        foreach ($nxbs as $nxb) {
            $slug = Str::slug($nxb['ten']);
            $exists = NhaXuatBan::where('ten_nxb', $nxb['ten'])
                ->orWhere('duong_dan', $slug)
                ->exists();
            
            if (!$exists) {
                NhaXuatBan::create([
                    'ten_nxb' => $nxb['ten'],
                    'duong_dan' => $slug,
                    'dia_chi' => $nxb['dia_chi'],
                    'so_dien_thoai' => $nxb['so_dien_thoai'] ?? null,
                    'email' => $nxb['email'] ?? null,
                ]);
            }
        }
        $this->command->info('🏢 Đã thêm nhà xuất bản');
    }


    private function seedSach()
    {
        $theLoais = TheLoai::all()->keyBy('ten_the_loai');
        $tacGias = TacGia::all()->keyBy('ten_tac_gia');
        $nxbs = NhaXuatBan::all()->keyBy('ten_nxb');

        $sachs = [
            // Văn học Việt Nam
            ['ten' => 'Truyện Kiều', 'the_loai' => 'Văn học Việt Nam', 'tac_gia' => 'Nguyễn Du', 'nxb' => 'NXB Văn học', 'gia' => 125000, 'gia_km' => 99000, 'mo_ta' => 'Kiệt tác văn học của đại thi hào Nguyễn Du, kể về cuộc đời đầy bi kịch của nàng Kiều.', 'luot_xem' => 1520],
            ['ten' => 'Chí Phèo', 'the_loai' => 'Văn học Việt Nam', 'tac_gia' => 'Nam Cao', 'nxb' => 'NXB Văn học', 'gia' => 85000, 'gia_km' => null, 'mo_ta' => 'Truyện ngắn xuất sắc về số phận người nông dân bị tha hóa.', 'luot_xem' => 980],
            ['ten' => 'Mắt biếc', 'the_loai' => 'Văn học Việt Nam', 'tac_gia' => 'Nguyễn Nhật Ánh', 'nxb' => 'NXB Trẻ', 'gia' => 110000, 'gia_km' => 88000, 'mo_ta' => 'Câu chuyện tình yêu trong sáng thời học trò.', 'luot_xem' => 2350],
            ['ten' => 'Tôi thấy hoa vàng trên cỏ xanh', 'the_loai' => 'Văn học Việt Nam', 'tac_gia' => 'Nguyễn Nhật Ánh', 'nxb' => 'NXB Trẻ', 'gia' => 120000, 'gia_km' => 96000, 'mo_ta' => 'Tuổi thơ đẹp đẽ ở miền quê Việt Nam.', 'luot_xem' => 3100],
            ['ten' => 'Dế Mèn phiêu lưu ký', 'the_loai' => 'Thiếu nhi', 'tac_gia' => 'Tô Hoài', 'nxb' => 'NXB Kim Đồng', 'gia' => 75000, 'gia_km' => 60000, 'mo_ta' => 'Cuộc phiêu lưu của chú Dế Mèn.', 'luot_xem' => 1800],
            ['ten' => 'Số đỏ', 'the_loai' => 'Văn học Việt Nam', 'tac_gia' => 'Vũ Trọng Phụng', 'nxb' => 'NXB Văn học', 'gia' => 95000, 'gia_km' => null, 'mo_ta' => 'Tiểu thuyết trào phúng xuất sắc về xã hội Việt Nam thời Pháp thuộc.', 'luot_xem' => 1250],
            ['ten' => 'Cánh đồng bất tận', 'the_loai' => 'Văn học Việt Nam', 'tac_gia' => 'Nguyễn Ngọc Tư', 'nxb' => 'NXB Trẻ', 'gia' => 89000, 'gia_km' => 71000, 'mo_ta' => 'Tập truyện ngắn đặc sắc về miền Tây Nam Bộ.', 'luot_xem' => 890],
            
            // Văn học nước ngoài
            ['ten' => 'Nhà giả kim', 'the_loai' => 'Văn học nước ngoài', 'tac_gia' => 'Paulo Coelho', 'nxb' => 'NXB Hội Nhà văn', 'gia' => 79000, 'gia_km' => 63000, 'mo_ta' => 'Hành trình theo đuổi giấc mơ của chàng chăn cừu Santiago.', 'luot_xem' => 4500],
            ['ten' => 'Rừng Na Uy', 'the_loai' => 'Văn học nước ngoài', 'tac_gia' => 'Haruki Murakami', 'nxb' => 'NXB Hội Nhà văn', 'gia' => 135000, 'gia_km' => 108000, 'mo_ta' => 'Tiểu thuyết về tình yêu và mất mát của tuổi trẻ.', 'luot_xem' => 2800],
            ['ten' => 'Harry Potter và Hòn đá Phù thủy', 'the_loai' => 'Văn học nước ngoài', 'tac_gia' => 'J.K. Rowling', 'nxb' => 'NXB Trẻ', 'gia' => 150000, 'gia_km' => 120000, 'mo_ta' => 'Tập đầu tiên của series Harry Potter huyền thoại.', 'luot_xem' => 5200],
            ['ten' => 'Harry Potter và Phòng chứa Bí mật', 'the_loai' => 'Văn học nước ngoài', 'tac_gia' => 'J.K. Rowling', 'nxb' => 'NXB Trẻ', 'gia' => 155000, 'gia_km' => 124000, 'mo_ta' => 'Tập 2 series Harry Potter.', 'luot_xem' => 4800],
            
            // Kinh tế - Kinh doanh
            ['ten' => 'Cha giàu cha nghèo', 'the_loai' => 'Kinh tế - Kinh doanh', 'tac_gia' => 'Robert Kiyosaki', 'nxb' => 'NXB Trẻ', 'gia' => 110000, 'gia_km' => 88000, 'mo_ta' => 'Bài học về tài chính cá nhân và đầu tư.', 'luot_xem' => 3800],
            ['ten' => 'Dạy con làm giàu - Tập 1', 'the_loai' => 'Kinh tế - Kinh doanh', 'tac_gia' => 'Robert Kiyosaki', 'nxb' => 'NXB Trẻ', 'gia' => 95000, 'gia_km' => null, 'mo_ta' => 'Những bài học tài chính cho trẻ em.', 'luot_xem' => 1200],
            ['ten' => 'Khởi nghiệp tinh gọn', 'the_loai' => 'Kinh tế - Kinh doanh', 'tac_gia' => 'Dale Carnegie', 'nxb' => 'Alpha Books', 'gia' => 189000, 'gia_km' => 151000, 'mo_ta' => 'Phương pháp khởi nghiệp hiệu quả.', 'luot_xem' => 2100],
            
            // Kỹ năng sống
            ['ten' => 'Đắc nhân tâm', 'the_loai' => 'Kỹ năng sống', 'tac_gia' => 'Dale Carnegie', 'nxb' => 'NXB Tổng hợp TP.HCM', 'gia' => 86000, 'gia_km' => 69000, 'mo_ta' => 'Nghệ thuật thu phục lòng người.', 'luot_xem' => 6500],
            ['ten' => 'Quẳng gánh lo đi và vui sống', 'the_loai' => 'Kỹ năng sống', 'tac_gia' => 'Dale Carnegie', 'nxb' => 'NXB Tổng hợp TP.HCM', 'gia' => 95000, 'gia_km' => 76000, 'mo_ta' => 'Cách sống vui vẻ và tích cực.', 'luot_xem' => 3200],
            ['ten' => 'Nghĩ giàu làm giàu', 'the_loai' => 'Kỹ năng sống', 'tac_gia' => 'Dale Carnegie', 'nxb' => 'NXB Lao động Xã hội', 'gia' => 108000, 'gia_km' => null, 'mo_ta' => 'Tư duy làm giàu từ những người thành công.', 'luot_xem' => 2900],
            
            // Khoa học - Công nghệ
            ['ten' => 'Sapiens - Lược sử loài người', 'the_loai' => 'Khoa học - Công nghệ', 'tac_gia' => 'Yuval Noah Harari', 'nxb' => 'NXB Thế giới', 'gia' => 209000, 'gia_km' => 167000, 'mo_ta' => 'Lịch sử phát triển của loài người từ thời tiền sử.', 'luot_xem' => 4100],
            ['ten' => 'Homo Deus - Lược sử tương lai', 'the_loai' => 'Khoa học - Công nghệ', 'tac_gia' => 'Yuval Noah Harari', 'nxb' => 'NXB Thế giới', 'gia' => 225000, 'gia_km' => 180000, 'mo_ta' => 'Tương lai của loài người trong kỷ nguyên công nghệ.', 'luot_xem' => 3500],
            ['ten' => 'Lược sử thời gian', 'the_loai' => 'Khoa học - Công nghệ', 'tac_gia' => 'Stephen Hawking', 'nxb' => 'NXB Trẻ', 'gia' => 145000, 'gia_km' => 116000, 'mo_ta' => 'Giải thích vũ trụ cho người không chuyên.', 'luot_xem' => 2700],
            
            // Tâm lý - Triết học
            ['ten' => 'Tâm lý học đám đông', 'the_loai' => 'Tâm lý - Triết học', 'tac_gia' => 'Dale Carnegie', 'nxb' => 'NXB Thế giới', 'gia' => 89000, 'gia_km' => null, 'mo_ta' => 'Nghiên cứu về hành vi tập thể.', 'luot_xem' => 1800],
            ['ten' => 'Đời ngắn đừng ngủ dài', 'the_loai' => 'Tâm lý - Triết học', 'tac_gia' => 'Dale Carnegie', 'nxb' => 'NXB Lao động Xã hội', 'gia' => 75000, 'gia_km' => 60000, 'mo_ta' => 'Cách sống trọn vẹn từng ngày.', 'luot_xem' => 2400],
            
            // Truyện tranh - Manga
            ['ten' => 'One Piece - Tập 1', 'the_loai' => 'Truyện tranh - Manga', 'tac_gia' => 'Eiichiro Oda', 'nxb' => 'NXB Kim Đồng', 'gia' => 25000, 'gia_km' => 20000, 'mo_ta' => 'Hành trình trở thành Vua Hải Tặc của Luffy.', 'luot_xem' => 8500],
            ['ten' => 'One Piece - Tập 2', 'the_loai' => 'Truyện tranh - Manga', 'tac_gia' => 'Eiichiro Oda', 'nxb' => 'NXB Kim Đồng', 'gia' => 25000, 'gia_km' => 20000, 'mo_ta' => 'Tiếp tục hành trình của băng Mũ Rơm.', 'luot_xem' => 7800],
            ['ten' => 'Conan - Tập 1', 'the_loai' => 'Truyện tranh - Manga', 'tac_gia' => 'Gosho Aoyama', 'nxb' => 'NXB Kim Đồng', 'gia' => 25000, 'gia_km' => null, 'mo_ta' => 'Thám tử lừng danh Conan.', 'luot_xem' => 7200],
            ['ten' => 'Conan - Tập 2', 'the_loai' => 'Truyện tranh - Manga', 'tac_gia' => 'Gosho Aoyama', 'nxb' => 'NXB Kim Đồng', 'gia' => 25000, 'gia_km' => null, 'mo_ta' => 'Những vụ án ly kỳ của Conan.', 'luot_xem' => 6900],
            
            // Thiếu nhi
            ['ten' => 'Cho tôi xin một vé đi tuổi thơ', 'the_loai' => 'Thiếu nhi', 'tac_gia' => 'Nguyễn Nhật Ánh', 'nxb' => 'NXB Trẻ', 'gia' => 85000, 'gia_km' => 68000, 'mo_ta' => 'Ký ức tuổi thơ đẹp đẽ.', 'luot_xem' => 2600],
            ['ten' => 'Kính vạn hoa', 'the_loai' => 'Thiếu nhi', 'tac_gia' => 'Nguyễn Nhật Ánh', 'nxb' => 'NXB Trẻ', 'gia' => 95000, 'gia_km' => null, 'mo_ta' => 'Những câu chuyện vui nhộn của tuổi học trò.', 'luot_xem' => 1900],
            
            // Lịch sử - Địa lý
            ['ten' => 'Việt Nam sử lược', 'the_loai' => 'Lịch sử - Địa lý', 'tac_gia' => 'Nguyễn Du', 'nxb' => 'NXB Giáo dục Việt Nam', 'gia' => 185000, 'gia_km' => 148000, 'mo_ta' => 'Lịch sử Việt Nam từ thời dựng nước.', 'luot_xem' => 1500],
            ['ten' => 'Đại Việt sử ký toàn thư', 'the_loai' => 'Lịch sử - Địa lý', 'tac_gia' => 'Nguyễn Du', 'nxb' => 'NXB Văn học', 'gia' => 350000, 'gia_km' => 280000, 'mo_ta' => 'Bộ sử lớn nhất của Việt Nam thời phong kiến.', 'luot_xem' => 980],
            
            // Giáo trình
            ['ten' => 'Giáo trình Toán cao cấp', 'the_loai' => 'Giáo trình - Tham khảo', 'tac_gia' => 'Stephen Hawking', 'nxb' => 'NXB Giáo dục Việt Nam', 'gia' => 125000, 'gia_km' => null, 'mo_ta' => 'Giáo trình toán dành cho sinh viên đại học.', 'luot_xem' => 650],
            ['ten' => 'Giáo trình Vật lý đại cương', 'the_loai' => 'Giáo trình - Tham khảo', 'tac_gia' => 'Stephen Hawking', 'nxb' => 'NXB Giáo dục Việt Nam', 'gia' => 145000, 'gia_km' => 116000, 'mo_ta' => 'Giáo trình vật lý cơ bản.', 'luot_xem' => 720],
        ];

        foreach ($sachs as $s) {
            $theLoai = $theLoais[$s['the_loai']] ?? null;
            $tacGia = $tacGias[$s['tac_gia']] ?? null;
            $nxb = $nxbs[$s['nxb']] ?? null;

            if ($theLoai && $tacGia) {
                $slug = Str::slug($s['ten']);
                $exists = Sach::where('ten_sach', $s['ten'])
                    ->orWhere('duong_dan', $slug)
                    ->exists();
                
                if (!$exists) {
                    Sach::create([
                        'ten_sach' => $s['ten'],
                        'duong_dan' => $slug,
                        'mo_ta' => $s['mo_ta'],
                        'gia_ban' => $s['gia'],
                        'gia_khuyen_mai' => $s['gia_km'],
                        'so_luong_ton' => rand(10, 200),
                        'ma_the_loai' => $theLoai->ma_the_loai,
                        'ma_tac_gia' => $tacGia->ma_tac_gia,
                        'ma_nxb' => $nxb?->ma_nxb,
                        'trang_thai' => 'active',
                        'luot_xem' => $s['luot_xem'] ?? rand(100, 1000),
                        'nam_xuat_ban' => rand(2018, 2024),
                    ]);
                }
            }
        }
        $this->command->info('📖 Đã thêm sách mới');
    }


    private function seedNguoiDung()
    {
        $nguoiDungs = [
            ['ho_ten' => 'Nguyễn Văn An', 'email' => 'nguyenvanan@gmail.com', 'sdt' => '0901234567', 'dia_chi' => '123 Nguyễn Huệ, Q.1, TP.HCM'],
            ['ho_ten' => 'Trần Thị Bình', 'email' => 'tranthibinh@gmail.com', 'sdt' => '0912345678', 'dia_chi' => '456 Lê Lợi, Q.1, TP.HCM'],
            ['ho_ten' => 'Lê Văn Cường', 'email' => 'levancuong@gmail.com', 'sdt' => '0923456789', 'dia_chi' => '789 Trần Hưng Đạo, Q.5, TP.HCM'],
            ['ho_ten' => 'Phạm Thị Dung', 'email' => 'phamthidung@gmail.com', 'sdt' => '0934567890', 'dia_chi' => '321 Hai Bà Trưng, Q.3, TP.HCM'],
            ['ho_ten' => 'Hoàng Văn Em', 'email' => 'hoangvanem@gmail.com', 'sdt' => '0945678901', 'dia_chi' => '654 Võ Văn Tần, Q.3, TP.HCM'],
            ['ho_ten' => 'Ngô Thị Phương', 'email' => 'ngothiphuong@gmail.com', 'sdt' => '0956789012', 'dia_chi' => '987 Điện Biên Phủ, Q.Bình Thạnh, TP.HCM'],
            ['ho_ten' => 'Đặng Văn Giang', 'email' => 'dangvangiang@gmail.com', 'sdt' => '0967890123', 'dia_chi' => '147 Cách Mạng Tháng 8, Q.10, TP.HCM'],
            ['ho_ten' => 'Vũ Thị Hoa', 'email' => 'vuthihoa@gmail.com', 'sdt' => '0978901234', 'dia_chi' => '258 Nguyễn Đình Chiểu, Q.3, TP.HCM'],
            ['ho_ten' => 'Bùi Văn Khoa', 'email' => 'buivankhoa@gmail.com', 'sdt' => '0989012345', 'dia_chi' => '369 Lý Thường Kiệt, Q.10, TP.HCM'],
            ['ho_ten' => 'Đỗ Thị Lan', 'email' => 'dothilan@gmail.com', 'sdt' => '0990123456', 'dia_chi' => '741 Nguyễn Trãi, Q.5, TP.HCM'],
        ];

        foreach ($nguoiDungs as $nd) {
            // Sử dụng User model thay vì NguoiDung
            \App\Models\User::firstOrCreate(
                ['email' => $nd['email']],
                [
                    'ho_ten' => $nd['ho_ten'],
                    'mat_khau' => Hash::make('password123'),
                    'so_dien_thoai' => $nd['sdt'],
                    'dia_chi' => $nd['dia_chi'],
                    'vai_tro' => 'customer',
                    'xac_minh_email_luc' => now(),
                ]
            );
        }
        $this->command->info('👥 Đã thêm 10 người dùng');
    }

    private function seedMaGiamGia()
    {
        $maGiamGias = [
            ['ma' => 'WELCOME10', 'ten' => 'Chào mừng khách mới', 'loai' => 'phan_tram', 'gia_tri' => 10, 'mo_ta' => 'Giảm 10% cho khách hàng mới', 'toi_thieu' => 100000, 'toi_da' => 50000, 'so_luong' => 100],
            ['ma' => 'SALE20', 'ten' => 'Giảm 20%', 'loai' => 'phan_tram', 'gia_tri' => 20, 'mo_ta' => 'Giảm 20% toàn bộ đơn hàng', 'toi_thieu' => 200000, 'toi_da' => 100000, 'so_luong' => 50],
            ['ma' => 'FREESHIP', 'ten' => 'Miễn phí ship', 'loai' => 'so_tien', 'gia_tri' => 30000, 'mo_ta' => 'Miễn phí vận chuyển', 'toi_thieu' => 150000, 'toi_da' => null, 'so_luong' => 200],
            ['ma' => 'BOOK50K', 'ten' => 'Giảm 50K', 'loai' => 'so_tien', 'gia_tri' => 50000, 'mo_ta' => 'Giảm 50.000đ cho đơn từ 300K', 'toi_thieu' => 300000, 'toi_da' => null, 'so_luong' => 30],
            ['ma' => 'VIP15', 'ten' => 'Ưu đãi VIP', 'loai' => 'phan_tram', 'gia_tri' => 15, 'mo_ta' => 'Ưu đãi VIP giảm 15%', 'toi_thieu' => 500000, 'toi_da' => 200000, 'so_luong' => 20],
            ['ma' => 'NEWYEAR25', 'ten' => 'Mừng năm mới', 'loai' => 'phan_tram', 'gia_tri' => 25, 'mo_ta' => 'Mừng năm mới giảm 25%', 'toi_thieu' => 250000, 'toi_da' => 150000, 'so_luong' => 100],
            ['ma' => 'SUMMER30', 'ten' => 'Khuyến mãi hè', 'loai' => 'phan_tram', 'gia_tri' => 30, 'mo_ta' => 'Khuyến mãi hè giảm 30%', 'toi_thieu' => 400000, 'toi_da' => 200000, 'so_luong' => 50],
            ['ma' => 'FLASH100K', 'ten' => 'Flash Sale', 'loai' => 'so_tien', 'gia_tri' => 100000, 'mo_ta' => 'Flash sale giảm 100K', 'toi_thieu' => 500000, 'toi_da' => null, 'so_luong' => 10],
        ];

        foreach ($maGiamGias as $mg) {
            MaGiamGia::firstOrCreate(
                ['ma_code' => $mg['ma']],
                [
                    'ten_ma_giam_gia' => $mg['ten'],
                    'mo_ta' => $mg['mo_ta'],
                    'loai_giam_gia' => $mg['loai'],
                    'gia_tri_giam' => $mg['gia_tri'],
                    'gia_tri_don_hang_toi_thieu' => $mg['toi_thieu'],
                    'gia_tri_giam_toi_da' => $mg['toi_da'],
                    'so_luong' => $mg['so_luong'],
                    'da_su_dung' => rand(0, 20),
                    'ngay_bat_dau' => now()->subDays(rand(1, 30)),
                    'ngay_ket_thuc' => now()->addDays(rand(30, 90)),
                    'trang_thai' => true,
                ]
            );
        }
        $this->command->info('🎫 Đã thêm 8 mã giảm giá');
    }

    private function seedDonHang()
    {
        // Lấy users từ bảng users (không phải nguoi_dung)
        $users = \App\Models\User::where('vai_tro', 'customer')->get();
        $sachs = Sach::all();
        
        if ($users->isEmpty() || $sachs->isEmpty()) {
            $this->command->warn('⚠️ Không có users hoặc sách để tạo đơn hàng');
            return;
        }

        $trangThais = ['cho_xac_nhan', 'da_xac_nhan', 'dang_chuan_bi', 'dang_giao', 'da_giao', 'da_huy'];
        $phuongThucThanhToan = ['cod', 'bank_transfer', 'momo', 'vnpay'];
        
        // Tạo 20 đơn hàng mẫu
        for ($i = 1; $i <= 20; $i++) {
            $user = $users->random();
            $trangThai = $trangThais[array_rand($trangThais)];
            $soSanPham = rand(1, 4);
            $selectedSachs = $sachs->random($soSanPham);
            
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
            
            $tienGiamGia = rand(0, 1) ? rand(10000, 50000) : 0;
            $phiVanChuyen = rand(0, 1) ? 30000 : 0;
            $tongThanhToan = $tongTien - $tienGiamGia + $phiVanChuyen;
            
            $maDonHangUnique = 'DH' . date('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT) . rand(100, 999);
            
            try {
                $donHang = \DB::table('don_hang')->insertGetId([
                    'ma_don_hang_unique' => $maDonHangUnique,
                    'ma_nguoi_dung' => $user->id,
                    'tong_tien' => $tongTien,
                    'tien_giam_gia' => $tienGiamGia,
                    'phi_van_chuyen' => $phiVanChuyen,
                    'tong_thanh_toan' => $tongThanhToan,
                    'ten_nguoi_nhan' => $user->ho_ten ?? $user->name ?? 'Khách hàng',
                    'so_dien_thoai_nguoi_nhan' => $user->so_dien_thoai ?? '0901234567',
                    'dia_chi_giao_hang' => $user->dia_chi ?? '123 Đường ABC, Quận 1, TP.HCM',
                    'ghi_chu' => rand(0, 1) ? 'Giao giờ hành chính' : null,
                    'phuong_thuc_thanh_toan' => $phuongThucThanhToan[array_rand($phuongThucThanhToan)],
                    'trang_thai' => $trangThai,
                    'trang_thai_thanh_toan' => $trangThai === 'da_giao' ? 'da_thanh_toan' : 'chua_thanh_toan',
                    'ngay_dat_hang' => now()->subDays(rand(1, 60)),
                    'ngay_giao_hang' => $trangThai === 'da_giao' ? now()->subDays(rand(1, 30)) : null,
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now(),
                ]);
                
                foreach ($chiTiets as $ct) {
                    \DB::table('chi_tiet_don_hang')->insert([
                        'ma_don_hang' => $donHang,
                        'ma_sach' => $ct['ma_sach'],
                        'so_luong' => $ct['so_luong'],
                        'gia_ban' => $ct['gia_ban'],
                        'gia_khuyen_mai' => $ct['gia_khuyen_mai'],
                        'thanh_tien' => $ct['thanh_tien'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } catch (\Exception $e) {
                $this->command->warn("⚠️ Lỗi tạo đơn hàng: " . $e->getMessage());
                continue;
            }
        }
        $this->command->info('📦 Đã thêm 20 đơn hàng với chi tiết');
    }

    private function seedDanhGia()
    {
        // Lấy users từ bảng users
        $users = \App\Models\User::where('vai_tro', 'customer')->get();
        $sachs = Sach::all();
        
        if ($users->isEmpty() || $sachs->isEmpty()) {
            $this->command->warn('⚠️ Không có users hoặc sách để tạo đánh giá');
            return;
        }

        $noiDungs = [
            'Sách rất hay, nội dung hấp dẫn. Đóng gói cẩn thận, giao hàng nhanh.',
            'Chất lượng sách tốt, giấy đẹp. Rất hài lòng với sản phẩm.',
            'Nội dung sách bổ ích, đáng đọc. Sẽ ủng hộ shop tiếp.',
            'Sách hay, giá cả hợp lý. Recommend cho mọi người.',
            'Giao hàng hơi chậm nhưng sách đẹp, nội dung tốt.',
            'Tuyệt vời! Đây là cuốn sách tôi tìm kiếm bấy lâu.',
            'Sách được bọc cẩn thận, không bị móp méo. Nội dung hay.',
            'Đọc xong rất thích, sẽ mua thêm các cuốn khác của tác giả.',
        ];

        $trangThais = ['cho_duyet', 'da_duyet', 'tu_choi'];

        // Tạo đánh giá mẫu
        $count = 0;
        foreach ($sachs->take(15) as $sach) {
            $soLuongDanhGia = rand(1, 3);
            $selectedUsers = $users->random(min($soLuongDanhGia, $users->count()));
            
            foreach ($selectedUsers as $user) {
                $exists = \DB::table('danh_gia')
                    ->where('ma_sach', $sach->ma_sach)
                    ->where('ma_nguoi_dung', $user->id)
                    ->exists();
                    
                if (!$exists) {
                    try {
                        \DB::table('danh_gia')->insert([
                            'ma_sach' => $sach->ma_sach,
                            'ma_nguoi_dung' => $user->id,
                            'diem_danh_gia' => rand(3, 5),
                            'noi_dung_danh_gia' => $noiDungs[array_rand($noiDungs)],
                            'trang_thai' => 'da_duyet', // Phần lớn đã duyệt
                            'created_at' => now()->subDays(rand(1, 30)),
                            'updated_at' => now(),
                        ]);
                        $count++;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
        $this->command->info("⭐ Đã thêm {$count} đánh giá sách");
    }

    private function seedYeuThich()
    {
        // Lấy users từ bảng users
        $users = \App\Models\User::where('vai_tro', 'customer')->get();
        $sachs = Sach::all();
        
        if ($users->isEmpty() || $sachs->isEmpty()) {
            $this->command->warn('⚠️ Không có users hoặc sách để tạo yêu thích');
            return;
        }

        $count = 0;
        foreach ($users as $user) {
            $soLuongYeuThich = rand(2, 6);
            $selectedSachs = $sachs->random(min($soLuongYeuThich, $sachs->count()));
            
            foreach ($selectedSachs as $sach) {
                $exists = \DB::table('yeu_thich')
                    ->where('ma_sach', $sach->ma_sach)
                    ->where('ma_nguoi_dung', $user->id)
                    ->exists();
                    
                if (!$exists) {
                    try {
                        \DB::table('yeu_thich')->insert([
                            'ma_sach' => $sach->ma_sach,
                            'ma_nguoi_dung' => $user->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $count++;
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }
        }
        $this->command->info("❤️ Đã thêm {$count} sách yêu thích");
    }
}
