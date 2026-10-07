<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwal {{ ucfirst($hari) }} (Template)</title>
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
            <div style="margin-bottom:12px; display:flex; align-items:center; justify-content:space-between;">
                <a href="{{ route('jadwal.index') }}" class="link-kembali">&larr; Jadwal Praktik</a>
                <a href="{{ route('jadwal.download', $hari) }}" class="btn-generate-pink">⬇ Unduh Jadwal {{ ucfirst($hari) }}</a>
            </div>

            <div class="card">
                <div class="poster-header">
                    <p class="subjudul">RUMAH SAKIT ISLAM FATIMAH CILACAP</p>
                    <h2>JADWAL PRAKTIK DOKTER</h2>
                    <p class="nama-hari">{{ strtoupper($hari) }}</p>
                </div>

                <div class="grid-klinik-jadwal">
                    @forelse ($sesiPerKlinik as $klinikId => $sesiList)
                        @php $klinik = $sesiList->first()->klinik; @endphp
                        <div class="klinik-box">
                            <div class="klinik-box-header">{{ strtoupper($klinik->nama) }}</div>

                            @foreach ($sesiList as $sesi)
                                <div class="sesi-row">
                                    <span>
                                        @foreach ($sesi->dokter as $dokter)
                                            {{ $dokter->nama_lengkap }}@if (!$loop->last), @endif
                                        @endforeach
                                        @if ($sesi->keterangan_sesi)
                                            <span class="ket-sesi">{{ $sesi->keterangan_sesi }}</span>
                                        @endif
                                    </span>
                                    <span class="jam">{{ $sesi->jam_label }}</span>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p style="color:#999;">Belum ada jadwal untuk hari {{ ucfirst($hari) }}.</p>
                    @endforelse
                </div>

                <div class="footer-aksi">
                    <span></span>
                    <a href="{{ route('jadwal.index') }}" class="link-kembali">kembali</a>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
