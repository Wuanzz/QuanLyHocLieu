<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TaiLieu;

class HocPhan extends Model
{
    protected $table = 'HocPhan';
    protected $primaryKey = 'HocPhanID';
    public $timestamps = false;
    protected $fillable = ['TenHocPhan', 'MoTa', 'NganhID'];

    public function Nganh()
    {
        return $this->belongsTo(Nganh::class, 'NganhID', 'NganhID');
    }

    public function TaiLieus()
    {
        return $this->hasMany(TaiLieu::class, 'HocPhanID', 'HocPhanID');
    }
}
