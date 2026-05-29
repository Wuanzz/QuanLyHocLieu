<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaoCao extends Model
{
    protected $table = 'BaoCao';
    protected $primaryKey = 'BaoCaoID';
    public $timestamps = false;
    protected $fillable = ['LyDo', 'NgayBaoCao', 'TrangThaiXuLy', 'NguoiDungID', 'ReviewID', 'TaiLieuID', 'BinhLuanID'];
}
