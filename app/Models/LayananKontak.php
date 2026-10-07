<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananKontak extends Model
{
    protected $table = 'layanan_kontak';
    protected $fillable = ['layanan_tambahan_id', 'label', 'value', 'urutan'];
}