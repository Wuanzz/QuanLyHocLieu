<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Khoa extends Model
{
    protected $table = 'Khoa';
    protected $primaryKey = 'KhoaID';
    public $timestamps = false;
    protected $fillable = ['TenKhoa', 'MoTa'];
}
