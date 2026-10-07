<!DOCTYPE html>
  <html lang="id">
  <head>
  <meta charset="UTF-8">
  <title>Edit Info Dokter</title>
  <link rel="stylesheet" href="{{ asset('dokter-style.css') }}">
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
                  <a href="{{ route('dokter.index') }}" class="active">Data Dokter</a>
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
                  <p>Data Dokter</p>
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
                          <div class="icon-placeholder" style="width:40px;height:40px;">
                                <img src="{{ asset('images/doktericon.png') }}" alt="Ikon dokter"
                                    style="width:100%; height:100%; object-fit:contain;">
                            </div>
                          <h2>{{ $dokter->nama_lengkap }}</h2>
                      </div>
                  </div>

                  <form action="{{ route('dokter.update', $dokter->id) }}" method="POST">
                      @csrf
                      @method('PUT')

                      <div class="info-row baris-ganjil">
                          <span class="info-label">Nama Dokter</span>
                          <span class="info-value">
                              <input type="text" name="nama" value="{{ old('nama', $dokter->nama) }}" required>
                          </span>
                      </div>

                      <div class="info-row baris-genap">
                          <span class="info-label">Gelar Pendidikan</span>
                          <span class="info-value">
                              <input type="text" name="gelar" value="{{ old('gelar', $dokter->gelar) }}"
  placeholder="Contoh: Sp.PD">
                          </span>
                      </div>

                      <div class="info-row baris-ganjil" style="align-items:flex-start;">
    <span class="info-label" style="padding-top:4px;">Klinik</span>
    <span class="info-value">
        <div class="checkbox-klinik-list">
            @forelse($kliniks->whereIn('id', $klinikTerdaftar) as $klinik)
                <span class="checkbox-klinik-item">{{ $klinik->nama }}</span>
            @empty
                <span style="color:#94a3b8;">Belum terdaftar di klinik manapun.</span>
            @endforelse
        </div>
        <p style="font-size:12px; color:#94a3b8; margin:6px 0 0;">
            Untuk mengganti klinik, pergi ke page Data Klinik pada sesi Jadwal Praktik ini.
        </p>
    </span>
</div>
                             
                          </span>
                      </div>

                      <div class="footer-aksi">
                          <button type="submit" class="btn-primary">Simpan edit</button>
                          <div class="footer-aksi-kanan">
                              <a href="{{ route('dokter.show', $dokter->id) }}" class="btn-secondary"style="text-decoration:none;">Batal edit</a>
                              <a href="{{ route('dokter.index') }}" class="link-kembali">kembali</a>
                          </div>
                      </div>
                  </form>
              </div>
          </main>
      </div>
  </div>
  </body>
  </html>