<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KlinikController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\LayananController;


Route::get('/', [JadwalController::class, 'index'])->name('jadwal.index');

// ================= JADWAL PRAKTIK =================
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
Route::get('/jadwal/generate/{tanggal}', [JadwalController::class, 'generateHarian'])->name('jadwal.generate');
Route::post('/jadwal/generate/{tanggal}', [JadwalController::class, 'simpanHarian'])->name('jadwal.simpanHarian');
Route::post('/jadwal/poster/{tanggal}', [JadwalController::class, 'poster'])->name('jadwal.poster');
Route::get('/jadwal/{hari}/download', [JadwalController::class, 'downloadTemplate'])->name('jadwal.download');
Route::get('/jadwal/{hari}', [JadwalController::class, 'template'])->name('jadwal.template');

// ================= DATA KLINIK =================
Route::get('/klinik', [KlinikController::class, 'index'])->name('klinik.index');
Route::get('/klinik/create', [KlinikController::class, 'create'])->name('klinik.create');
Route::post('/klinik', [KlinikController::class, 'store'])->name('klinik.store');
Route::get('/klinik/sampah', [KlinikController::class, 'sampah'])->name('klinik.sampah');
Route::patch('/klinik/{id}/restore', [KlinikController::class, 'restore'])->name('klinik.restore');
Route::delete('/klinik/{id}/hapus-permanen', [KlinikController::class, 'hapusPermanen'])->name('klinik.hapusPermanen');
Route::get('/klinik/{klinik}', [KlinikController::class, 'show'])->name('klinik.show');
Route::get('/klinik/{klinik}/edit', [KlinikController::class, 'edit'])->name('klinik.edit');
Route::delete('/klinik/{klinik}', [KlinikController::class, 'destroy'])->name('klinik.destroy');
Route::post('/klinik/{klinik}/sesi', [KlinikController::class, 'storeSesi'])->name('klinik.sesi.store');
Route::put('/klinik/{klinik}/sesi/{sesi}', [KlinikController::class, 'updateSesi'])->name('klinik.sesi.update');
Route::delete('/klinik/{klinik}/sesi/{sesi}', [KlinikController::class, 'destroySesi'])->name('klinik.sesi.destroy');

// ================= DATA DOKTER =================
Route::get('/dokter/sampah', [DokterController::class, 'sampah'])->name('dokter.sampah');
Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
Route::get('/dokter/create', [DokterController::class, 'create'])->name('dokter.create');   
Route::post('/dokter', [DokterController::class, 'store'])->name('dokter.store');           
Route::get('/dokter/{dokter}', [DokterController::class, 'show'])->name('dokter.show');
Route::get('/dokter/{dokter}/edit', [DokterController::class, 'edit'])->name('dokter.edit');
Route::put('/dokter/{dokter}', [DokterController::class, 'update'])->name('dokter.update');
Route::delete('/dokter/{dokter}', [DokterController::class, 'destroy'])->name('dokter.destroy');

// ================= INFO LAYANAN =================
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
Route::get('/layanan/create', [LayananController::class, 'create'])->name('layanan.create');
Route::post('/layanan', [LayananController::class, 'store'])->name('layanan.store');
// Tempat sampah: HARUS di atas /layanan/{layanan}, kalau tidak "sampah" dianggap id
Route::get('/layanan/sampah', [LayananController::class, 'sampah'])->name('layanan.sampah');
Route::patch('/layanan/{id}/restore', [LayananController::class, 'restore'])->name('layanan.restore');
Route::delete('/layanan/{id}/hapus-permanen', [LayananController::class, 'hapusPermanen'])->name('layanan.hapusPermanen');
Route::get('/layanan/{layanan}/edit', [LayananController::class, 'edit'])->name('layanan.edit');
Route::put('/layanan/{layanan}', [LayananController::class, 'update'])->name('layanan.update');
Route::delete('/layanan/{layanan}', [LayananController::class, 'destroy'])->name('layanan.destroy');
Route::get('/layanan/{layanan}', [LayananController::class, 'show'])->name('layanan.show');