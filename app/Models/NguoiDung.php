<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;

class NguoiDung extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'ho_ten', 'email', 'mat_khau', 'so_dien_thoai',
        'dia_chi', 'vai_tro', 'xac_minh_email_luc', 'token_ghi_nho'
    ];

    protected $hidden = [
        'mat_khau', 'token_ghi_nho'
    ];

    protected $casts = [
        'xac_minh_email_luc' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Mutator để hash mật khẩu
    public function setMatKhauAttribute($value)
    {
        $this->attributes['mat_khau'] = Hash::make($value);
    }

    // Accessor để kiểm tra vai trò
    public function isAdmin()
    {
        return $this->vai_tro === 'admin';
    }

    public function isCustomer()
    {
        return $this->vai_tro === 'customer';
    }

    // Relationships
    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'ma_nguoi_dung', 'id');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'ma_nguoi_dung', 'id');
    }

    public function gioHang()
    {
        return $this->hasMany(GioHang::class, 'ma_nguoi_dung', 'id');
    }

    public function yeuThich()
    {
        return $this->hasMany(YeuThich::class, 'ma_nguoi_dung', 'id');
    }

    // Scope để lọc theo vai trò
    public function scopeAdmin($query)
    {
        return $query->where('vai_tro', 'admin');
    }

    public function scopeCustomer($query)
    {
        return $query->where('vai_tro', 'customer');
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('xac_minh_email_luc');
    }
}
