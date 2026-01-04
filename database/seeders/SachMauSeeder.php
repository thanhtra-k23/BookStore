<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sach;
use App\Models\TacGia;
use App\Models\TheLoai;
use App\Models\NhaXuatBan;
use Illuminate\Support\Str;

class SachMauSeeder extends Seeder
{
    /**
     * Thêm sách mẫu vào database
     */
    public function run(): void
    {
        // Lấy hoặc tạo thể loại
        $theLoai = $this->getOrCreateTheLoai();
        
        // Lấy hoặc tạo tác giả
        $tacGia = $this->getOrCreateTacGia();
        
        // Lấy hoặc tạo NXB
        $nxb = $this->getOrCreateNXB();

        // Danh sách sách mẫu với hình ảnh
        $sachList = [
            // Văn học Việt Nam
            [
                'ten_sach' => 'Dế Mèn Phiêu Lưu Ký',
                'tac_gia' => 'Tô Hoài',
                'the_loai' => 'Văn học',
                'nxb' => 'NXB Kim Đồng',
                'gia_ban' => 65000,
                'mo_ta' => 'Tác phẩm văn học thiếu nhi nổi tiếng của nhà văn Tô Hoài, kể về cuộc phiêu lưu của chú Dế Mèn.',
                'nam_xuat_ban' => 2020,
                'so_trang' => 200,
                'hinh_anh' => 'https://picsum.photos/seed/demen/300/400',
            ],
            [
                'ten_sach' => 'Số Đỏ',
                'tac_gia' => 'Vũ Trọng Phụng',
                'the_loai' => 'Văn học',
                'nxb' => 'NXB Văn Học',
                'gia_ban' => 85000,
                'mo_ta' => 'Tiểu thuyết trào phúng xuất sắc của văn học Việt Nam, phê phán xã hội thực dân nửa phong kiến.',
                'nam_xuat_ban' => 2019,
                'so_trang' => 320,
                'hinh_anh' => 'https://picsum.photos/seed/sodo/300/400',
            ],
            [
                'ten_sach' => 'Tắt Đèn',
                'tac_gia' => 'Ngô Tất Tố',
                'the_loai' => 'Văn học',
                'nxb' => 'NXB Văn Học',
                'gia_ban' => 75000,
                'mo_ta' => 'Tiểu thuyết hiện thực phê phán về cuộc sống của người nông dân Việt Nam trước Cách mạng.',
                'nam_xuat_ban' => 2021,
                'so_trang' => 280,
                'hinh_anh' => 'https://picsum.photos/seed/tatden/300/400',
            ],
            [
                'ten_sach' => 'Chí Phèo',
                'tac_gia' => 'Nam Cao',
                'the_loai' => 'Văn học',
                'nxb' => 'NXB Văn Học',
                'gia_ban' => 55000,
                'mo_ta' => 'Tập truyện ngắn kinh điển của Nam Cao về số phận bi kịch của người nông dân.',
                'nam_xuat_ban' => 2020,
                'so_trang' => 180,
                'hinh_anh' => 'https://picsum.photos/seed/chipheo/300/400',
            ],
            
            // Sách Kinh tế - Kinh doanh
            [
                'ten_sach' => 'Đắc Nhân Tâm',
                'tac_gia' => 'Dale Carnegie',
                'the_loai' => 'Kinh tế',
                'nxb' => 'NXB Tổng Hợp',
                'gia_ban' => 88000,
                'mo_ta' => 'Cuốn sách kinh điển về nghệ thuật giao tiếp và ứng xử, giúp bạn thành công trong cuộc sống.',
                'nam_xuat_ban' => 2022,
                'so_trang' => 320,
                'hinh_anh' => 'https://picsum.photos/seed/dacnhantam/300/400',
            ],
            [
                'ten_sach' => 'Nghĩ Giàu Làm Giàu',
                'tac_gia' => 'Napoleon Hill',
                'the_loai' => 'Kinh tế',
                'nxb' => 'NXB Tổng Hợp',
                'gia_ban' => 110000,
                'mo_ta' => 'Cuốn sách về triết lý thành công và làm giàu, được viết dựa trên nghiên cứu 500 người giàu nhất nước Mỹ.',
                'nam_xuat_ban' => 2021,
                'so_trang' => 400,
                'hinh_anh' => 'https://picsum.photos/seed/nghigiau/300/400',
            ],
            [
                'ten_sach' => 'Cha Giàu Cha Nghèo',
                'tac_gia' => 'Robert Kiyosaki',
                'the_loai' => 'Kinh tế',
                'nxb' => 'NXB Trẻ',
                'gia_ban' => 125000,
                'mo_ta' => 'Cuốn sách về tài chính cá nhân bán chạy nhất mọi thời đại, dạy cách tư duy về tiền bạc.',
                'nam_xuat_ban' => 2022,
                'so_trang' => 350,
                'hinh_anh' => 'https://picsum.photos/seed/chagiau/300/400',
            ],
            [
                'ten_sach' => 'Người Giàu Nhất Thành Babylon',
                'tac_gia' => 'George S. Clason',
                'the_loai' => 'Kinh tế',
                'nxb' => 'NXB Lao Động',
                'gia_ban' => 78000,
                'mo_ta' => 'Những bài học về quản lý tài chính cá nhân thông qua các câu chuyện từ thành Babylon cổ đại.',
                'nam_xuat_ban' => 2020,
                'so_trang' => 200,
                'hinh_anh' => 'https://picsum.photos/seed/babylon/300/400',
            ],
            
            // Sách Kỹ năng sống
            [
                'ten_sach' => 'Đời Ngắn Đừng Ngủ Dài',
                'tac_gia' => 'Robin Sharma',
                'the_loai' => 'Kỹ năng sống',
                'nxb' => 'NXB Trẻ',
                'gia_ban' => 95000,
                'mo_ta' => 'Cuốn sách truyền cảm hứng về cách sống một cuộc đời ý nghĩa và thành công.',
                'nam_xuat_ban' => 2021,
                'so_trang' => 280,
                'hinh_anh' => 'https://picsum.photos/seed/doingan/300/400',
            ],
            [
                'ten_sach' => 'Nhà Giả Kim',
                'tac_gia' => 'Paulo Coelho',
                'the_loai' => 'Văn học',
                'nxb' => 'NXB Văn Học',
                'gia_ban' => 69000,
                'mo_ta' => 'Tiểu thuyết về hành trình theo đuổi giấc mơ của chàng chăn cừu Santiago.',
                'nam_xuat_ban' => 2022,
                'so_trang' => 228,
                'hinh_anh' => 'https://picsum.photos/seed/nhagiakim/300/400',
            ],
            [
                'ten_sach' => 'Tuổi Trẻ Đáng Giá Bao Nhiêu',
                'tac_gia' => 'Rosie Nguyễn',
                'the_loai' => 'Kỹ năng sống',
                'nxb' => 'NXB Hội Nhà Văn',
                'gia_ban' => 80000,
                'mo_ta' => 'Cuốn sách dành cho người trẻ về cách sống, học tập và phát triển bản thân.',
                'nam_xuat_ban' => 2020,
                'so_trang' => 285,
                'hinh_anh' => 'https://picsum.photos/seed/tuoitre/300/400',
            ],
            
            // Sách Khoa học
            [
                'ten_sach' => 'Sapiens: Lược Sử Loài Người',
                'tac_gia' => 'Yuval Noah Harari',
                'the_loai' => 'Khoa học',
                'nxb' => 'NXB Tri Thức',
                'gia_ban' => 209000,
                'mo_ta' => 'Cuốn sách về lịch sử loài người từ thời tiền sử đến hiện đại.',
                'nam_xuat_ban' => 2021,
                'so_trang' => 550,
                'hinh_anh' => 'https://picsum.photos/seed/sapiens/300/400',
            ],
            [
                'ten_sach' => 'Homo Deus: Lược Sử Tương Lai',
                'tac_gia' => 'Yuval Noah Harari',
                'the_loai' => 'Khoa học',
                'nxb' => 'NXB Tri Thức',
                'gia_ban' => 225000,
                'mo_ta' => 'Cuốn sách về tương lai của loài người trong kỷ nguyên công nghệ.',
                'nam_xuat_ban' => 2022,
                'so_trang' => 520,
                'hinh_anh' => 'https://picsum.photos/seed/homodeus/300/400',
            ],
            [
                'ten_sach' => 'Lược Sử Thời Gian',
                'tac_gia' => 'Stephen Hawking',
                'the_loai' => 'Khoa học',
                'nxb' => 'NXB Trẻ',
                'gia_ban' => 135000,
                'mo_ta' => 'Cuốn sách phổ biến khoa học về vũ trụ, thời gian và không gian.',
                'nam_xuat_ban' => 2020,
                'so_trang' => 256,
                'hinh_anh' => 'https://picsum.photos/seed/thoigian/300/400',
            ],
            
            // Sách Thiếu nhi
            [
                'ten_sach' => 'Hoàng Tử Bé',
                'tac_gia' => 'Antoine de Saint-Exupéry',
                'the_loai' => 'Thiếu nhi',
                'nxb' => 'NXB Kim Đồng',
                'gia_ban' => 55000,
                'mo_ta' => 'Câu chuyện cổ tích dành cho người lớn về tình bạn và ý nghĩa cuộc sống.',
                'nam_xuat_ban' => 2021,
                'so_trang' => 120,
                'hinh_anh' => 'https://picsum.photos/seed/hoangtube/300/400',
            ],
            [
                'ten_sach' => 'Doraemon - Tập 1',
                'tac_gia' => 'Fujiko F. Fujio',
                'the_loai' => 'Thiếu nhi',
                'nxb' => 'NXB Kim Đồng',
                'gia_ban' => 20000,
                'mo_ta' => 'Truyện tranh về chú mèo máy Doraemon đến từ tương lai.',
                'nam_xuat_ban' => 2022,
                'so_trang' => 192,
                'hinh_anh' => 'https://picsum.photos/seed/doraemon/300/400',
            ],
            [
                'ten_sach' => 'Conan - Tập 1',
                'tac_gia' => 'Gosho Aoyama',
                'the_loai' => 'Thiếu nhi',
                'nxb' => 'NXB Kim Đồng',
                'gia_ban' => 25000,
                'mo_ta' => 'Truyện tranh trinh thám về thám tử nhí Conan.',
                'nam_xuat_ban' => 2022,
                'so_trang' => 200,
                'hinh_anh' => 'https://picsum.photos/seed/conan/300/400',
            ],
            
            // Sách Tâm lý
            [
                'ten_sach' => 'Tâm Lý Học Đám Đông',
                'tac_gia' => 'Gustave Le Bon',
                'the_loai' => 'Tâm lý',
                'nxb' => 'NXB Thế Giới',
                'gia_ban' => 89000,
                'mo_ta' => 'Nghiên cứu kinh điển về tâm lý và hành vi của đám đông.',
                'nam_xuat_ban' => 2020,
                'so_trang' => 220,
                'hinh_anh' => 'https://picsum.photos/seed/tamlyhoc/300/400',
            ],
            [
                'ten_sach' => 'Tư Duy Nhanh Và Chậm',
                'tac_gia' => 'Daniel Kahneman',
                'the_loai' => 'Tâm lý',
                'nxb' => 'NXB Thế Giới',
                'gia_ban' => 189000,
                'mo_ta' => 'Cuốn sách về hai hệ thống tư duy của con người và cách chúng ảnh hưởng đến quyết định.',
                'nam_xuat_ban' => 2021,
                'so_trang' => 500,
                'hinh_anh' => 'https://picsum.photos/seed/tuduy/300/400',
            ],
            
            // Sách Lịch sử
            [
                'ten_sach' => 'Việt Nam Sử Lược',
                'tac_gia' => 'Trần Trọng Kim',
                'the_loai' => 'Lịch sử',
                'nxb' => 'NXB Văn Học',
                'gia_ban' => 150000,
                'mo_ta' => 'Bộ sách lịch sử Việt Nam từ thời dựng nước đến thời Pháp thuộc.',
                'nam_xuat_ban' => 2019,
                'so_trang' => 600,
                'hinh_anh' => 'https://picsum.photos/seed/vnsuluoc/300/400',
            ],
            [
                'ten_sach' => 'Đại Việt Sử Ký Toàn Thư',
                'tac_gia' => 'Ngô Sĩ Liên',
                'the_loai' => 'Lịch sử',
                'nxb' => 'NXB Khoa Học Xã Hội',
                'gia_ban' => 350000,
                'mo_ta' => 'Bộ quốc sử chính thống của Việt Nam thời phong kiến.',
                'nam_xuat_ban' => 2020,
                'so_trang' => 1200,
                'hinh_anh' => 'https://picsum.photos/seed/daiviet/300/400',
            ],
            
            // Sách Công nghệ
            [
                'ten_sach' => 'Clean Code',
                'tac_gia' => 'Robert C. Martin',
                'the_loai' => 'Công nghệ',
                'nxb' => 'NXB Bách Khoa',
                'gia_ban' => 320000,
                'mo_ta' => 'Hướng dẫn viết code sạch và dễ bảo trì cho lập trình viên.',
                'nam_xuat_ban' => 2021,
                'so_trang' => 464,
                'hinh_anh' => 'https://picsum.photos/seed/cleancode/300/400',
            ],
            [
                'ten_sach' => 'Lập Trình Python Cơ Bản',
                'tac_gia' => 'Nguyễn Văn A',
                'the_loai' => 'Công nghệ',
                'nxb' => 'NXB Bách Khoa',
                'gia_ban' => 180000,
                'mo_ta' => 'Sách học lập trình Python từ cơ bản đến nâng cao.',
                'nam_xuat_ban' => 2022,
                'so_trang' => 400,
                'hinh_anh' => 'https://picsum.photos/seed/python/300/400',
            ],
            
            // Thêm sách văn học nước ngoài
            [
                'ten_sach' => 'Harry Potter và Hòn Đá Phù Thủy',
                'tac_gia' => 'J.K. Rowling',
                'the_loai' => 'Văn học',
                'nxb' => 'NXB Trẻ',
                'gia_ban' => 150000,
                'mo_ta' => 'Tập đầu tiên trong series Harry Potter về cậu bé phù thủy.',
                'nam_xuat_ban' => 2022,
                'so_trang' => 366,
                'hinh_anh' => 'https://picsum.photos/seed/harrypotter/300/400',
            ],
            [
                'ten_sach' => 'Sherlock Holmes Toàn Tập',
                'tac_gia' => 'Arthur Conan Doyle',
                'the_loai' => 'Văn học',
                'nxb' => 'NXB Văn Học',
                'gia_ban' => 280000,
                'mo_ta' => 'Tuyển tập truyện trinh thám về thám tử lừng danh Sherlock Holmes.',
                'nam_xuat_ban' => 2021,
                'so_trang' => 800,
                'hinh_anh' => 'https://picsum.photos/seed/sherlock/300/400',
            ],
        ];

        foreach ($sachList as $sachData) {
            // Tìm hoặc tạo tác giả
            $author = TacGia::firstOrCreate(
                ['ten_tac_gia' => $sachData['tac_gia']],
                [
                    'duong_dan' => Str::slug($sachData['tac_gia']),
                    'trang_thai' => true
                ]
            );

            // Tìm thể loại
            $category = TheLoai::where('ten_the_loai', $sachData['the_loai'])->first();
            if (!$category) {
                $category = TheLoai::first();
            }

            // Tìm NXB
            $nxbSlug = Str::slug($sachData['nxb']);
            $publisher = NhaXuatBan::where('duong_dan', $nxbSlug)->orWhere('ten_nxb', $sachData['nxb'])->first();
            if (!$publisher) {
                $publisher = NhaXuatBan::create([
                    'ten_nxb' => $sachData['nxb'],
                    'duong_dan' => $nxbSlug,
                    'dia_chi' => 'Việt Nam',
                    'trang_thai' => true
                ]);
            }

            // Kiểm tra sách đã tồn tại chưa
            $existingSach = Sach::where('ten_sach', $sachData['ten_sach'])->first();
            if (!$existingSach) {
                Sach::create([
                    'ten_sach' => $sachData['ten_sach'],
                    'duong_dan' => Str::slug($sachData['ten_sach']),
                    'mo_ta' => $sachData['mo_ta'],
                    'gia_ban' => $sachData['gia_ban'],
                    'gia_khuyen_mai' => rand(0, 1) ? round($sachData['gia_ban'] * 0.85, -3) : null,
                    'so_luong_ton' => rand(10, 100),
                    'ma_tac_gia' => $author->ma_tac_gia,
                    'ma_the_loai' => $category->ma_the_loai,
                    'ma_nxb' => $publisher->ma_nxb,
                    'nam_xuat_ban' => $sachData['nam_xuat_ban'],
                    'so_trang' => $sachData['so_trang'],
                    'hinh_anh' => $sachData['hinh_anh'] ?? null,
                    'trang_thai' => 'active',
                ]);
                
                $this->command->info("Đã thêm sách: {$sachData['ten_sach']}");
            } else {
                // Cập nhật hình ảnh nếu sách đã tồn tại nhưng chưa có hình
                if (empty($existingSach->hinh_anh) && !empty($sachData['hinh_anh'])) {
                    $existingSach->update(['hinh_anh' => $sachData['hinh_anh']]);
                    $this->command->info("Đã cập nhật hình ảnh: {$sachData['ten_sach']}");
                } else {
                    $this->command->info("Sách đã tồn tại: {$sachData['ten_sach']}");
                }
            }
        }

        $this->command->info('Hoàn thành thêm sách mẫu!');
    }

