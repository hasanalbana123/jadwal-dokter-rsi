<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Generate Jadwal {{ ucfirst($hari ?? 'Hari Ini') }}</title>
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
            <div class="card-header-row" style="margin-bottom:16px;">
                <div>
                    <h2 style="margin:0;">Generate Jadwal</h2>
                    <span style="font-size:13px; color:#64748b;">
                        {{ ucfirst($hari ?? '-') }},
                        {{ isset($tanggal) ? \Carbon\Carbon::parse($tanggal)->locale('id')->isoFormat('D MMMM YYYY') : now()->locale('id')->isoFormat('D MMMM YYYY') }}
                    </span>
                </div>
                <a href="{{ route('jadwal.index') }}" class="link-kembali">&larr; Kembali</a>
            </div>

            <div class="card">
                <p style="font-size:13px; color:#64748b; margin:0 0 16px;">
                    Sesuaikan dokter yang praktik hari ini. Pilih dokter pengganti atau tandai "Tidak Praktik" jika berhalangan.
                </p>

                <form method="POST" action="{{ route('jadwal.poster', $tanggal ?? now()->format('Y-m-d')) }}">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal ?? now()->format('Y-m-d') }}">
                <input type="hidden" name="hari" value="{{ $hari ?? '' }}">

                @forelse ($sesiPerKlinik as $klinikId => $sesiList)
                    @php $klinik = $sesiList->first()->klinik; @endphp
                    <p class="nama-klinik-generate">{{ strtoupper($klinik->nama) }}</p>

                    @foreach ($sesiList as $sesi)
                        @php
                            // nama dokter template untuk ditampilkan sebagai opsi default
                            $namaDokterTemplate = $sesi->dokter->map(fn($d) => $d->nama_lengkap)->join(', ');
                            $labelSesi = $namaDokterTemplate;
                            if ($sesi->keterangan_sesi) {
                                $labelSesi .= ' — ' . $sesi->keterangan_sesi;
                            }
                        @endphp
                        <div class="baris-dropdown">
                            <select name="sesi[{{ $sesi->id }}]">
                                <option value="{{ $labelSesi }}" selected>{{ $labelSesi }}</option>
                                @foreach ($semuaDokter as $dokterPilihan)
                                    @if ($sesi->dokter->pluck('id')->doesntContain($dokterPilihan->id))
                                        <option value="{{ $dokterPilihan->nama_lengkap }}">
                                            {{ $dokterPilihan->nama_lengkap }}
                                        </option>
                                    @endif
                                @endforeach
                                <option value="TIDAK_PRAKTIK">— Tidak Praktik —</option>
                            </select>
                            <span class="jam">{{ $sesi->jam_label }}</span>
                        </div>
                    @endforeach
                @empty
                    <p style="color:#999;">Belum ada template jadwal untuk hari {{ ucfirst($hari) }}.</p>
                @endforelse

                @if ($sesiPerKlinik->isNotEmpty())
                <div class="footer-aksi" style="margin-top:32px;">
                    <a href="{{ route('jadwal.index') }}" class="link-kembali">kembali</a>
                    <button type="submit" class="btn-generate-pink">Generate & Lihat Poster ➜</button>
                </div>
                @endif
                </form>
            </div>
        </main>
    </div>
</div>
</body>
</html>
