<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class MaGiamGia extends Model
{
    use HasFactory;

    protected $table = 'ma_giam_gia';
    protected $primaryKey = 'ma_giam_gia';

    const LOAI_PHAN_TRAM = 'phan_tram';
    const LOAI_SO_TIEN = 'so_tien';

    protected $fillable = [
        'ma_code', 'ten_ma_giam_gia', 'mo_ta', 'loai_giam_gia', 'gia_tri_giam',
        'gia_tri_don_hang_toi_thieu', 'gia_tri_giam_toi_da', 'so_luong',
        'da_su_dung', 'gioi_han_su_dung_moi_user', 'ngay_bat_dau', 'ngay_ket_thuc', 'trang_thai'
    ];

    protected $casts = [
        'gia_tri_giam' => 'decimal:2',
        'gia_tri_don_hang_toi_thieu' => 'decimal:2',
        'gia_tri_giam_toi_da' => 'decimal:2',
        'so_luong' => 'integer',
        'da_su_dung' => 'integer',
        'gioi_han_su_dung_moi_user' => 'integer',
        'trang_thai' => 'boolean',
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'ma_giam_gia', 'ma_giam_gia');
    }

    // Accessor methods
    public function getLoaiGiamGiaTextAttribute()
    {
        return $this->loai_giam_gia === self::LOAI_PHAN_TRAM ? 'Phần trăm' : 'Số tiền';
    }

    public function getGiaTriGiamTextAttribute()
    {
        if ($this->loai_giam_gia === self::LOAI_PHAN_TRAM) {
            return $this->gia_tri_giam . '%';
        }
        return number_format($this->gia_tri_giam, 0, ',', '.') . 'đ';
    }

    public function getSoLuongConLaiAttribute()
    {
        if ($this->so_luong === null) {
            return null; // Không giới hạn
        }
        return max(0, $this->so_luong - $this->da_su_dung);
    }

    // Scope methods
    public function scopeActive($query)
    {
        return $query->where('trang_thai', true)
                    ->where('ngay_bat_dau', '<=', now())
                    ->where('ngay_ket_thuc', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
                    ->where(function ($q) {
                        $q->whereNull('so_luong')
                          ->orWhereRaw('da_su_dung < so_luong');
                    });
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('ma_code', $code);
    }

    // Helper methods
    public function isActive()
    {
        return $this->trang_thai === true &&
               $this->ngay_bat_dau <= now() &&
               $this->ngay_ket_thuc >= now();
    }

    public function isAvailable()
    {
        return $this->isActive() && 
               ($this->so_luong === null || $this->so_luong_con_lai > 0);
    }

    public function canApplyToOrder($orderTotal)
    {
        return $this->isAvailable() && 
               $orderTotal >= $this->gia_tri_don_hang_toi_thieu;
    }

    public function calculateDiscount($orderTotal)
    {
        if (!$this->canApplyToOrder($orderTotal)) {
            return 0;
        }

        if ($this->loai_giam_gia === self::LOAI_PHAN_TRAM) {
            $discount = ($orderTotal * $this->gia_tri_giam) / 100;
            
            if ($this->gia_tri_giam_toi_da && $discount > $this->gia_tri_giam_toi_da) {
                $discount = $this->gia_tri_giam_toi_da;
            }
            
            return $discount;
        }

        return min($this->gia_tri_giam, $orderTotal);
    }

    public function use()
    {
        $this->increment('da_su_dung');
    }

    public function isExpired()
    {
        return $this->ngay_ket_thuc < now();
    }

    public function isNotStarted()
    {
        return $this->ngay_bat_dau > now();
    }

    public function getStatusText()
    {
        if ($this->isNotStarted()) {
            return 'Chưa bắt đầu';
        }
        
        if ($this->isExpired()) {
            return 'Đã hết hạn';
        }
        
        if (!$this->isAvailable()) {
            return 'Đã hết lượt sử dụng';
        }
        
        if ($this->trang_thai !== true) {
            return 'Không hoạt động';
        }
        
        return 'Có thể sử dụng';
    }
}