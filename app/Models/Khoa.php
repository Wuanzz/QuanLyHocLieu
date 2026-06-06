<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Nganh;

class Khoa extends Model
{
    protected $table = 'Khoa';
    protected $primaryKey = 'KhoaID';
    public $timestamps = false;
    protected $fillable = ['TenKhoa', 'MoTa'];

    public function Nganhs()
    {
        return $this->hasMany(Nganh::class, 'KhoaID', 'KhoaID');
    }
}
