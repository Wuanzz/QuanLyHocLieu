<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nganh extends Model
{
    protected $table = 'Nganh';
    protected $primaryKey = 'NganhID';
    public $timestamps = false;
    protected $fillable = ['TenNganh', 'MoTa', 'KhoaID'];
}
