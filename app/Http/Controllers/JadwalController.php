<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\JadwalTemplate;
use App\Models\Dokter;

use Barryvdh\DomPDF\Facade\Pdf;

class JadwalController extends Controller
{
    public function index()
    {
        return view('jadwal.jadwal');
    }

    public function template($hari)
    {
        // Ambil semua sesi hari ini, group per klinik, eager load relasi
        $sesiPerKlinik = JadwalTemplate::with(['klinik', 'dokter'])
            ->where('hari', $hari)
            ->join('klinik', 'klinik.id', '=', 'jadwal_template.klinik_id')
            ->orderBy('klinik.urutan_tampil')
            ->orderBy('jadwal_template.jam_mulai')
            ->select('jadwal_template.*')
            ->get()
            ->groupBy('klinik_id');

        return view('jadwal.template_jadwal', compact('hari', 'sesiPerKlinik'));
    }

    public function generateHarian($tanggal)
    {
        $carbon = Carbon::parse($tanggal)->locale('id');
        $hari   = strtolower($carbon->isoFormat('dddd'));

        // Ambil semua dokter aktif untuk dropdown pengganti
        $semuaDokter = Dokter::where('status_aktif', 1)->orderBy('nama')->get();

        // Ambil sesi hari ini group per klinik
        $sesiPerKlinik = JadwalTemplate::with(['klinik', 'dokter'])
            ->where('hari', $hari)
            ->join('klinik', 'klinik.id', '=', 'jadwal_template.klinik_id')
            ->orderBy('klinik.urutan_tampil')
            ->orderBy('jadwal_template.jam_mulai')
            ->select('jadwal_template.*')
            ->get()
            ->groupBy('klinik_id');

        return view('jadwal.make_jadwal', compact('hari', 'tanggal', 'sesiPerKlinik', 'semuaDokter'));
    }

    public function downloadTemplate($hari)
    {
        $sesiPerKlinik = JadwalTemplate::with(['klinik', 'dokter'])
            ->where('hari', $hari)
            ->join('klinik', 'klinik.id', '=', 'jadwal_template.klinik_id')
            ->orderBy('klinik.urutan_tampil')
            ->orderBy('jadwal_template.jam_mulai')
            ->select('jadwal_template.*')
            ->get()
            ->groupBy('klinik_id');

        $pdf = Pdf::loadView('jadwal.pdf_template', compact('hari', 'sesiPerKlinik'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('jadwal-' . $hari . '.pdf');
    }

    public function simpanHarian(Request $request, $tanggal)
    {
        return redirect()->route('jadwal.generate', $tanggal);
    }

     public function poster(Request $request, $tanggal)
  {
      $carbon        = Carbon::parse($tanggal)->locale('id');
      $hari          = strtoupper($carbon->isoFormat('dddd'));
      $tanggal_label = $carbon->isoFormat('D MMMM YYYY');

      $sesiDipilih = $request->input('sesi', []); 

      
       $sesiData = JadwalTemplate::with(['klinik', 'dokter'])
          ->whereIn('jadwal_template.id', array_keys($sesiDipilih))
          ->join('klinik', 'klinik.id', '=', 'jadwal_template.klinik_id')
          ->orderBy('klinik.urutan_tampil')
          ->orderBy('jadwal_template.jam_mulai')
          ->select('jadwal_template.*')
          ->get()
          ->groupBy('klinik_id');

      return view('jadwal.poster_jadwal', compact('hari', 'tanggal_label', 'sesiDipilih', 'sesiData'));
  }
}
