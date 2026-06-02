<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaiLieu;
use App\Models\NguoiDung;

class BaoCao extends Model
{
    protected $table = 'BaoCao';
    protected $primaryKey = 'BaoCaoID';
    public $timestamps = false;
    protected $fillable = ['LyDo', 'NgayBaoCao', 'TrangThaiXuLy', 'NguoiDungID', 'ReviewID', 'TaiLieuID', 'BinhLuanID'];

    public function TaiLieu()
    {
        return $this->belongsTo(TaiLieu::class, 'TaiLieuID', 'TaiLieuID');
    }

    public function NguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiDungID', 'NguoiDungID');
    }
}
