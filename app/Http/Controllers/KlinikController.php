<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\JadwalTemplate;
use App\Models\Klinik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class KlinikController extends Controller
{
    private const URUTAN_HARI = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

    public function index()
    {
        $kliniks = Klinik::orderBy('urutan_tampil')->get();
        return view('klinik.klinik', compact('kliniks'));
    }

    public function create()
    {
        return view('klinik.tambah_klinik');
    }

    public function show($id)
    {
        $klinik = Klinik::findOrFail($id);
        $baris  = $this->barisSesi($id);

        return view('klinik.detail_klinik', compact('klinik', 'baris'));
    }

    public function edit($id)
    {
        $klinik  = Klinik::findOrFail($id);
        $baris   = $this->barisSesi($id);
        $dokters = Dokter::orderBy('nama')->get();

        return view('klinik.edit_klinik', compact('klinik', 'baris', 'dokters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        Klinik::create([
            'nama' => $request->nama,
        ]);

        return redirect()->route('klinik.index');
    }

    // ================= HAPUS KLINIK =================
    // destroy = soft delete (pindah ke tempat sampah).
    // Hapus permanen hanya lewat halaman tempat sampah (hapusPermanen).

    public function destroy($id)
    {
        Klinik::findOrFail($id)->delete();

        return redirect()->route('klinik.index')->with('success', 'Klinik dipindahkan ke tempat sampah.');
    }

    public function sampah()
    {
        $kliniks = Klinik::onlyTrashed()->orderByDesc('deleted_at')->get();

        return view('klinik.sampah_klinik', compact('kliniks'));
    }

    public function restore($id)
    {
        Klinik::onlyTrashed()->findOrFail($id)->restore();

        return redirect()->route('klinik.sampah')->with('success', 'Klinik berhasil dipulihkan.');
    }

    public function hapusPermanen($id)
    {
        $klinik = Klinik::onlyTrashed()->findOrFail($id);

        if ($this->punyaJadwal($id)) {
            return back()->with('error', 'Klinik masih punya jadwal, tidak bisa dihapus permanen.');
        }

        $klinik->forceDelete();

        return redirect()->route('klinik.sampah')->with('success', 'Klinik dihapus permanen.');
    }

    // ================= SESI JADWAL (jadwal_template) =================

    public function storeSesi(Request $request, $klinik)
    {
        $klinikModel = Klinik::findOrFail($klinik);
        $data = $this->validasiSesi($request);

        DB::transaction(function () use ($data, $klinikModel) {
            // 1 baris jadwal_template per hari yang dicentang
            foreach ($data['hari'] as $hari) {
                $sesi = new JadwalTemplate();
                $sesi->klinik_id       = $klinikModel->id;
                $sesi->hari            = $hari;
                $sesi->jam_mulai       = $data['jam_mulai'];
                $sesi->jam_selesai     = $data['jam_selesai'];
                $sesi->keterangan_sesi = $data['keterangan_sesi'] ?? null;
                $sesi->save();

                $sesi->dokter()->sync($data['dokter_id']);
            }
        });

        return redirect()->route('klinik.edit', $klinikModel->id)->with('success', 'Sesi berhasil ditambahkan.');
    }

    public function updateSesi(Request $request, $klinik, $sesi)
    {
        $data = $this->validasiSesi($request);
        $ids  = $this->idsDariRequest($request, $sesi);

        $rows = JadwalTemplate::where('klinik_id', $klinik)->whereIn('id', $ids)->get();
        abort_if($rows->isEmpty(), 404);

        DB::transaction(function () use ($rows, $data, $klinik) {
            $perHari = $rows->keyBy(fn ($r) => strtolower($r->hari));

            // hari yang dicentang: update baris lama / buat baris baru
            foreach ($data['hari'] as $hari) {
                $row = $perHari->get($hari) ?? new JadwalTemplate();

                if (!$row->exists) {
                    $row->klinik_id = $klinik;
                    $row->hari      = $hari;
                }
                $row->jam_mulai       = $data['jam_mulai'];
                $row->jam_selesai     = $data['jam_selesai'];
                $row->keterangan_sesi = $data['keterangan_sesi'] ?? null;
                $row->save();

                $row->dokter()->sync($data['dokter_id']);
            }

            // hari yang dicentang dilepas: hapus barisnya
            foreach ($perHari as $hari => $row) {
                if (!in_array($hari, $data['hari'], true)) {
                    $row->dokter()->detach();
                    $row->delete();
                }
            }
        });

        return redirect()->route('klinik.edit', $klinik)->with('success', 'Sesi berhasil diperbarui.');
    }

    public function destroySesi(Request $request, $klinik, $sesi)
    {
        $ids  = $this->idsDariRequest($request, $sesi);
        $rows = JadwalTemplate::where('klinik_id', $klinik)->whereIn('id', $ids)->get();

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                $row->dokter()->detach();
                $row->delete();
            }
        });

        return redirect()->route('klinik.edit', $klinik)->with('success', 'Sesi berhasil dihapus.');
    }

    // ================= HELPER =================

    // Cek apakah klinik masih dipakai di jadwal (cegah error foreign key saat hard delete)
    private function punyaJadwal($klinikId): bool
    {
        return JadwalTemplate::where('klinik_id', $klinikId)->exists();
    }

    private function validasiSesi(Request $request): array
    {
        $tabelDokter = (new Dokter())->getTable();

        $data = $request->validate([
            'jam_mulai'       => 'required|date_format:H:i',
            'jam_selesai'     => 'required|date_format:H:i|after:jam_mulai',
            'keterangan_sesi' => 'nullable|string|max:100',
            'hari'            => 'required|array|min:1',
            'hari.*'          => 'in:' . implode(',', self::URUTAN_HARI),
            'dokter_id'       => 'required|array|min:1',
            'dokter_id.*'     => 'nullable|exists:' . $tabelDokter . ',id',
        ], [
            'hari.required'       => 'Pilih minimal 1 hari.',
            'dokter_id.required'  => 'Pilih minimal 1 dokter.',
            'jam_selesai.after'   => 'Jam selesai harus setelah jam mulai.',
        ]);

        // buang dropdown kosong & duplikat (dokter ke-2 opsional)
        $data['dokter_id'] = array_values(array_unique(array_filter($data['dokter_id'])));

        if (empty($data['dokter_id'])) {
            throw ValidationException::withMessages(['dokter_id' => 'Pilih minimal 1 dokter.']);
        }

        return $data;
    }

    // "5,6,7" -> [5,6,7]  (satu "sesi" di layar bisa terdiri dari beberapa baris hari)
    private function idsDariRequest(Request $request, $sesi): array
    {
        return array_values(array_filter(array_map(
            'intval',
            explode(',', (string) $request->input('ids', $sesi))
        )));
    }

    // Gabungkan baris jadwal_template yang jam, keterangan, & dokternya sama jadi 1 sesi
    private function barisSesi($klinikId)
    {
        $sesiList = JadwalTemplate::with('dokter')
            ->where('klinik_id', $klinikId)
            ->orderBy('jam_mulai')
            ->get();

        return $sesiList
            ->groupBy(function ($s) {
                return $s->jam_mulai . '|' . $s->jam_selesai . '|' . $s->keterangan_sesi . '|'
                    . $s->dokter->pluck('id')->sort()->implode(',');
            })
            ->map(function ($group) {
                $first = $group->first();

                $idx = $group
                    ->map(fn ($s) => array_search(strtolower($s->hari), self::URUTAN_HARI))
                    ->filter(fn ($i) => $i !== false)
                    ->unique()->sort()->values()->all();

                return (object) [
                    'ids'               => $group->pluck('id')->implode(','),
                    'first_id'          => $first->id,
                    'jam'               => $first->jam_label,
                    'jam_mulai'         => $first->jam_mulai,
                    'jam_mulai_input'   => substr($first->jam_mulai, 0, 5),
                    'jam_selesai_input' => substr($first->jam_selesai, 0, 5),
                    'keterangan'        => $first->keterangan_sesi,
                    'hari'              => $idx
                        ? $this->rangkumHari($idx)
                        : $group->pluck('hari')->map(fn ($h) => ucfirst($h))->unique()->join(', '),
                    'hari_list'         => $group->map(fn ($s) => strtolower($s->hari))->unique()->values()->all(),
                    'urut'              => $idx[0] ?? 99,
                    'dokter'            => $first->dokter->pluck('nama_lengkap'),
                    'dokter_ids'        => $first->dokter->pluck('id')->all(),
                ];
            })
            ->sortBy([['jam_mulai', 'asc'], ['urut', 'asc']])
            ->values();
    }

    // [0,1,2,3] -> "Senin s/d Kamis" | [0,1,2,3,5] -> "Senin s/d Kamis, Sabtu"
    private function rangkumHari(array $idx): string
    {
        $nama = self::URUTAN_HARI;
        $runs = [];
        $start = $prev = $idx[0];

        foreach (array_slice($idx, 1) as $i) {
            if ($i === $prev + 1) {
                $prev = $i;
                continue;
            }
            $runs[] = [$start, $prev];
            $start = $prev = $i;
        }
        $runs[] = [$start, $prev];

        $bagian = array_map(function ($r) use ($nama) {
            [$a, $b] = $r;
            if ($a === $b) {
                return ucfirst($nama[$a]);
            }
            if ($b - $a === 1) {
                return ucfirst($nama[$a]) . ' & ' . ucfirst($nama[$b]);
            }
            return ucfirst($nama[$a]) . ' s/d ' . ucfirst($nama[$b]);
        }, $runs);

        return implode(', ', $bagian);
    }
}