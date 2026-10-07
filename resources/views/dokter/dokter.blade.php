<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Dokter</title>
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
            <div class="card">
                <div class="card-header-row">
                    <h2>Daftar Dokter Tersedia ({{ $dokters->count() }})</h2>
                    
                    <a href="{{ route('dokter.sampah') }}" class="btn-secondary" style="text-decoration:none;">Tempat Sampah</a>
                </div>
                    <a href="{{ route('dokter.create') }}" class="btn-secondary" style="text-decoration:none;">+ Tambah Dokter</a>
                <div class="grid-klinik">
                    @forelse ($dokters as $dokter)
                        <a href="{{ route('dokter.show', $dokter->id) }}" class="klinik-item">
                            <div class="icon-placeholder" style="width:40px;height:40px;">
                                <img src="{{ asset('images/doktericon.png') }}" alt="Ikon dokter"
                                    style="width:100%; height:100%; object-fit:contain;">
                            </div>
                            <span>{{ $dokter->nama_lengkap }}</span>
                        </a>
                    @empty
                        <p style="grid-column: 1 / -1; color:#94a3b8; font-size:14px;">
                            Belum ada data dokter. Klik "+ Tambah Dokter" untuk menambahkan.
                        </p>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>