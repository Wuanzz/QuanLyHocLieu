<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaiLieu;
use App\Models\NguoiDung;
use App\Models\Review;

class BinhLuan extends Model
{
    protected $table = 'BinhLuan';
    protected $primaryKey = 'BinhLuanID';
    public $timestamps = false;
    protected $fillable = ['NoiDung', 'NgayDang', 'TrangThaiDuyet', 'NguoiDungID', 'ParentID', 'ReviewID', 'TaiLieuID'];

    public function TaiLieu()
    {
        return $this->belongsTo(TaiLieu::class, 'TaiLieuID', 'TaiLieuID');
    }

    public function NguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiDungID', 'NguoiDungID');
    }

    public function Review()
    {
        return $this->belongsTo(Review::class, 'ReviewID', 'ReviewID');
    }
}
