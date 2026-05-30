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
}
