<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Sach extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sach';

    protected $primaryKey = 'ma_sach';
    
    protected $fillable = [
        'ten_sach', 'duong_dan', 'mo_ta', 'noi_dung',
        'hinh_anh', 'gia_ban', 'gia_khuyen_mai', 'so_luong_ton',
        'ngay_xuat_ban', 'nam_xuat_ban', 'ma_the_loai', 'ma_tac_gia', 'ma_nxb',
        'trang_thai', 'luot_xem', 'diem_trung_binh', 'so_luot_danh_gia'
    ];

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

    // Boot method để tự động tạo đường dẫn
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($sach) {
            if (empty($sach->duong_dan)) {
                $sach->duong_dan = Str::slug($sach->ten_sach);
            }
        });

        static::updating(function ($sach) {
            if ($sach->isDirty('ten_sach') && empty($sach->duong_dan)) {
                $sach->duong_dan = Str::slug($sach->ten_sach);
            }
        });
    }

    // Relationships
    public function theLoai()
    {
        return $this->belongsTo(TheLoai::class, 'ma_the_loai', 'ma_the_loai');
    }

    public function tacGia()
    {
        return $this->belongsTo(TacGia::class, 'ma_tac_gia', 'ma_tac_gia');
    }

    public function nhaXuatBan()
    {
        return $this->belongsTo(NhaXuatBan::class, 'ma_nxb', 'ma_nxb');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_sach', 'ma_sach');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'ma_sach', 'ma_sach');
    }

    public function gioHang()
    {
        return $this->hasMany(GioHang::class, 'ma_sach', 'ma_sach');
    }

    public function yeuThich()
    {
        return $this->hasMany(YeuThich::class, 'ma_sach', 'ma_sach');
    }

    // Accessor methods
    public function getGiaHienTaiAttribute()
    {
        return $this->gia_khuyen_mai ?: $this->gia_ban;
    }

    public function getPhanTramGiamGiaAttribute()
    {
        if ($this->gia_khuyen_mai && $this->gia_ban > 0) {
            return round((($this->gia_ban - $this->gia_khuyen_mai) / $this->gia_ban) * 100);
        }
        return 0;
    }

    public function getAnhBiaUrlAttribute()
    {
        if ($this->hinh_anh) {
            return asset('storage/' . $this->hinh_anh);
        }
        return asset('images/default-book.svg');
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
