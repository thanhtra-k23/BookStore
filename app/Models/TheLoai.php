<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TheLoai extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'the_loai';
    protected $primaryKey = 'ma_the_loai';

    protected $fillable = [
        'ten_the_loai', 'duong_dan', 'mo_ta', 'hinh_anh', 'thu_tu_hien_thi'
    ];

    protected $casts = [
        'thu_tu_hien_thi' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Boot method để tự động tạo đường dẫn
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($theLoai) {
            if (empty($theLoai->duong_dan)) {
                $theLoai->duong_dan = Str::slug($theLoai->ten_the_loai);
            }
        });

        static::updating(function ($theLoai) {
            if ($theLoai->isDirty('ten_the_loai') && empty($theLoai->duong_dan)) {
                $theLoai->duong_dan = Str::slug($theLoai->ten_the_loai);
            }
        });
    }

    // Relationships
    public function sach()
    {
        return $this->hasMany(Sach::class, 'ma_the_loai');
    }

    // Accessor methods
    public function getAnhDaiDienUrlAttribute()
    {
        if ($this->hinh_anh) {
            return asset('storage/' . $this->hinh_anh);
        }
        return asset('images/default-category.svg');
    }

    public function getAnhDaiDienAttribute()
    {
        return $this->hinh_anh;
    }

    public function getDuongDanDayDuAttribute()
    {
        return $this->duong_dan;
    }

    // Scope methods
    public function scopeOrdered($query)
    {
        return $query->orderBy('thu_tu_hien_thi', 'asc')
                    ->orderBy('ten_the_loai', 'asc');
    }

    public function scopeWithBookCount($query)
    {
        return $query->withCount('sach');
    }

    // Helper methods
    public function getTotalBookCount()
    {
        return $this->sach()->count();
    }
}
