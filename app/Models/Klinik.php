<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Klinik extends Model
{
    use SoftDeletes;
    protected $table = 'klinik';
    protected $fillable = ['nama', 'urutan_tampil'];

    public function jadwalTemplate()
    {
        return $this->hasMany(JadwalTemplate::class, 'klinik_id');
    }
}
