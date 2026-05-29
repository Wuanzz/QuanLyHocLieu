<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    protected $table = 'BinhLuan';
    protected $primaryKey = 'BinhLuanID';
    public $timestamps = false;
    protected $fillable = ['NoiDung', 'NgayDang', 'TrangThaiDuyet', 'NguoiDungID', 'ParentID', 'ReviewID', 'TaiLieuID'];
}
