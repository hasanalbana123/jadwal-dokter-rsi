<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Klinik Baru - Mockup (CSS biasa)</title>
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
                <a href="{{ route('jadwal.index') }}">Jadwal Praktik</a>
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
            <div class="card">
                <div class="card-header-row">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="icon-placeholder" style="width:40px;height:40px;">icon.img</div>
                        <h2>Tambah Klinik Baru</h2>
                    </div>
                </div>

                <form action="{{ route('klinik.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="info-row baris-genap">
                        <span class="info-label">Nama Klinik</span>
                        <span class="info-value">
                            <input type="text" name="nama" placeholder="Contoh: Klinik Penyakit Dalam" required>
                        </span>
                    </div>

                    <div class="footer-aksi">
                        <button type="submit" class="btn-primary">Simpan klinik</button>
                        <div class="footer-aksi-kanan">
                            <a href="{{ route('klinik.index') }}" class="btn-secondary" style="text-decoration:none;">Batal</a>
                            <a href="{{ route('klinik.index') }}" class="link-kembali">kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
</body>
</html>
