<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class NguoiDung extends Authenticatable
{
    protected $table = 'NguoiDung';
    protected $primaryKey = 'NguoiDungID';
    public $timestamps = false;
    protected $fillable = ['HoTen', 'Email', 'MatKhau', 'AnhDaiDien', 'NgayDangKy', 'TrangThai', 'VaiTro'];

    // Ghi đè phương thức mặc định để Laravel nhận diện đúng cột mật khẩu
    public function getAuthPassword()
    {
        return $this->MatKhau;
    }
}
