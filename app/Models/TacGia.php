<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TacGia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tac_gia';
    protected $primaryKey = 'ma_tac_gia';

    protected $fillable = [
        'ten_tac_gia', 'duong_dan', 'tieu_su', 'hinh_anh', 
        'ngay_sinh', 'ngay_mat', 'quoc_tich', 'website', 'trang_thai'
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'ngay_mat' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Boot method để tự động tạo đường dẫn
    protected static function boot()
    {
        parent::boot();
        
        // Tạm thời tắt auto-generate slug để controller xử lý
        /*
        static::creating(function ($tacGia) {
            if (empty($tacGia->duong_dan)) {
                $tacGia->duong_dan = Str::slug($tacGia->ten_tac_gia);
            }
        });

        static::updating(function ($tacGia) {
            if ($tacGia->isDirty('ten_tac_gia') && empty($tacGia->duong_dan)) {
                $tacGia->duong_dan = Str::slug($tacGia->ten_tac_gia);
            }
        });
        */
    }

    // Relationships
    public function sach()
    {
        return $this->hasMany(Sach::class, 'ma_tac_gia', 'ma_tac_gia');
    }

    // Accessor methods
    public function getAnhDaiDienUrlAttribute()
    {
        if ($this->hinh_anh) {
            return asset('storage/' . $this->hinh_anh);
        }
        return asset('images/default-author.svg');
    }

    public function getTuoiAttribute()
    {
        if ($this->ngay_sinh) {
            $endDate = $this->ngay_mat ?: now();
            return $this->ngay_sinh->diffInYears($endDate);
        }
        return null;
    }

    public function getTrangThaiAttribute()
    {
        return $this->ngay_mat ? 'Đã mất' : 'Còn sống';
    }

    // Scope methods
    public function scopeAlive($query)
    {
        return $query->whereNull('ngay_mat');
    }

    public function scopeDeceased($query)
    {
        return $query->whereNotNull('ngay_mat');
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('quoc_tich', $country);
    }

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
        return $query->where('ten_tac_gia', 'like', "%{$keyword}%")
                    ->orWhere('tieu_su', 'like', "%{$keyword}%");
    }

    // Helper methods
    public function isAlive()
    {
        return is_null($this->ngay_mat);
    }

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
}
