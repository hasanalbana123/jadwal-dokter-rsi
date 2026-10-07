<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Sesi Klinik</title>
<link rel="stylesheet" href="{{ asset('klinik-style.css') }}">
</head>
<body>
<div class="app-wrapper">
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <p>RUMAH SAKIT ISLAM<br>FATIMAH CILACAP</p>
            </div>
            <nav class="sidebar-menu">
                <a href="{{ route('jadwal.index') }}" >Jadwal Praktik</a>
  <a href="{{ route('klinik.index') }}" class="active">Data Klinik</a>
  <a href="{{ route('dokter.index') }}">Data Dokter</a>
  <a href="{{ route('layanan.index') }}">Info Layanan</a>
            </nav>
        </div>
        <div class="sidebar-footer">
            <button class="btn-logout">Logout</button>
        </div>
    </aside>
    

    <div class="main-content">
        <header class="top-header">
            <div>
                <h1>Aplikasi Generator & Download Jadwal Praktik Dokter</h1>
                <p>Data Klinik</p>
            </div>
            <div class="header-actions">
                <span class="header-badge">Logo</span>
                <span class="header-badge">Akreditasi</span>
            </div>
        </header>

        <main class="page-body">
            @if(session('success'))
                <div class="card" style="color:#16a34a; padding:12px; margin-bottom:12px;">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="card" style="color:#dc2626; padding:12px; margin-bottom:12px;">
                    @foreach($errors->all() as $pesan)
                        <div>{{ $pesan }}</div>
                    @endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-header-row">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="icon-placeholder" style="width:40px;height:40px;">
                            <img src="{{ asset('images/clinic.png') }}" alt="{{ $klinik->nama }}"
                                 style="width:100%; height:100%; object-fit:contain;">
                        </div>
                        <h2>{{ $klinik->nama }}</h2>
                    </div>
                    <button type="button" class="btn-secondary" onclick="toggleFormTambah()">+ Tambah Sesi</button>
                </div>

                <table class="tabel-jadwal">
                    <thead>
                        <tr>
                            <th>Jam Praktik</th>
                            <th>Hari</th>
                            <th>Dokter</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($baris as $b)
                            <tr class="{{ $loop->odd ? 'baris-ganjil' : 'baris-genap' }}">
                                <td>
                                    {{ $b->jam }}
                                    @if($b->keterangan)
                                        <span class="keterangan-sesi">({{ $b->keterangan }})</span>
                                    @endif
                                    <button type="button" class="icon-edit-btn" onclick="toggleEditSesi({{ $loop->index }})">&#9998;</button>
                                </td>
                                <td>
                                    {{ $b->hari }}
                                    <button type="button" class="icon-edit-btn" onclick="toggleEditSesi({{ $loop->index }})">&#9998;</button>
                                </td>
                                <td>{{ $b->dokter->join(', ') ?: '-' }}</td>
                                <td>
                                    <form action="{{ route('klinik.sesi.destroy', [$klinik->id, $b->first_id]) }}" method="POST"
                                          onsubmit="return confirm('Hapus sesi {{ $b->jam }} ({{ $b->hari }})?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="ids" value="{{ $b->ids }}">
                                        <button type="submit" class="btn-danger-text">Hapus sesi ini &#128465;</button>
                                    </form>
                                </td>
                            </tr>
                            {{-- Form edit sesi (tersembunyi, muncul saat klik ikon pensil) --}}
                            <tr id="edit-sesi-{{ $loop->index }}" style="display:none;">
                                <td colspan="4" style="padding:8px 0 16px;">
                                    <div style="padding:16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">
                                        <p style="font-size:13px; font-weight:600; color:#0f766e; margin:0 0 14px;">Edit Sesi</p>
                                        @include('klinik._form_sesi', [
                                            'action'   => route('klinik.sesi.update', [$klinik->id, $b->first_id]),
                                            'method'   => 'PUT',
                                            'formName' => 'edit-' . $loop->index,
                                            'sesi'     => $b,
                                            'dokters'  => $dokters,
                                            'batalJs'  => 'toggleEditSesi(' . $loop->index . ')',
                                        ])
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding:16px; color:#94a3b8;">Belum ada sesi untuk klinik ini. Klik "+ Tambah Sesi".</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Form tambah sesi (tersembunyi, muncul saat klik + Tambah Sesi) --}}
                <div id="form-tambah-sesi"
                     style="display:{{ $errors->any() && old('_form') === 'tambah' ? 'block' : 'none' }}; margin-top:20px; padding:16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px;">
                    <p style="font-size:13px; font-weight:600; color:#0f766e; margin:0 0 14px;">Tambah Sesi Baru</p>
                    @include('klinik._form_sesi', [
                        'action'   => route('klinik.sesi.store', $klinik->id),
                        'method'   => 'POST',
                        'formName' => 'tambah',
                        'dokters'  => $dokters,
                        'batalJs'  => 'toggleFormTambah()',
                    ])
                </div>

                <div class="footer-aksi">
                    <a href="{{ route('klinik.show', $klinik->id) }}" class="btn-primary" style="text-decoration:none;">Selesai</a>
                    <a href="{{ route('klinik.index') }}" class="link-kembali">kembali</a>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function toggleFormTambah() {
    const form = document.getElementById('form-tambah-sesi');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

function toggleEditSesi(i) {
    const baris = document.getElementById('edit-sesi-' + i);
    baris.style.display = baris.style.display === 'none' ? 'table-row' : 'none';
}
</script>
</body>
</html>