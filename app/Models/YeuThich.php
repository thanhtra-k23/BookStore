<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YeuThich extends Model
{
    use HasFactory;

    protected $table = 'yeu_thich';
    protected $primaryKey = 'ma_yeu_thich';

    protected $fillable = [
        'ma_nguoi_dung', 'ma_sach'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    public function sach()
    {
        return $this->belongsTo(Sach::class, 'ma_sach', 'ma_sach');
    }

    // Scope methods
    public function scopeByUser($query, $nguoiDungId)
    {
        return $query->where('ma_nguoi_dung', $nguoiDungId);
    }

    public function scopeByBook($query, $sachId)
    {
        return $query->where('ma_sach', $sachId);
    }

    // Helper methods
    public static function toggle($nguoiDungId, $sachId)
    {
        $existing = static::where('ma_nguoi_dung', $nguoiDungId)
                          ->where('ma_sach', $sachId)
                          ->first();

        if ($existing) {
            $existing->delete();
            return false; // Removed from favorites
        }

        static::create([
            'ma_nguoi_dung' => $nguoiDungId,
            'ma_sach' => $sachId
        ]);

        return true; // Added to favorites
    }

    public static function isFavorite($nguoiDungId, $sachId)
    {
        return static::where('ma_nguoi_dung', $nguoiDungId)
                    ->where('ma_sach', $sachId)
                    ->exists();
    }

    public static function getFavoriteCount($nguoiDungId)
    {
        return static::byUser($nguoiDungId)->count();
    }

    public static function getFavoriteBooks($nguoiDungId)
    {
        return static::byUser($nguoiDungId)
                    ->with('sach')
                    ->latest()
                    ->get()
                    ->pluck('sach');
    }
}