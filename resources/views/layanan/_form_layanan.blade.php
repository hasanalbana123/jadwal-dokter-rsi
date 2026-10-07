{{--
    Form layanan dipakai bareng untuk Tambah dan Edit.
    Variabel: $action, $method (POST|PUT), $batalUrl, $layanan (opsional, kalau edit)
--}}
@php
    $isEdit = isset($layanan);

    if (old('kontak') !== null) {
        $kontakRows = array_values(old('kontak'));
    } elseif ($isEdit) {
        $kontakRows = $layanan->daftarKontak->map(fn ($k) => ['label' => $k->label, 'value' => $k->value])->all();
    } else {
        $kontakRows = [['label' => '', 'value' => '']]; // tambah baru: mulai dengan 1 baris kosong
    }

    $namaAwal = $isEdit ? $layanan->nama : '';
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="info-row baris-ganjil">
        <span class="info-label">Nama Layanan</span>
        <span class="info-value" style="flex:1;">
            <input type="text" name="nama" value="{{ old('nama', $namaAwal) }}" required
                   placeholder="Contoh: Layanan IGD"
                   style="width:100%; padding:6px 8px; border:1px solid #cbd5e1; border-radius:6px;">
        </span>
    </div>

    <div class="info-row baris-genap" style="align-items:flex-start;">
        <span class="info-label" style="padding-top:4px;">Kontak</span>
        <span class="info-value" style="flex:1;">
            <p style="font-size:12px; color:#94a3b8; margin:0 0 8px;">Boleh dikosongkan (misal layanan yang cuma punya icon), bisa tambah/hapus sesuai kebutuhan.</p>

            <div id="kontak-list">
                @foreach($kontakRows as $i => $k)
                    <div class="kontak-row-edit">
                        <input type="text" name="kontak[{{ $i }}][label]" class="label-input"
                               value="{{ $k['label'] ?? '' }}" placeholder="Label (misal: WhatsApp)">
                        <input type="text" name="kontak[{{ $i }}][value]" class="value-input"
                               value="{{ $k['value'] ?? '' }}" placeholder="Nilai (misal: 0822-1144-1313)">
                        <button type="button" class="btn-hapus-kontak" onclick="hapusKontak(this)">&#128465;</button>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn-tambah-kontak" onclick="tambahKontak()">+ Tambah Kontak</button>
        </span>
    </div>

    <div class="info-row baris-ganjil" style="align-items:flex-start;">
        <span class="info-label" style="padding-top:4px;">Icon</span>
        <span class="info-value">
            @if($isEdit && $layanan->icon)
                <div class="icon-upload-box" style="padding:0; overflow:hidden; margin:0 0 8px;">
                    <img src="{{ asset('storage/' . $layanan->icon) }}" alt="{{ $layanan->nama }}"
                         style="width:100%; height:100%; object-fit:contain;">
                </div>
                <span style="font-size:12px; color:#94a3b8; display:block; margin-bottom:4px;">Pilih file baru kalau mau mengganti icon.</span>
            @endif
            <input type="file" name="icon" accept="image/*" style="font-size:13px;">
            <span style="font-size:12px; color:#94a3b8; display:block; margin-top:4px;">PNG/JPG, maksimal 2 MB.</span>
        </span>
    </div>

    <div class="footer-aksi">
        <button type="submit" class="btn-primary">{{ $isEdit ? 'Simpan edit' : 'Simpan Layanan' }}</button>
        <div class="footer-aksi-kanan">
            <a href="{{ $batalUrl }}" class="btn-secondary" style="text-decoration:none;">Batal</a>
            <a href="{{ route('layanan.index') }}" class="link-kembali">kembali</a>
        </div>
    </div>
</form>

<script>
let kontakIdx = {{ count($kontakRows) }};

function tambahKontak() {
    const baris = document.createElement('div');
    baris.className = 'kontak-row-edit';
    baris.innerHTML =
        '<input type="text" name="kontak[' + kontakIdx + '][label]" class="label-input" placeholder="Label (misal: WhatsApp)">' +
        '<input type="text" name="kontak[' + kontakIdx + '][value]" class="value-input" placeholder="Nilai (misal: 0822-1144-1313)">' +
        '<button type="button" class="btn-hapus-kontak" onclick="hapusKontak(this)">&#128465;</button>';
    document.getElementById('kontak-list').appendChild(baris);
    kontakIdx++;
}

function hapusKontak(tombol) {
    tombol.closest('.kontak-row-edit').remove();
}
</script>