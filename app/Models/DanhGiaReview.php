<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGiaReview extends Model
{
    protected $table = 'DanhGiaReview';
    protected $primaryKey = 'DanhGiaID';
    public $timestamps = false;
    
    protected $fillable = ['ReviewID', 'NguoiDungID', 'SoSao', 'NgayDanhGia'];

    public function NguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiDungID', 'NguoiDungID');
    }
}