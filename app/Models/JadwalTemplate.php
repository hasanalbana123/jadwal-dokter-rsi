<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalTemplate extends Model
{
    protected $table = 'jadwal_template';
    protected $fillable = ['hari', 'klinik_id', 'jam_mulai', 'jam_selesai', 'keterangan_sesi'];

    public function klinik()
    {
        return $this->belongsTo(Klinik::class, 'klinik_id');
    }

    public function jadwalTemplateDokter()
    {
        return $this->hasMany(JadwalTemplateDokter::class, 'jadwal_template_id');
    }

    public function dokter()
    {
        return $this->belongsToMany(Dokter::class, 'jadwal_template_dokter', 'jadwal_template_id', 'dokter_id');
    }

    // Format jam: "08:00 - 12:00" → "08.00 - 12.00"
    public function getJamLabelAttribute(): string
    {
        $mulai   = substr($this->jam_mulai, 0, 5);
        $selesai = substr($this->jam_selesai, 0, 5);
        return str_replace(':', '.', $mulai) . ' - ' . str_replace(':', '.', $selesai);
    }
}
