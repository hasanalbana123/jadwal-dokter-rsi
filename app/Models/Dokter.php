<?php

 namespace App\Models;

  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\SoftDeletes;

  class Dokter extends Model
  {
      use SoftDeletes;

      protected $table = 'dokter';
      protected $fillable = ['nama', 'gelar', 'status_aktif'];

      public function jadwalTemplateDokter()
      {
          return $this->hasMany(JadwalTemplateDokter::class, 'dokter_id');
      }

      public function getNamaLengkapAttribute(): string
      {
          return $this->gelar ? $this->nama . ', ' . $this->gelar : $this->nama;
      }
  }