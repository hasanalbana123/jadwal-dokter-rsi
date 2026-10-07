<?php

namespace App\Http\Controllers;

use App\Models\LayananKontak;
use App\Models\LayananTambahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = LayananTambahan::orderBy('urutan_tampil')->get();

        return view('layanan.layanan', compact('layanans'));
    }

    public function create()
    {
        return view('layanan.tambah_layanan');
    }

    public function show($id)
    {
        $layanan = LayananTambahan::with('daftarKontak')->findOrFail($id);

        return view('layanan.detail_layanan', compact('layanan'));
    }

    public function edit($id)
    {
        $layanan = LayananTambahan::with('daftarKontak')->findOrFail($id);

        return view('layanan.edit_layanan', compact('layanan'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        DB::transaction(function () use ($request, $data) {
            $layanan = LayananTambahan::create([
                'nama'          => $data['nama'],
                'icon'          => $request->hasFile('icon')
                    ? $request->file('icon')->store('layanan-icons', 'public')
                    : null,
                'urutan_tampil' => (LayananTambahan::withTrashed()->max('urutan_tampil') ?? 0) + 1,
            ]);

            $this->simpanKontak($layanan, $data['kontak']);
        });

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $layanan = LayananTambahan::findOrFail($id);
        $data    = $this->validasi($request);

        DB::transaction(function () use ($request, $layanan, $data) {
            $layanan->nama = $data['nama'];

            // ganti icon kalau ada upload baru (icon lama dihapus dari storage)
            if ($request->hasFile('icon')) {
                if ($layanan->icon) {
                    Storage::disk('public')->delete($layanan->icon);
                }
                $layanan->icon = $request->file('icon')->store('layanan-icons', 'public');
            }

            $layanan->save();

            $this->simpanKontak($layanan, $data['kontak']);
        });

        return redirect()->route('layanan.show', $layanan->id)->with('success', 'Layanan berhasil diperbarui.');
    }

    // ================= HAPUS LAYANAN =================
    // destroy = soft delete (pindah ke tempat sampah).
    // Hapus permanen hanya lewat halaman tempat sampah (hapusPermanen).

    public function destroy($id)
    {
        LayananTambahan::findOrFail($id)->delete();

        return redirect()->route('layanan.index')->with('success', 'Layanan dipindahkan ke tempat sampah.');
    }

    public function sampah()
    {
        $layanans = LayananTambahan::onlyTrashed()->orderByDesc('deleted_at')->get();

        return view('layanan.sampah_layanan', compact('layanans'));
    }

    public function restore($id)
    {
        LayananTambahan::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('layanan.sampah')->with('success', 'Layanan berhasil dipulihkan.');
    }

    public function hapusPermanen($id)
    {
        $layanan = LayananTambahan::onlyTrashed()->findOrFail($id);

        DB::transaction(function () use ($layanan) {
            if ($layanan->icon) {
                Storage::disk('public')->delete($layanan->icon);
            }
            LayananKontak::where('layanan_tambahan_id', $layanan->id)->delete();
            $layanan->forceDelete();
        });

        return redirect()->route('layanan.sampah')->with('success', 'Layanan dihapus permanen.');
    }

    // ================= HELPER =================

    private function validasi(Request $request): array
    {
        $data = $request->validate([
            'nama'            => 'required|string|max:255',
            'icon'            => 'nullable|image|max:2048',
            'kontak'          => 'nullable|array',
            'kontak.*.label'  => 'nullable|string|max:100',
            'kontak.*.value'  => 'nullable|string|max:255',
        ]);

        $kontak = [];
        foreach ($data['kontak'] ?? [] as $k) {
            $label = trim($k['label'] ?? '');
            $value = trim($k['value'] ?? '');

            if ($label === '' && $value === '') {
                continue; // baris kosong dibuang
            }
            if ($label === '' || $value === '') {
                throw ValidationException::withMessages([
                    'kontak' => 'Tiap kontak harus punya label dan nilai (atau kosongkan barisnya).',
                ]);
            }
            $kontak[] = ['label' => $label, 'value' => $value];
        }

        $data['kontak'] = $kontak;

        return $data;
    }

    // Ganti seluruh kontak layanan dengan daftar baru (urutan sesuai baris di form)
    private function simpanKontak(LayananTambahan $layanan, array $kontak): void
    {
        LayananKontak::where('layanan_tambahan_id', $layanan->id)->delete();

        foreach ($kontak as $i => $k) {
            LayananKontak::create([
                'layanan_tambahan_id' => $layanan->id,
                'label'               => $k['label'],
                'value'               => $k['value'],
                'urutan'              => $i + 1,
            ]);
        }
    }
}