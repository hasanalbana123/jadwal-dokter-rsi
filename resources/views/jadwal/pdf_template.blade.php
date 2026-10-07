<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; font-size: 11px; color: #000; }

    .header {
        background-color: #0f766e;
        color: #fff;
        text-align: center;
        padding: 12px;
        margin-bottom: 14px;
        border-radius: 6px;
    }
    .header .subjudul { font-size: 9px; letter-spacing: 0.05em; }
    .header h2 { font-size: 15px; margin: 3px 0; }
    .header .nama-hari { font-size: 12px; font-weight: bold; text-transform: uppercase; }

    .grid {
        width: 100%;
        border-collapse: separate;
        border-spacing: 6px;
    }
    .grid td { width: 50%; vertical-align: top; }

    .klinik-box {
        border: 1px solid #ccc;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 6px;
    }
    .klinik-box-header {
        background-color: #0d9488;
        color: #fff;
        font-size: 9px;
        font-weight: bold;
        padding: 4px 8px;
    }
    .sesi-row {
        display: block;
        padding: 4px 8px;
        border-top: 1px solid #f0f0f0;
        font-size: 10px;
    }
    .sesi-row .nama { float: left; max-width: 72%; }
    .sesi-row .jam  { float: right; color: #444; white-space: nowrap; }
    .sesi-row .ket  { display: block; font-size: 9px; color: #888; clear: both; }
    .clearfix::after { content: ''; display: table; clear: both; }
</style>
</head>
<body>

<div class="header">
    <p class="subjudul">RUMAH SAKIT ISLAM FATIMAH CILACAP</p>
    <h2>JADWAL PRAKTIK DOKTER</h2>
    <p class="nama-hari">{{ strtoupper($hari) }}</p>
</div>

{{-- Split sesiPerKlinik jadi 2 kolom --}}
@php
    $items     = $sesiPerKlinik->values();
    $half      = ceil($items->count() / 2);
    $kolom_kiri  = $items->slice(0, $half);
    $kolom_kanan = $items->slice($half);
@endphp

<table class="grid">
    <tr>
        {{-- Kolom kiri --}}
        <td>
            @foreach ($kolom_kiri as $sesiList)
                @php $klinik = $sesiList->first()->klinik; @endphp
                <div class="klinik-box">
                    <div class="klinik-box-header">{{ strtoupper($klinik->nama) }}</div>
                    @foreach ($sesiList as $sesi)
                        <div class="sesi-row clearfix">
                            <span class="nama">
                                @foreach ($sesi->dokter as $d)
                                    {{ $d->nama_lengkap }}@if (!$loop->last), @endif
                                @endforeach
                            </span>
                            <span class="jam">{{ $sesi->jam_label }}</span>
                            @if ($sesi->keterangan_sesi)
                                <span class="ket">{{ $sesi->keterangan_sesi }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </td>

        {{-- Kolom kanan --}}
        <td>
            @foreach ($kolom_kanan as $sesiList)
                @php $klinik = $sesiList->first()->klinik; @endphp
                <div class="klinik-box">
                    <div class="klinik-box-header">{{ strtoupper($klinik->nama) }}</div>
                    @foreach ($sesiList as $sesi)
                        <div class="sesi-row clearfix">
                            <span class="nama">
                                @foreach ($sesi->dokter as $d)
                                    {{ $d->nama_lengkap }}@if (!$loop->last), @endif
                                @endforeach
                            </span>
                            <span class="jam">{{ $sesi->jam_label }}</span>
                            @if ($sesi->keterangan_sesi)
                                <span class="ket">{{ $sesi->keterangan_sesi }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </td>
    </tr>
</table>

</body>
</html>
