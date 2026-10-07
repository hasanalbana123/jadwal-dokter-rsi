<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LayananTambahan extends Model
{
    use SoftDeletes;

    protected $table = 'layanan_tambahan';
    protected $fillable = ['nama', 'icon', 'urutan_tampil'];

    public function daftarKontak()
    {
        return $this->hasMany(LayananKontak::class, 'layanan_tambahan_id')->orderBy('urutan');
    }
}