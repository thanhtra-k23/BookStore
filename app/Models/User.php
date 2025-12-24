<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ho_ten',
        'email',
        'mat_khau',
        'so_dien_thoai',
        'dia_chi',
        'vai_tro',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'mat_khau',
        'token_ghi_nho',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'xac_minh_email_luc' => 'datetime',
            'mat_khau' => 'hashed',
        ];
    }

    /**
     * Get the password attribute name for authentication
     */
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    /**
     * Get the password attribute name for authentication
     */
    public function getAuthPasswordName()
    {
        return 'mat_khau';
    }

    /**
     * Get name attribute (alias for ho_ten)
     */
    public function getNameAttribute()
    {
        return $this->ho_ten;
    }

    /**
     * Set name attribute (alias for ho_ten)
     */
    public function setNameAttribute($value)
    {
        $this->attributes['ho_ten'] = $value;
    }

    /**
     * Get password attribute (alias for mat_khau)
     */
    public function getPasswordAttribute()
    {
        return $this->mat_khau;
    }

    /**
     * Set password attribute (alias for mat_khau)
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['mat_khau'] = $value;
    }

    /**
     * Get avatar URL
     */
    public function getAvatarUrlAttribute()
    {
        // Generate avatar from name initials
        $initials = collect(explode(' ', $this->ho_ten))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');
            
        return "https://ui-avatars.com/api/?name={$initials}&background=667eea&color=fff&size=128";
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->vai_tro === 'admin';
    }

    /**
     * Check if user is active (always true for now)
     */
    public function isActive()
    {
        return !$this->trashed();
    }

    /**
     * Relationships
     */
    public function nguoiDung()
    {
        return $this->hasOne(NguoiDung::class);
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class);
    }

    public function gioHangs()
    {
        return $this->hasMany(GioHang::class);
    }

    public function yeuThichs()
    {
        return $this->hasMany(YeuThich::class);
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    public function scopeAdmin($query)
    {
        return $query->where('vai_tro', 'admin');
    }

    public function scopeCustomer($query)
    {
        return $query->where('vai_tro', 'customer');
    }
}
