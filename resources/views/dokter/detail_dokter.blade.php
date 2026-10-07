<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Dokter</title>
<link rel="stylesheet" href="{{ asset('dokter-style.css') }}">
</head>
<body>
<div class="app-wrapper">
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <p>RUMAH SAKIT ISLAM<br>FATIMAH CILACAP</p>
            </div>
            <nav class="sidebar-menu">
                <a href="{{ route('jadwal.index') }}">Jadwal Praktik</a>
                <a href="{{ route('klinik.index') }}">Data Klinik</a>
                <a href="{{ route('dokter.index') }}" class="active">Data Dokter</a>
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
                <p>Data Dokter</p>
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
                            <img src="{{ asset('images/doktericon.png') }}" alt="Ikon dokter"
                                 style="width:100%; height:100%; object-fit:contain;">
                        </div>
                        <h2>{{ $dokter->nama_lengkap }}</h2>
                    </div>
                </div>

                <div class="info-row baris-ganjil">
                    <span class="info-label">Nama Dokter</span>
                    <span class="info-value">{{ $dokter->nama }}</span>
                </div>
                <div class="info-row baris-genap">
                    <span class="info-label">Gelar Pendidikan</span>
                    <span class="info-value">{{ $dokter->gelar ?: '-' }}</span>
                </div>
                <div class="info-row baris-ganjil">
                    <span class="info-label">Klinik</span>
                    <span class="info-value">
                        {{ $kliniks->pluck('nama')->join(', ') ?: '-' }}
                    </span>
                </div>

                <div class="footer-aksi">
                    <a href="{{ route('dokter.edit', $dokter->id) }}" class="btn-secondary" style="text-decoration:none;">Edit info</a>

                    {{-- Soft delete: pindah ke tempat sampah (hapus permanen ada di halaman tempat sampah) --}}
                    <form action="{{ route('dokter.destroy', $dokter->id) }}" method="POST"
                          onsubmit="return confirm('Pindahkan dokter {{ $dokter->nama }} ke tempat sampah? Data bisa dipulihkan lagi.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary" style="color:#f59e0b;">Nonaktifkan</button>
                    </form>

                    <a href="{{ route('dokter.index') }}" class="link-kembali">kembali</a>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>