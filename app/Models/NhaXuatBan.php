<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class NhaXuatBan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'nha_xuat_ban';
    protected $primaryKey = 'ma_nxb';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'ma_nxb';
    }

    protected $fillable = [
        'ten_nxb', 'duong_dan', 'dia_chi', 'so_dien_thoai', 
        'email', 'website', 'mo_ta', 'logo', 'nam_thanh_lap'
    ];

    protected $casts = [
        'nam_thanh_lap' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Boot method để tự động tạo đường dẫn
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($nhaXuatBan) {
            if (empty($nhaXuatBan->duong_dan)) {
                $nhaXuatBan->duong_dan = Str::slug($nhaXuatBan->ten_nxb);
            }
        });

        static::updating(function ($nhaXuatBan) {
            if ($nhaXuatBan->isDirty('ten_nxb') && empty($nhaXuatBan->duong_dan)) {
                $nhaXuatBan->duong_dan = Str::slug($nhaXuatBan->ten_nxb);
            }
        });
    }

    // Relationships
    public function sach()
    {
        return $this->hasMany(Sach::class, 'ma_nxb', 'ma_nxb');
    }

    // Accessor methods
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return asset('images/default-publisher.jpg');
    }

    public function getTuoiAttribute()
    {
        if ($this->nam_thanh_lap) {
            return now()->year - $this->nam_thanh_lap;
        }
        return null;
    }

    // Scope methods
    public function scopeWithBookCount($query)
    {
        return $query->withCount('sach');
    }

    public function scopePopular($query)
    {
        return $query->withCount('sach')
                    ->orderBy('sach_count', 'desc');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('ten_nxb', 'like', "%{$keyword}%")
                    ->orWhere('mo_ta', 'like', "%{$keyword}%")
                    ->orWhere('dia_chi', 'like', "%{$keyword}%");
    }

    public function scopeEstablishedAfter($query, $year)
    {
        return $query->where('nam_thanh_lap', '>=', $year);
    }

    public function scopeEstablishedBefore($query, $year)
    {
        return $query->where('nam_thanh_lap', '<=', $year);
    }

    // Helper methods
    public function getBookCount()
    {
        return $this->sach()->count();
    }

    public function getActiveBookCount()
    {
        return $this->sach()->active()->count();
    }

    public function getAverageBookRating()
    {
        return $this->sach()
                   ->whereNotNull('diem_danh_gia')
                   ->avg('diem_danh_gia');
    }

    public function getTotalBookViews()
    {
        return $this->sach()->sum('luot_xem');
    }

    public function getNewestBook()
    {
        return $this->sach()->latest()->first();
    }

    public function getOldestBook()
    {
        return $this->sach()->oldest()->first();
    }

    public function getBestSellingBooks($limit = 10)
    {
        return $this->sach()
                   ->withCount('chiTietDonHangs')
                   ->orderBy('chi_tiet_don_hangs_count', 'desc')
                   ->limit($limit)
                   ->get();
    }
}
