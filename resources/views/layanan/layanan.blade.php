<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Info Layanan</title>
<link rel="stylesheet" href="{{ asset('layanan-style.css') }}">
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
                <a href="{{ route('dokter.index') }}">Data Dokter</a>
                <a href="{{ route('layanan.index') }}" class="active">Info Layanan</a>
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
                <p>Info Layanan</p>
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
            @if(session('error'))
                <div class="card" style="color:#dc2626; padding:12px; margin-bottom:12px;">{{ session('error') }}</div>
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
                    <h2>Kontrol Promosi PKRS</h2>
                    <div style="display:flex; gap:8px;">
                        <a href="{{ route('layanan.create') }}" class="btn-secondary" style="text-decoration:none;">+ Tambah Layanan</a>
                        <a href="{{ route('layanan.sampah') }}" class="btn-secondary" style="text-decoration:none;">Tempat Sampah</a>
                    </div>
                </div>

                @forelse($layanans as $layanan)
                    <a href="{{ route('layanan.show', $layanan->id) }}"
                       class="info-row {{ $loop->odd ? 'baris-ganjil' : 'baris-genap' }}"
                       style="text-decoration:none; color:inherit; justify-content:space-between;">
                        {{ $layanan->nama }} <span style="color:#94a3b8;">&rsaquo;</span>
                    </a>
                @empty
                    <p style="padding:16px; color:#94a3b8;">Belum ada layanan. Klik "+ Tambah Layanan".</p>
                @endforelse
            </div>
        </main>
    </div>
</div>
</body>
</html>
