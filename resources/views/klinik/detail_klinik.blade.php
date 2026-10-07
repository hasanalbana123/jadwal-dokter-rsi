<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Klinik</title>
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
            @if(session('error'))
                <div class="card" style="color:#dc2626; padding:12px; margin-bottom:12px;">{{ session('error') }}</div>
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
                </div>

                <table class="tabel-jadwal">
                    <thead>
                        <tr>
                            <th>Jam Praktik</th>
                            <th>Hari</th>
                            <th>Dokter</th>
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
                                </td>
                                <td>{{ $b->hari }}</td>
                                <td>{{ $b->dokter->join(', ') ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="padding:16px; color:#94a3b8;">Belum ada jadwal untuk klinik ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="footer-aksi">
                    <form action="{{ route('klinik.destroy', $klinik->id) }}" method="POST"
      onsubmit="return confirm('Pindahkan {{ $klinik->nama }} ke tempat sampah? Data bisa dipulihkan lagi.')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-secondary" style="color:#f59e0b;">Nonaktifkan</button>
</form>

                    <a href="{{ route('klinik.edit', $klinik) }}" class="btn-secondary" style="text-decoration:none;">Edit sesi</a>
                    <a href="{{ route('klinik.index') }}" class="link-kembali">kembali</a>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>