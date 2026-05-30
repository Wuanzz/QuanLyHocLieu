<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaiLieu extends Model
{
    protected $table = 'TaiLieu';
    protected $primaryKey = 'TaiLieuID';
    public $timestamps = false;
    protected $fillable = [
        'TenTaiLieu', 
        'DuongDanFile', 
        'LoaiTaiLieu', 
        'KichThuoc', 
        'NgayUpload', 
        'TrangThaiDuyet', 
        'LuotTai', 
        'NguoiDungID', 
        'HocPhanID'
    ];

    public function NguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiDungID', 'NguoiDungID');
    }

    public function HocPhan()
    {
        return $this->belongsTo(HocPhan::class, 'HocPhanID', 'HocPhanID');
    }

    public function BinhLuans()
    {
        return $this->hasMany(BinhLuan::class, 'TaiLieuID', 'TaiLieuID');
    }
}
