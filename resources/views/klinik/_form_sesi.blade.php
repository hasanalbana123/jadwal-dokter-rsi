{{--
    Form sesi dipakai bareng untuk "Tambah Sesi" dan "Edit Sesi".
    Variabel: $action, $method (POST|PUT), $formName, $dokters, $batalJs, $sesi (opsional, kalau edit)
--}}
@php
    $isEdit        = isset($sesi);
    $mulai         = $isEdit ? $sesi->jam_mulai_input   : old('jam_mulai');
    $selesai       = $isEdit ? $sesi->jam_selesai_input : old('jam_selesai');
    $ket           = $isEdit ? $sesi->keterangan        : old('keterangan_sesi');
    $hariDipilih   = $isEdit ? $sesi->hari_list         : (array) old('hari', []);
    $dokterDipilih = $isEdit ? $sesi->dokter_ids        : (array) old('dokter_id', []);
    $inputStyle    = 'width:100%; padding:7px 10px; border:1px solid #cbd5e1; border-radius:8px; font-size:13px;';
    $labelStyle    = 'font-size:12px; color:#64748b; display:block; margin-bottom:4px;';
@endphp

<form action="{{ $action }}" method="POST">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif
    <input type="hidden" name="_form" value="{{ $formName }}">
    @if($isEdit)
        <input type="hidden" name="ids" value="{{ $sesi->ids }}">
    @endif

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
        <div>
            <label style="{{ $labelStyle }}">Jam Mulai</label>
            <input type="time" name="jam_mulai" value="{{ $mulai }}" required style="{{ $inputStyle }}">
        </div>
        <div>
            <label style="{{ $labelStyle }}">Jam Selesai</label>
            <input type="time" name="jam_selesai" value="{{ $selesai }}" required style="{{ $inputStyle }}">
        </div>
    </div>

    <div style="margin-bottom:12px;">
        <label style="{{ $labelStyle }}">Keterangan Sesi <span style="color:#94a3b8;">(opsional, misal: Praktek Pagi, Praktek Malam)</span></label>
        <input type="text" name="keterangan_sesi" value="{{ $ket }}" placeholder="Misal: Praktek Malam" style="{{ $inputStyle }}">
    </div>

    <div style="margin-bottom:12px;">
        <label style="font-size:12px; color:#64748b; display:block; margin-bottom:6px;">Hari</label>
        <div style="display:flex; flex-wrap:wrap; gap:8px;">
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $hari)
                <label style="display:flex; align-items:center; gap:4px; font-size:13px; cursor:pointer;">
                    <input type="checkbox" name="hari[]" value="{{ strtolower($hari) }}"
                           @checked(in_array(strtolower($hari), $hariDipilih))> {{ $hari }}
                </label>
            @endforeach
        </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:16px;">
        @foreach([0, 1] as $i)
            <div>
                <label style="{{ $labelStyle }}">
                    {{ $i === 0 ? 'Dokter' : 'Dokter ke-2 (opsional, kalau gantian)' }}
                </label>
                <select name="dokter_id[]" style="{{ $inputStyle }}">
                    <option value="">{{ $i === 0 ? '— Pilih Dokter —' : '— Tidak ada —' }}</option>
                    @foreach($dokters as $d)
                        <option value="{{ $d->id }}" @selected(($dokterDipilih[$i] ?? null) == $d->id)>
                            {{ $d->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>

    <div style="display:flex; gap:10px;">
        <button type="submit" class="btn-primary">Simpan Sesi</button>
        <button type="button" class="btn-secondary" onclick="{{ $batalJs }}">Batal</button>
    </div>
</form>
