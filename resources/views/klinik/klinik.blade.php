<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Klinik - Mockup (CSS biasa)</title>
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
  <a href="{{ route('klinik.index') }}"class="active">Data Klinik</a>
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
                    <h2>Daftar Klinik Tersedia</h2>
                   
    <a href="{{ route('klinik.sampah') }}" class="btn-secondary" style="text-decoration:none;">Tempat Sampah</a>


                </div>
              <a href="{{ route('klinik.create') }}" class="btn-secondary" style="text-decoration:none;">+ Tambah Klinik</a>  
                <div class="grid-klinik">
      @forelse ($kliniks as $klinik)
          <a href="{{ route('klinik.show', $klinik->id) }}" class="klinik-item">
              <div class="icon-placeholder">
                  
                      <div class="icon-placeholder" style="width:40px;height:40px;">
                                <img src="{{ asset('images/clinic.png') }}" alt="Ikon klinik"
                                    style="width:100%; height:100%; object-fit:contain;">
                            </div>
                
              </div>
              <span>{{ $klinik->nama }}</span>
          </a>
      @empty
          <p style="color:#999;">Belum ada klinik tersedia.</p>
      @endforelse
  </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
