<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Poster Jadwal {{ $hari }} - {{ $tanggal_label }}</title>
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #e5e5e5;
        padding: 20px 0 40px;
    }

    /* ===== TOOLBAR (di luar poster, tidak ikut print) ===== */
    .toolbar {
        width: 720px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .toolbar a {
        font-size: 13px;
        color: #475569;
        text-decoration: none;
    }
    .toolbar a:hover { text-decoration: underline; }
    .toolbar-btns { display: flex; gap: 10px; }
    .btn-download {
        background: #0d9488;
        color: #fff;
        border: none;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .btn-download:hover { background: #0f766e; }
    .btn-print {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
    .btn-print:hover { background: #e2e8f0; }

    /* ===== POSTER ===== */
    .poster {
        width: 720px;
        margin: 0 auto;
        background: linear-gradient(180deg, #0e5c5c 0%, #0a4a4a 100%);
        color: #fff;
        overflow: hidden;
        border-radius: 4px;
    }

    /* HEADER */
    .header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 16px 20px 8px 20px;
        gap: 12px;
    }
    .header-left { display: flex; align-items: center; gap: 12px; }
    .logo-circle {
        width: 54px; height: 54px; border-radius: 50%;
        background: #fff; display: flex; align-items: center;
        justify-content: center; font-size: 9px; color: #0e5c5c;
        font-weight: bold; flex-shrink: 0; text-align: center; line-height: 1.2;
    }
    .header-title h1 {
        font-size: 11px; letter-spacing: 1px;
        color: #d7efe9; font-weight: 500; margin-bottom: 2px;
    }
    .header-title h2 {
        font-size: 24px; font-weight: 800; letter-spacing: 0.5px;
        text-shadow: 1px 2px 3px rgba(0,0,0,0.3); line-height: 1.1;
    }
    .header-title p {
        font-size: 11px; letter-spacing: 0.5px;
        color: #d7efe9; margin-top: 3px;
    }
    .header-right {
        display: flex; flex-direction: column;
        align-items: flex-end; gap: 6px;
    }
    .badge-akreditasi {
        width: 48px; height: 48px; border-radius: 50%;
        background: #fff; display: flex; align-items: center;
        justify-content: center; font-size: 7px; color: #0e5c5c;
        text-align: center; flex-shrink: 0;
    }
    .day-badge {
        background: #fff; color: #0e5c5c;
        font-size: 20px; font-weight: 800; letter-spacing: 1px;
        padding: 6px 20px; border-radius: 4px; text-align: center;
    }
    .date-badge {
        background: rgba(255,255,255,0.15); color: #fff;
        font-size: 11px; font-weight: 600;
        padding: 4px 12px; border-radius: 4px; text-align: center;
    }

    /* BODY GRID — CSS columns agar otomatis balance */
    .body-grid {
        columns: 2;
        column-gap: 10px;
        padding: 10px 14px 4px;
    }
    .klinik-block {
        break-inside: avoid;
        margin-bottom: 8px;
    }
    .klinik-title {
        background: #fff; color: #0e5c5c;
        font-size: 11px; font-weight: 700;
        letter-spacing: 0.3px; padding: 4px 8px;
        border-radius: 2px 2px 0 0;
    }
    .dokter-row {
        display: flex; justify-content: space-between;
        align-items: center;
        background: rgba(255,255,255,0.08);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding: 4px 8px; font-size: 11px; gap: 6px;
    }
    .dokter-row:last-child { border-radius: 0 0 2px 2px; border-bottom: none; }
    .dokter-nama { flex: 1; line-height: 1.3; }
    .dokter-sesi {
        display: block; font-size: 9px; color: #a7d9d0;
    }
    .dokter-jam {
        white-space: nowrap; font-weight: 600;
        background: rgba(0,0,0,0.2); padding: 2px 5px;
        border-radius: 2px; font-size: 10px; flex-shrink: 0;
    }
    .tidak-praktik {
        color: #fca5a5; font-style: italic; font-size: 10px;
    }

    /* FOOTER */
    .booking-bar {
        background: #f7d61e; color: #333;
        font-size: 11px; font-weight: 700;
        text-align: center; padding: 7px;
        margin: 10px 14px 6px; border-radius: 3px;
    }
    .footer-grid {
        display: grid; grid-template-columns: 1fr 1fr 1fr;
        gap: 8px; padding: 0 14px 8px;
    }
    .footer-box {
        background: rgba(255,255,255,0.1);
        border-radius: 4px; padding: 7px 8px;
        font-size: 9.5px; line-height: 1.5;
    }
    .footer-box b { display: block; font-size: 10px; margin-bottom: 2px; }
    .footer-note {
        text-align: center; font-size: 9px;
        color: #d7efe9; padding: 0 20px 6px;
    }
    .footer-social {
        text-align: center; font-size: 9.5px;
        padding: 6px 16px 12px;
        border-top: 1px solid rgba(255,255,255,0.15);
    }

    /* PRINT */
    @media print {
        body { background: none; padding: 0; }
        .toolbar { display: none; }
        .poster { margin: 0; border-radius: 0; width: 100%; }
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
    }
</style>
</head>
<body>

<div class="toolbar">
    <a href="{{ url()->previous() }}">&larr; Kembali edit jadwal</a>
    <div class="toolbar-btns">
        <button class="btn-print" onclick="window.print()">🖨️ Print</button>
        <button class="btn-download" onclick="downloadPoster()">⬇️ Download Gambar</button>
    </div>
</div>

<div class="poster" id="poster">

    {{-- HEADER --}}
    <div class="header">
        <div class="header-left">
            <div class="logo-circle">
                <img src="{{ asset('images/logo-rsi.png') }}" alt="Logo RSI" style="width:100%; height:100%; object-fit:contain; border-radius:50%;">
            </div>
            <div class="header-title">
                <h1>JADWAL DOKTER</h1>
                <h2>JADWAL<br>PRAKTIK DOKTER</h2>
                <p>RS. ISLAM FATIMAH CILACAP</p>
            </div>
        </div>
        <div class="header-right">
            <div class="badge-akreditasi">
                <img src="{{ asset('images/akreditasi.png') }}" alt="Akreditasi KARS" style="width:100%; height:100%; object-fit:contain; border-radius:50%;">
            </div>
            <div class="day-badge">{{ $hari }}</div>
            <div class="date-badge">{{ $tanggal_label }}</div>
        </div>
    </div>

    {{-- BODY --}}
   <div class="body-grid">
      @foreach ($sesiData as $klinikId => $sesiList)
          @php $klinik = $sesiList->first()->klinik; @endphp
          <div class="klinik-block">
              <div class="klinik-title">{{ strtoupper($klinik->nama) }}</div>
              @foreach ($sesiList as $sesi)
                  @php
                      $namaTampil = $sesiDipilih[$sesi->id] ?? null;
                  @endphp
                  @if ($namaTampil === 'TIDAK_PRAKTIK')
                      <div class="dokter-row">
                            <div class="dokter-nama tidak-praktik">Tidak Praktik ({{ $sesi->dokter->map(fn($d) => $d->nama_lengkap)->join(', ')}})</div>
                            <div class="dokter-jam">{{ $sesi->jam_label }}</div>
                      </div>
                  @elseif ($namaTampil)
                      <div class="dokter-row">
                          <div class="dokter-nama">{{ $namaTampil }}</div>
                          <div class="dokter-jam">{{ $sesi->jam_label }}</div>
                      </div>
                  @endif
              @endforeach
          </div>
      @endforeach
  </div>
    {{-- FOOTER --}}
@php
    // Layanan aktif (yang di-"Nonaktifkan" otomatis tidak ikut tampil di poster)
    $layananAktif  = \App\Models\LayananTambahan::with('daftarKontak')->orderBy('urutan_tampil')->get();

    // Sosial media tampil di baris bawah, layanan lain tampil sebagai kotak
    $layananSosmed = $layananAktif->first(fn ($l) => str_contains(strtolower($l->nama), 'sosial'));
    $layananBox    = $layananAktif->filter(
        fn ($l) => $l->id !== optional($layananSosmed)->id && $l->daftarKontak->isNotEmpty()
    );

    $labelTelepon = ['whatsapp', 'wa', 'telepon', 'telp', 'hp'];
    $emojiSosmed  = ['facebook' => '📘', 'instagram' => '📷', 'youtube' => '▶️', 'playstore' => '🌐', 'whatsapp' => '💬'];
@endphp

<div class="booking-bar">Layanan booking mulai dari H-14 s.d hari H</div>

<div class="footer-grid">
    @foreach ($layananBox as $layanan)
        <div class="footer-box">
            <b>{{ strtoupper($layanan->nama) }}</b>
            @foreach ($layanan->daftarKontak as $k)
                @if (in_array(strtolower($k->label), $labelTelepon))
                    ☎ {{ $k->value }}<br>
                @else
                    {{ $k->label }}: {{ $k->value }}<br>
                @endif
            @endforeach
        </div>
    @endforeach
</div>

<div class="footer-note">
    Jadwal sewaktu-waktu dapat berubah, perubahan jadwal akan kami publikasikan melalui media sosial kami:
</div>

@if ($layananSosmed)
    <div class="footer-social">
        @foreach ($layananSosmed->daftarKontak as $k)
            {{ $emojiSosmed[strtolower($k->label)] ?? '' }} {{ $k->value }}@if (!$loop->last) &nbsp;|&nbsp; @endif
        @endforeach
    </div>
@endif

</div>{{-- end poster --}}

<script>
function downloadPoster() {
    // Untuk mockup: pakai browser print ke PDF / screenshot
    // Nanti bisa diganti dengan html2canvas atau Browsershot
    alert('Untuk download gambar, gunakan tombol Print → Save as PDF, atau Ctrl+P.\n\nFitur download otomatis akan aktif saat integrasi dengan library html2canvas.');
}
</script>

</body>
</html>
