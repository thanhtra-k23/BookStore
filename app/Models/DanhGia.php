<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DanhGia extends Model
{
    use HasFactory;

    protected $table = 'danh_gia';
    protected $primaryKey = 'ma_danh_gia';

    protected $fillable = [
        'ma_sach', 'ma_nguoi_dung', 'diem_danh_gia', 'noi_dung_danh_gia', 'trang_thai', 'ly_do_tu_choi'
    ];

    protected $casts = [
        'diem_danh_gia' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    const TRANG_THAI_CHO_DUYET = 'cho_duyet';
    const TRANG_THAI_DA_DUYET = 'da_duyet';
    const TRANG_THAI_BI_TU_CHOI = 'tu_choi';

    // Relationships
    public function sach()
    {
        return $this->belongsTo(Sach::class, 'ma_sach', 'ma_sach');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'ma_nguoi_dung', 'ma_nguoi_dung');
    }

    // Accessor methods
    public function getTrangThaiTextAttribute()
    {
        $trangThaiTexts = [
            self::TRANG_THAI_CHO_DUYET => 'Chờ duyệt',
            self::TRANG_THAI_DA_DUYET => 'Đã duyệt',
            self::TRANG_THAI_BI_TU_CHOI => 'Bị từ chối'
        ];

        return $trangThaiTexts[$this->trang_thai] ?? 'Không xác định';
    }

    public function getDiemSoTextAttribute()
    {
        $stars = str_repeat('★', $this->diem_danh_gia) . str_repeat('☆', 5 - $this->diem_danh_gia);
        return $stars . " ({$this->diem_danh_gia}/5)";
    }

    // Scope methods
    public function scopeApproved($query)
    {
        return $query->where('trang_thai', self::TRANG_THAI_DA_DUYET);
    }

    public function scopePending($query)
    {
        return $query->where('trang_thai', self::TRANG_THAI_CHO_DUYET);
    }

    public function scopeRejected($query)
    {
        return $query->where('trang_thai', self::TRANG_THAI_BI_TU_CHOI);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('diem_danh_gia', $rating);
    }

    public function scopeByBook($query, $sachId)
    {
        return $query->where('ma_sach', $sachId);
    }

    public function scopeByUser($query, $nguoiDungId)
    {
        return $query->where('ma_nguoi_dung', $nguoiDungId);
    }

    // Helper methods
    public function isApproved()
    {
        return $this->trang_thai === self::TRANG_THAI_DA_DUYET;
    }

    public function isPending()
    {
        return $this->trang_thai === self::TRANG_THAI_CHO_DUYET;
    }

    public function isRejected()
    {
        return $this->trang_thai === self::TRANG_THAI_BI_TU_CHOI;
    }

    public function approve()
    {
        $this->update(['trang_thai' => self::TRANG_THAI_DA_DUYET]);
        
        // Cập nhật điểm đánh giá của sách
        $this->sach->updateRating($this->diem_danh_gia);
    }

    public function reject()
    {
        $this->update(['trang_thai' => self::TRANG_THAI_BI_TU_CHOI]);
    }
}