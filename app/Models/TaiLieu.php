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
}
