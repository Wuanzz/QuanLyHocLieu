<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'Review';
    protected $primaryKey = 'ReviewID';
    public $timestamps = false;
    protected $fillable = ['NoiDung', 'SoSao', 'NgayDang', 'TrangThaiDuyet', 'NguoiDungID', 'HocPhanID'];
}
