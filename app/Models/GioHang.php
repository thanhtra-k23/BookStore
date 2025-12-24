<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GioHang extends Model
{
    use HasFactory;

    protected $table = 'gio_hang';
    protected $primaryKey = 'ma_gio_hang';

    protected $fillable = [
        'ma_nguoi_dung', 'ma_sach', 'so_luong'
    ];

    protected $casts = [
        'so_luong' => 'integer',
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

    // Accessor methods
    public function getThanhTienAttribute()
    {
        return $this->so_luong * $this->sach->gia_hien_tai;
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
    public function increaseQuantity($amount = 1)
    {
        $maxQuantity = $this->sach->so_luong_ton;
        $newQuantity = min($this->so_luong + $amount, $maxQuantity);
        
        $this->update(['so_luong' => $newQuantity]);
        
        return $newQuantity;
    }

    public function decreaseQuantity($amount = 1)
    {
        $newQuantity = max($this->so_luong - $amount, 1);
        
        $this->update(['so_luong' => $newQuantity]);
        
        return $newQuantity;
    }

    public function canAddQuantity($amount = 1)
    {
        return ($this->so_luong + $amount) <= $this->sach->so_luong_ton;
    }

    public static function addToCart($nguoiDungId, $sachId, $soLuong = 1)
    {
        $existingItem = static::where('ma_nguoi_dung', $nguoiDungId)
                             ->where('ma_sach', $sachId)
                             ->first();

        if ($existingItem) {
            return $existingItem->increaseQuantity($soLuong);
        }

        return static::create([
            'ma_nguoi_dung' => $nguoiDungId,
            'ma_sach' => $sachId,
            'so_luong' => $soLuong
        ]);
    }

    public static function getCartTotal($nguoiDungId)
    {
        return static::byUser($nguoiDungId)
                    ->with('sach')
                    ->get()
                    ->sum('thanh_tien');
    }

    public static function getCartItemCount($nguoiDungId)
    {
        return static::byUser($nguoiDungId)->sum('so_luong');
    }

    public static function clearCart($nguoiDungId)
    {
        return static::byUser($nguoiDungId)->delete();
    }
}