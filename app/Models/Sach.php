<?php
/**
 * =============================================================================
 * Model Sach (Sách) - Quản lý thông tin sách trong hệ thống
 * =============================================================================
 * 
 * @package     App\Models
 * @author      BookStore Team
 * @version     1.0.0
 * 
 * CÔNG NGHỆ SỬ DỤNG:
 * - Eloquent ORM: Object-Relational Mapping của Laravel
 * - Soft Deletes: Xóa mềm (không xóa vĩnh viễn khỏi database)
 * - Accessors & Mutators: Tự động xử lý dữ liệu khi đọc/ghi
 * - Query Scopes: Tái sử dụng các điều kiện truy vấn
 * - Relationships: Định nghĩa quan hệ giữa các bảng
 * - Model Events: Tự động xử lý khi tạo/cập nhật
 * 
 * CÁC QUAN HỆ (RELATIONSHIPS):
 * - belongsTo: TheLoai, TacGia, NhaXuatBan (N-1)
 * - hasMany: ChiTietDonHang, DanhGia, GioHang, YeuThich (1-N)
 * 
 * CÁC SCOPE QUAN TRỌNG:
 * - active(): Lọc sách đang bán
 * - inStock(): Lọc sách còn hàng
 * - onSale(): Lọc sách đang khuyến mãi
 * - search(): Tìm kiếm theo từ khóa
 * =============================================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sach extends Model
{
    /**
     * Sử dụng các Trait của Laravel:
     * - HasFactory: Hỗ trợ tạo dữ liệu mẫu với Factory
     * - SoftDeletes: Xóa mềm - thêm cột deleted_at thay vì xóa thật
     */
    use HasFactory, SoftDeletes;

    /**
     * Tên bảng trong database
     * Laravel mặc định sẽ tìm bảng 'sachs' (số nhiều), ta override thành 'sach'
     */
    protected $table = 'sach';

    /**
     * Khóa chính của bảng
     * Laravel mặc định dùng 'id', ta override thành 'ma_sach'
     */
    protected $primaryKey = 'ma_sach';
    
    /**
     * Các cột được phép gán giá trị hàng loạt (Mass Assignment)
     * Bảo vệ khỏi lỗ hổng Mass Assignment Vulnerability
     */
    protected $fillable = [
        'ten_sach', 'duong_dan', 'mo_ta', 'noi_dung',
        'hinh_anh', 'gia_ban', 'gia_khuyen_mai', 'so_luong_ton',
        'ngay_xuat_ban', 'nam_xuat_ban', 'ma_the_loai', 'ma_tac_gia', 'ma_nxb',
        'trang_thai', 'luot_xem', 'diem_trung_binh', 'so_luot_danh_gia'
    ];

    /**
     * Ép kiểu dữ liệu tự động khi đọc từ database
     * Giúp đảm bảo kiểu dữ liệu đúng trong PHP
     */
    protected $casts = [
        'gia_ban' => 'decimal:0',
        'gia_khuyen_mai' => 'decimal:0',
        'so_luong_ton' => 'integer',
        'ngay_xuat_ban' => 'date',
        'nam_xuat_ban' => 'integer',
        'luot_xem' => 'integer',
        'diem_trung_binh' => 'decimal:2',
        'so_luot_danh_gia' => 'integer',
        'trang_thai' => 'string',
        'noi_bat' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * =========================================================================
     * MODEL EVENTS - Tự động xử lý khi tạo/cập nhật sách
     * =========================================================================
     * 
     * CÔNG NGHỆ: Laravel Model Events (creating, updating, deleting, etc.)
     * Cho phép hook vào lifecycle của Model để thực hiện logic tự động
     */
    protected static function boot()
    {
        parent::boot();
        
        /**
         * Event 'creating': Chạy trước khi tạo bản ghi mới
         * Tự động tạo đường dẫn (slug) từ tên sách nếu chưa có
         */
        static::creating(function ($sach) {
            if (empty($sach->duong_dan)) {
                $sach->duong_dan = Str::slug($sach->ten_sach);
            }
        });

        /**
         * Event 'updating': Chạy trước khi cập nhật bản ghi
         * Cập nhật lại slug nếu tên sách thay đổi
         */
        static::updating(function ($sach) {
            if ($sach->isDirty('ten_sach') && empty($sach->duong_dan)) {
                $sach->duong_dan = Str::slug($sach->ten_sach);
            }
        });
    }

    /**
     * =========================================================================
     * RELATIONSHIPS - Định nghĩa quan hệ giữa các bảng
     * =========================================================================
     * 
     * CÔNG NGHỆ: Eloquent Relationships
     * - belongsTo: Quan hệ N-1 (nhiều sách thuộc 1 thể loại)
     * - hasMany: Quan hệ 1-N (1 sách có nhiều đánh giá)
     */

    /**
     * Quan hệ với Thể loại (N-1)
     * Mỗi sách thuộc về 1 thể loại
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function theLoai()
    {
        return $this->belongsTo(TheLoai::class, 'ma_the_loai', 'ma_the_loai');
    }

    /**
     * Quan hệ với Tác giả (N-1)
     * Mỗi sách có 1 tác giả chính
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tacGia()
    {
        return $this->belongsTo(TacGia::class, 'ma_tac_gia', 'ma_tac_gia');
    }

    /**
     * Quan hệ với Nhà xuất bản (N-1)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nhaXuatBan()
    {
        return $this->belongsTo(NhaXuatBan::class, 'ma_nxb', 'ma_nxb');
    }

    /**
     * Quan hệ với Chi tiết đơn hàng (1-N)
     * 1 sách có thể xuất hiện trong nhiều đơn hàng
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_sach', 'ma_sach');
    }

    /**
     * Quan hệ với Đánh giá (1-N)
     * 1 sách có nhiều đánh giá từ khách hàng
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'ma_sach', 'ma_sach');
    }

    /**
     * Quan hệ với Giỏ hàng (1-N)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gioHang()
    {
        return $this->hasMany(GioHang::class, 'ma_sach', 'ma_sach');
    }

    /**
     * Quan hệ với Yêu thích (1-N)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function yeuThich()
    {
        return $this->hasMany(YeuThich::class, 'ma_sach', 'ma_sach');
    }

    /**
     * =========================================================================
     * ACCESSORS - Tự động xử lý khi đọc thuộc tính
     * =========================================================================
     * 
     * CÔNG NGHỆ: Laravel Accessors (getXxxAttribute)
     * Cho phép tính toán hoặc format dữ liệu khi truy cập thuộc tính
     */

    /**
     * Lấy giá hiện tại (ưu tiên giá khuyến mãi)
     * Sử dụng: $sach->gia_hien_tai
     * 
     * @return float
     */
    public function getGiaHienTaiAttribute()
    {
        return $this->gia_khuyen_mai ?: $this->gia_ban;
    }

    /**
     * Tính phần trăm giảm giá
     * Sử dụng: $sach->phan_tram_giam_gia
     * 
     * @return int Phần trăm giảm (0-100)
     */
    public function getPhanTramGiamGiaAttribute()
    {
        if ($this->gia_khuyen_mai && $this->gia_ban > 0) {
            return round((($this->gia_ban - $this->gia_khuyen_mai) / $this->gia_ban) * 100);
        }
        return 0;
    }

    /**
     * Lấy URL ảnh bìa sách
     * Tự động fallback sang ảnh mặc định nếu không có
     * Sử dụng: $sach->anh_bia_url
     * 
     * @return string URL của ảnh bìa
     */
    public function getAnhBiaUrlAttribute()
    {
        if ($this->hinh_anh) {
            // Nếu là URL đầy đủ (http/https), trả về trực tiếp
            if (str_starts_with($this->hinh_anh, 'http://') || str_starts_with($this->hinh_anh, 'https://')) {
                return $this->hinh_anh;
            }
            // Nếu là đường dẫn local
            $localPath = public_path('storage/' . $this->hinh_anh);
            if (file_exists($localPath)) {
                return asset('storage/' . $this->hinh_anh);
            }
        }
        // Sử dụng bìa sách mockup realistic từ các nguồn ảnh chất lượng cao
        return $this->getRealisticBookCover();
    }
    
    /**
     * Lấy bìa sách mockup realistic dựa trên thể loại và ID sách
     */
    protected function getRealisticBookCover(): string
    {
        // Danh sách bìa sách realistic theo thể loại
        $bookCovers = [
            'Văn học' => [
                'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1476275466078-4007374efbbe?w=300&h=400&fit=crop',
            ],
            'Kinh tế' => [
                'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1579532537598-459ecdaf39cc?w=300&h=400&fit=crop',
            ],
            'Kỹ năng sống' => [
                'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1499750310107-5fef28a66643?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1552664730-d307ca884978?w=300&h=400&fit=crop',
            ],
            'Khoa học' => [
                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1628595351029-c2bf17511435?w=300&h=400&fit=crop',
            ],
            'Thiếu nhi' => [
                'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=300&h=400&fit=crop',
            ],
            'Tâm lý' => [
                'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1474631245212-32dc3c8310c6?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=300&h=400&fit=crop',
            ],
            'Lịch sử' => [
                'https://images.unsplash.com/photo-1461360370896-922624d12a74?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=300&h=400&fit=crop',
            ],
            'Công nghệ' => [
                'https://images.unsplash.com/photo-1518770660439-4636190af475?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1504639725590-34d0984388bd?w=300&h=400&fit=crop',
            ],
            'Giáo dục' => [
                'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=300&h=400&fit=crop',
                'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?w=300&h=400&fit=crop',
            ],
        ];
        
        // Bìa sách mặc định đẹp
        $defaultCovers = [
            'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=300&h=400&fit=crop',
            'https://images.unsplash.com/photo-1512820790803-83ca734da794?w=300&h=400&fit=crop',
            'https://images.unsplash.com/photo-1543002588-bfa74002ed7e?w=300&h=400&fit=crop',
            'https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=300&h=400&fit=crop',
            'https://images.unsplash.com/photo-1476275466078-4007374efbbe?w=300&h=400&fit=crop',
            'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=300&h=400&fit=crop',
            'https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=300&h=400&fit=crop',
            'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=300&h=400&fit=crop',
        ];
        
        $theLoai = $this->theLoai->ten_the_loai ?? 'Khác';
        $covers = $bookCovers[$theLoai] ?? $defaultCovers;
        
        // Sử dụng ma_sach để chọn ảnh nhất quán cho mỗi sách
        $index = ($this->ma_sach ?? 0) % count($covers);
        
        return $covers[$index];
    }
    
    /**
     * Lấy màu bìa sách theo thể loại (backup)
     */
    protected function getBookCoverColors(): array
    {
        $categoryColors = [
            'Văn học' => ['bg' => '8B4513', 'text' => 'F5DEB3'],
            'Kinh tế' => ['bg' => '1E3A5F', 'text' => 'FFFFFF'],
            'Kỹ năng sống' => ['bg' => '2E8B57', 'text' => 'FFFFFF'],
            'Khoa học' => ['bg' => '4B0082', 'text' => 'FFFFFF'],
            'Thiếu nhi' => ['bg' => 'FF6B6B', 'text' => 'FFFFFF'],
            'Tâm lý' => ['bg' => '6B5B95', 'text' => 'FFFFFF'],
            'Lịch sử' => ['bg' => '8B0000', 'text' => 'FFD700'],
            'Công nghệ' => ['bg' => '2C3E50', 'text' => '3498DB'],
        ];
        
        $theLoai = $this->theLoai->ten_the_loai ?? 'Khác';
        return $categoryColors[$theLoai] ?? ['bg' => '607D8B', 'text' => 'FFFFFF'];
    }

    public function getAnhBiaAttribute()
    {
        return $this->hinh_anh;
    }

    public function getTrangThaiTextAttribute()
    {
        switch ($this->trang_thai) {
            case 'active':
                return $this->so_luong_ton > 0 ? 'Đang bán' : 'Hết hàng';
            case 'inactive':
                return 'Ngừng bán';
            default:
                return 'Không xác định';
        }
    }

    public function getIdAttribute()
    {
        return $this->ma_sach;
    }

    // Scope methods
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('so_luong_ton', '>', 0);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('gia_khuyen_mai')
                    ->where('gia_khuyen_mai', '>', 0);
    }

    public function scopeByCategory($query, $theLoaiId)
    {
        return $query->where('ma_the_loai', $theLoaiId);
    }

    public function scopeByAuthor($query, $tacGiaId)
    {
        return $query->where('ma_tac_gia', $tacGiaId);
    }

    public function scopeByPublisher($query, $nhaXuatBanId)
    {
        return $query->where('ma_nxb', $nhaXuatBanId);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('ten_sach', 'like', "%{$keyword}%")
              ->orWhere('mo_ta', 'like', "%{$keyword}%")
              ->orWhereHas('tacGia', function ($author) use ($keyword) {
                  $author->where('ten_tac_gia', 'like', "%{$keyword}%");
              })
              ->orWhereHas('theLoai', function ($category) use ($keyword) {
                  $category->where('ten_the_loai', 'like', "%{$keyword}%");
              })
              ->orWhereHas('nhaXuatBan', function ($publisher) use ($keyword) {
                  $publisher->where('ten_nxb', 'like', "%{$keyword}%");
              });
        });
    }

    public function scopePopular($query)
    {
        return $query->orderBy('luot_xem', 'desc');
    }

    public function scopeHighRated($query, $minRating = 4.0)
    {
        return $query->where('diem_trung_binh', '>=', $minRating);
    }

    // Helper methods
    public function isInStock()
    {
        return $this->so_luong_ton > 0;
    }

    public function isOnSale()
    {
        return $this->gia_khuyen_mai && $this->gia_khuyen_mai > 0;
    }

    public function isActive()
    {
        return $this->trang_thai === 'active';
    }

    public function canOrder($quantity = 1)
    {
        return $this->isActive() && $this->isInStock() && $this->so_luong_ton >= $quantity;
    }

    public function incrementView()
    {
        $this->increment('luot_xem');
    }

    public function updateRating($newRating)
    {
        $totalRatings = $this->so_luot_danh_gia;
        $currentAverage = $this->diem_trung_binh ?: 0;
        
        $newAverage = (($currentAverage * $totalRatings) + $newRating) / ($totalRatings + 1);
        
        $this->update([
            'diem_trung_binh' => round($newAverage, 2),
            'so_luot_danh_gia' => $totalRatings + 1
        ]);
    }
}
