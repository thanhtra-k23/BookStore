<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChiTietDonHang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_don_hang';
    protected $primaryKey = 'ma_chi_tiet';

    protected $fillable = [
        'ma_don_hang', 'ma_sach', 'so_luong', 'gia_ban_tai_thoi_diem'
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'gia_ban_tai_thoi_diem' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function sach()
    {
        return $this->belongsTo(Sach::class, 'ma_sach', 'ma_sach');
    }

    // Accessor methods
    public function getThanhTienAttribute()
    {
        return $this->so_luong * $this->gia_ban_tai_thoi_diem;
    }

    // Boot method để tự động lấy giá sách hiện tại
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($chiTiet) {
            if (empty($chiTiet->gia_ban_tai_thoi_diem) && $chiTiet->sach) {
                $chiTiet->gia_ban_tai_thoi_diem = $chiTiet->sach->gia_khuyen_mai ?: $chiTiet->sach->gia_ban;
            }
        });
    }

    // Scope methods
    public function scopeByBook($query, $sachId)
    {
        return $query->where('ma_sach', $sachId);
    }

    public function scopeByOrder($query, $donHangId)
    {
        return $query->where('ma_don_hang', $donHangId);
    }
}
 