<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalTemplateDokter extends Model
{
    protected $table = 'jadwal_template_dokter';
    protected $fillable = ['jadwal_template_id', 'dokter_id'];

    public function jadwalTemplate()
    {
        return $this->belongsTo(JadwalTemplate::class, 'jadwal_template_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
}
