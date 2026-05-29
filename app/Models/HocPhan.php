<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HocPhan extends Model
{
    protected $table = 'HocPhan';
    protected $primaryKey = 'HocPhanID';
    public $timestamps = false;
    protected $fillable = ['TenHocPhan', 'MoTa', 'NganhID'];
}
