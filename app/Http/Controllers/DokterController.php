<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Klinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::orderBy('nama')->get();

        return view('dokter.dokter', compact('dokters'));
    }

    public function show($id)
    {
        $dokter = Dokter::findOrFail($id);

        $kliniks = \App\Models\Klinik::whereIn('id',
            \App\Models\JadwalTemplateDokter::where('dokter_id', $id)
                ->join('jadwal_template', 'jadwal_template_dokter.jadwal_template_id', '=', 'jadwal_template.id')
                ->pluck('jadwal_template.klinik_id')
                ->unique()
        )->orderBy('urutan_tampil')->get();

        return view('dokter.detail_dokter', compact('dokter', 'kliniks'));
    }

    public function edit($id)
    {
        $dokter  = Dokter::findOrFail($id);
        $kliniks = \App\Models\Klinik::orderBy('urutan_tampil')->get();

        // Ambil id klinik yang sudah terdaftar untuk dokter ini
        $klinikTerdaftar = \App\Models\JadwalTemplateDokter::where('dokter_id', $id)
            ->join('jadwal_template', 'jadwal_template_dokter.jadwal_template_id', '=', 'jadwal_template.id')
            ->pluck('jadwal_template.klinik_id')
            ->unique()
            ->toArray();

        return view('dokter.edit_dokter', compact('dokter', 'kliniks', 'klinikTerdaftar'));
    }

    public function create()
    {
        $kliniks = Klinik::orderBy('urutan_tampil')->get();

        return view('dokter.tambah_dokter', compact('kliniks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'gelar' => 'nullable|string|max:100',
        ]);

        Dokter::create([
            'nama'  => $request->nama,
            'gelar' => $request->gelar,
        ]);

        return redirect()->route('dokter.index');
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);

        $request->validate([
            'nama'  => 'required|string|max:255',
            'gelar' => 'nullable|string|max:100',
        ]);

        $dokter->update([
            'nama'  => $request->nama,
            'gelar' => $request->gelar,
        ]);

        return redirect()->route('dokter.show', $dokter->id);
    }

    // ================= HAPUS DOKTER =================
    // destroy = soft delete (pindah ke tempat sampah).
    // Hapus permanen hanya lewat halaman tempat sampah (hapusPermanen).

    public function destroy($id)
    {
        Dokter::findOrFail($id)->delete();

        return redirect()->route('dokter.index')->with('success', 'Dokter dipindahkan ke tempat sampah.');
    }

    public function sampah()
    {
        $dokters = Dokter::onlyTrashed()->orderByDesc('deleted_at')->get();

        return view('dokter.sampah_dokter', compact('dokters'));
    }

    public function restore($id)
    {
        Dokter::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('dokter.sampah')->with('success', 'Dokter berhasil dipulihkan.');
    }

    public function hapusPermanen($id)
    {
        $dokter = Dokter::onlyTrashed()->findOrFail($id);

        // Tolak kalau dokter masih tercatat di jadwal (template / harian),
        // supaya jadwal tidak rusak dan tidak kena error foreign key
        if ($this->dipakaiJadwal($id)) {
            return back()->with('error', 'Dokter masih tercatat di jadwal, tidak bisa dihapus permanen. Hapus dulu dari sesi jadwalnya.');
        }

        DB::transaction(function () use ($dokter, $id) {
            // relasi pendaftaran dokter-klinik ikut dibersihkan (bukan data jadwal)
            if (Schema::hasTable('dokter_klinik')) {
                DB::table('dokter_klinik')->where('dokter_id', $id)->delete();
            }

            $dokter->forceDelete();
        });

        return redirect()->route('dokter.sampah')->with('success', 'Dokter dihapus permanen.');
    }

    private function dipakaiJadwal($dokterId): bool
    {
        foreach (['jadwal_template_dokter', 'jadwal_praktik_dokter'] as $tabel) {
            if (Schema::hasTable($tabel) && DB::table($tabel)->where('dokter_id', $dokterId)->exists()) {
                return true;
            }
        }

        return false;
    }
}