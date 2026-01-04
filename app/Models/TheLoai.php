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
        'ten_the_loai', 'duong_dan', 'mo_ta', 'hinh_anh', 'thu_tu_hien_thi', 'ma_the_loai_cha', 'trang_thai'
    ];

    protected $casts = [
        'thu_tu_hien_thi' => 'integer',
        'ma_the_loai_cha' => 'integer',
        'trang_thai' => 'boolean',
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

    // Thể loại cha (parent category)
    public function theLoaiCha()
    {
        return $this->belongsTo(TheLoai::class, 'ma_the_loai_cha', 'ma_the_loai');
    }

    // Alias cho theLoaiCha
    public function parent()
    {
        return $this->belongsTo(TheLoai::class, 'ma_the_loai_cha', 'ma_the_loai');
    }

    // Thể loại con (child categories)
    public function theLoaiCon()
    {
        return $this->hasMany(TheLoai::class, 'ma_the_loai_cha', 'ma_the_loai');
    }

    // Alias cho theLoaiCon
    public function children()
    {
        return $this->hasMany(TheLoai::class, 'ma_the_loai_cha', 'ma_the_loai');
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

    public function scopeActive($query)
    {
        return $query->where('trang_thai', true);
    }

    public function scopeParentOnly($query)
    {
        return $query->whereNull('ma_the_loai_cha');
    }

    // Helper methods
    public function getTotalBookCount()
    {
        return $this->sach()->count();
    }

    public function hasChildren()
    {
        return $this->theLoaiCon()->count() > 0;
    }

    public function isChild()
    {
        return !is_null($this->ma_the_loai_cha);
    }
}
