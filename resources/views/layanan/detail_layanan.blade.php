<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Detail Layanan</title>
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
                    <h2>{{ $layanan->nama }}</h2>
                    <a href="{{ route('layanan.edit', $layanan->id) }}" class="btn-secondary" style="text-decoration:none;">Edit layanan</a>
                </div>

                @forelse($layanan->daftarKontak as $k)
                    <div class="kontak-row">
                        <span class="kontak-label">{{ $k->label }}</span>
                        <span>{{ $k->value }}</span>
                    </div>
                @empty
                    <p style="font-size:13px; color:#94a3b8; margin-bottom:8px;">
                        Layanan ini tidak punya kontak, cukup icon saja.
                    </p>
                @endforelse

                <div style="margin-top:12px;">
                    <span style="font-size:13px; color:#64748b;">Icon:</span>
                    <div class="icon-upload-box" style="padding:0; overflow:hidden;">
                        @if($layanan->icon)
                            <img src="{{ asset('storage/' . $layanan->icon) }}" alt="{{ $layanan->nama }}" style="width:100%; height:100%; object-fit:contain;">
                        @else
                            belum ada
                        @endif
                    </div>
                </div>

                <div class="footer-aksi">
                    {{-- Soft delete: pindah ke tempat sampah (hapus permanen ada di halaman tempat sampah) --}}
                    <form action="{{ route('layanan.destroy', $layanan->id) }}" method="POST"
                          onsubmit="return confirm('Pindahkan {{ $layanan->nama }} ke tempat sampah? Data bisa dipulihkan lagi.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-secondary" style="color:#f59e0b;">Nonaktifkan</button>
                    </form>
                    <a href="{{ route('layanan.index') }}" class="link-kembali">kembali</a>
                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>