    private function getOrCreateTheLoai()
    {
        $categories = [
            'Văn học', 'Kinh tế', 'Kỹ năng sống', 'Khoa học', 
            'Thiếu nhi', 'Tâm lý', 'Lịch sử', 'Công nghệ'
        ];

        foreach ($categories as $name) {
            TheLoai::firstOrCreate(
                ['ten_the_loai' => $name],
                [
                    'duong_dan' => Str::slug($name),
                    'trang_thai' => true
                ]
            );
        }

        return TheLoai::all();
    }

    private function getOrCreateTacGia()
    {
        return TacGia::all();
    }

    private function getOrCreateNXB()
    {
        $publishers = [
            ['ten_nxb' => 'NXB Kim Đồng', 'dia_chi' => '55 Quang Trung, Hà Nội'],
            ['ten_nxb' => 'NXB Văn Học', 'dia_chi' => '18 Nguyễn Trường Tộ, Hà Nội'],
            ['ten_nxb' => 'NXB Trẻ', 'dia_chi' => '161B Lý Chính Thắng, TP.HCM'],
            ['ten_nxb' => 'NXB Tổng Hợp', 'dia_chi' => '62 Nguyễn Thị Minh Khai, TP.HCM'],
            ['ten_nxb' => 'NXB Lao Động', 'dia_chi' => '175 Giảng Võ, Hà Nội'],
            ['ten_nxb' => 'NXB Hội Nhà Văn', 'dia_chi' => '65 Nguyễn Du, Hà Nội'],
            ['ten_nxb' => 'NXB Tri Thức', 'dia_chi' => '53 Nguyễn Du, Hà Nội'],
            ['ten_nxb' => 'NXB Thế Giới', 'dia_chi' => '46 Trần Hưng Đạo, Hà Nội'],
            ['ten_nxb' => 'NXB Khoa Học Xã Hội', 'dia_chi' => '26 Lý Thường Kiệt, Hà Nội'],
            ['ten_nxb' => 'NXB Bách Khoa', 'dia_chi' => '1 Đại Cồ Việt, Hà Nội'],
        ];

        foreach ($publishers as $pub) {
            $slug = Str::slug($pub['ten_nxb']);
            $existing = NhaXuatBan::where('duong_dan', $slug)->orWhere('ten_nxb', $pub['ten_nxb'])->first();
            
            if (!$existing) {
                NhaXuatBan::create([
                    'ten_nxb' => $pub['ten_nxb'],
                    'duong_dan' => $slug,
                    'dia_chi' => $pub['dia_chi'],
                    'trang_thai' => true
                ]);
            }
        }

        return NhaXuatBan::all();
    }
}
