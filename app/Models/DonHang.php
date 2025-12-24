<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DonHang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'don_hang';
    protected $primaryKey = 'ma_don_hang';

    const TRANG_THAI_CHO_XAC_NHAN = 'cho_xac_nhan';
    const TRANG_THAI_DA_XAC_NHAN = 'da_xac_nhan';
    const TRANG_THAI_DANG_GIAO = 'dang_giao';
    const TRANG_THAI_DA_GIAO = 'da_giao';
    const TRANG_THAI_DA_HUY = 'da_huy';

    const PHUONG_THUC_COD = 'cod';
    const PHUONG_THUC_CHUYEN_KHOAN = 'chuyen_khoan';
    const PHUONG_THUC_THE_TIN_DUNG = 'the_tin_dung';

    protected $fillable = [
        'ma_don', 'ma_nguoi_dung', 'tong_tien', 'tong_tien_goc', 'so_tien_giam_gia',
        'ma_giam_gia_id', 'trang_thai', 'dia_chi_giao', 'so_dien_thoai_giao', 
        'phuong_thuc_thanh_toan', 'ghi_chu'
    ];

    protected $casts = [
        'tong_tien' => 'decimal:2',
        'tong_tien_goc' => 'decimal:2',
        'so_tien_giam_gia' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Boot method để tự động tạo mã đơn hàng
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($donHang) {
            if (empty($donHang->ma_don)) {
                $donHang->ma_don = 'DH' . date('Ymd') . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Relationships
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    public function chiTiet()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function maGiamGia()
    {
        return $this->belongsTo(MaGiamGia::class, 'ma_giam_gia_id', 'ma_giam_gia_id');
    }

    // Accessor methods
    public function getTrangThaiTextAttribute()
    {
        $trangThaiTexts = [
            self::TRANG_THAI_CHO_XAC_NHAN => 'Chờ xác nhận',
            self::TRANG_THAI_DA_XAC_NHAN => 'Đã xác nhận',
            self::TRANG_THAI_DANG_GIAO => 'Đang giao',
            self::TRANG_THAI_DA_GIAO => 'Đã giao',
            self::TRANG_THAI_DA_HUY => 'Đã hủy'
        ];

        return $trangThaiTexts[$this->trang_thai] ?? 'Không xác định';
    }

    public function getPhuongThucThanhToanTextAttribute()
    {
        $phuongThucTexts = [
            self::PHUONG_THUC_COD => 'Thanh toán khi nhận hàng',
            self::PHUONG_THUC_CHUYEN_KHOAN => 'Chuyển khoản',
            self::PHUONG_THUC_THE_TIN_DUNG => 'Thẻ tín dụng'
        ];

        return $phuongThucTexts[$this->phuong_thuc_thanh_toan] ?? 'Không xác định';
    }

    // Scope methods
    public function scopeByStatus($query, $status)
    {
        return $query->where('trang_thai', $status);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('ma_nguoi_dung', $userId);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Helper methods
    public function canCancel()
    {
        return in_array($this->trang_thai, [
            self::TRANG_THAI_CHO_XAC_NHAN,
            self::TRANG_THAI_DA_XAC_NHAN
        ]);
    }

    public function isCompleted()
    {
        return $this->trang_thai === self::TRANG_THAI_DA_GIAO;
    }

    public function isCancelled()
    {
        return $this->trang_thai === self::TRANG_THAI_DA_HUY;
    }

    public function getTotalQuantity()
    {
        return $this->chiTiet->sum('so_luong');
    }
}


