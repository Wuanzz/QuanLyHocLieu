<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NguoiDung extends Model
{
    protected $table = 'NguoiDung';
    protected $primaryKey = 'NguoiDungID';
    public $timestamps = false;
    protected $fillable = ['HoTen', 'Email', 'MatKhau', 'AnhDaiDien', 'NgayDangKy', 'TrangThai', 'VaiTro'];
}
