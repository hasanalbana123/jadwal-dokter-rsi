<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tempat Sampah Layanan</title>
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
                    <h2>Tempat Sampah Layanan</h2>
                    <a href="{{ route('layanan.index') }}" class="link-kembali">kembali</a>
                </div>

                @forelse($layanans as $layanan)
                    <div class="info-row {{ $loop->odd ? 'baris-ganjil' : 'baris-genap' }}">
                        <span class="info-label" style="width:auto; flex:1;">
                            {{ $layanan->nama }}
                            <span style="display:block; font-weight:400; font-size:12px; color:#94a3b8;">
                                Dihapus {{ $layanan->deleted_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}
                            </span>
                        </span>

                        <div style="display:flex; gap:8px; margin-left:auto;">
                            <form action="{{ route('layanan.restore', $layanan->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn-secondary" style="color:#16a34a;">Pulihkan</button>
                            </form>

                            <form action="{{ route('layanan.hapusPermanen', $layanan->id) }}" method="POST"
                                  onsubmit="return confirm('HAPUS PERMANEN {{ $layanan->nama }}? Tidak bisa dikembalikan!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-secondary" style="color:#ef4444;">Hapus Permanen</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p style="padding:16px; color:#94a3b8;">Tempat sampah kosong.</p>
                @endforelse
            </div>
        </main>
    </div>
</div>
</body>
</html>
