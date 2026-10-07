<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwal Praktik - Mockup</title>
<link rel="stylesheet" href="{{ asset('jadwal-style.css') }}">
</head>
<body>
<div class="app-wrapper">
    <aside class="sidebar">
        <div>
            <div class="sidebar-header">
                <p>RUMAH SAKIT ISLAM<br>FATIMAH CILACAP</p>
            </div>
            <nav class="sidebar-menu">
                <a href="{{ route('jadwal.index') }}" class="active">Jadwal Praktik</a>
                <a href="{{ route('klinik.index') }}">Data Klinik</a>
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
                <p>Jadwal Praktik</p>
            </div>
            <div class="header-actions">
                <img src="{{ asset('images/logo-rsi.png') }}" alt="Logo RSI" class="header-logo">
                <img src="{{ asset('images/akreditasi.png') }}" alt="Akreditasi" class="header-logo">
            </div>
        </header>

        <main class="page-body">
            <div class="card">
                <div class="card-header-row">
                    <h2>Jadwal Praktik</h2>
                    <button class="btn-secondary">Bantuan</button>
                </div>

 <div style="margin-bottom: 24px;">
      <a href="{{ route('jadwal.generate', now()->format('Y-m-d')) }}"
  font-weight:600; font-size:14px; text-decoration:none;">
          Generate Jadwal Hari Ini {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
      </a>
  </div>

{{-- Pilih tanggal lain --}}
<div class="kotak-tanggal">
    <p class="judul">Atau pilih tanggal lain:</p>
    <form id="form-tanggal">
        <input type="date" id="input-tanggal" value="{{ now()->format('Y-m-d') }}">
        <button type="submit" class="btn-generate-tgl">Generate Tanggal Ini</button>
    </form>
</div>

                {{-- Lihat template per hari --}}
                <div>
                    <p style="font-size:13px; color:#64748b; margin:0 0 10px;">Lihat template jadwal per hari:</p>
                    <div class="grid-hari">
                        <a href="{{ route('jadwal.template', 'senin') }}" class="hari-item">Senin</a>
                        <a href="{{ route('jadwal.template', 'selasa') }}" class="hari-item">Selasa</a>
                        <a href="{{ route('jadwal.template', 'rabu') }}" class="hari-item">Rabu</a>
                        <a href="{{ route('jadwal.template', 'kamis') }}" class="hari-item">Kamis</a>
                        <a href="{{ route('jadwal.template', 'jumat') }}" class="hari-item">Jum'at</a>
                        <a href="{{ route('jadwal.template', 'sabtu') }}" class="hari-item">Sabtu</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.getElementById('form-tanggal').addEventListener('submit', function(e) {
    e.preventDefault();
    const tanggal = document.getElementById('input-tanggal').value;
    if (tanggal) {
        window.location.href = '/jadwal/generate/' + tanggal;
    }
});
</script>
</body>
</html>
