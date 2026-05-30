<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'Review';
    protected $primaryKey = 'ReviewID';
    public $timestamps = false;
    protected $fillable = ['NoiDung', 'SoSao', 'NgayDang', 'TrangThaiDuyet', 'NguoiDungID', 'HocPhanID'];

    public function NguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiDungID', 'NguoiDungID');
    }

    public function HocPhan()
    {
        return $this->belongsTo(HocPhan::class, 'HocPhanID', 'HocPhanID');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGiaReview::class, 'ReviewID', 'ReviewID');
    }

    // Hàm tự động tính toán điểm trung bình sao của bài Review
    public function getSaoTrungBinhAttribute()
    {
        // Tính trung bình cột SoSao từ bảng phụ, làm tròn 1 chữ số thập phân
        $trungBinh = $this->danhGias()->avg('SoSao');
        return $trungBinh ? round($trungBinh, 1) : 0;
    }
}
