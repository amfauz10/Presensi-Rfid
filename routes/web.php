<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\AlumniController;

/*
|--------------------------------------------------------------------------
| HALAMAN OTENTIKASI (LOGIN & GUEST ACCESS)
|--------------------------------------------------------------------------
| Diperketat dengan middleware 'guest' agar user yang sudah login tidak 
| bisa kembali ke halaman login sebelum mereka logout.
|
*/
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    // Dibatasi 5 percobaan per menit (per kombinasi IP + email) untuk mencegah brute-force password
    Route::post('/proses-login', [AuthController::class, 'login_proses'])
        ->middleware('throttle:5,1');
});

/*
|--------------------------------------------------------------------------
| ROUTE PRIVATE: DIASES OLEH ADMIN & GURU
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Halaman Dashboard Utama Sistem
    Route::get('/dashboard', [PresensiController::class, 'dashboard'])
        ->name('dashboard');

    // Laporan Matriks Presensi Bulanan
    Route::get('/laporan', [PresensiController::class, 'laporan'])
        ->name('laporan.index');

    // Export Rekap Laporan Bulanan ke Excel
    Route::get('/laporan/export', [PresensiController::class, 'exportLaporan'])
        ->name('laporan.export');

    // Form Input & Update Presensi Manual Berbasis Kelas
    Route::get('/presensi/manual', [PresensiController::class, 'inputManual'])
        ->name('presensi.manual');

    // Endpoint Simpan Presensi Manual
    Route::post('/presensi/manual/simpan', [PresensiController::class, 'storeManual'])
        ->name('presensi.manual.simpan');

    Route::post('/presensi/store', [PresensiController::class, 'storeManual'])
        ->name('presensi.store');

    // Proses Keluar dari Sistem
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| ROUTE KHUSUS: HANYA DIAKSES OLEH ROLE ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Modul Kelola Pengguna (User Management)
    |--------------------------------------------------------------------------
    */
    Route::resource('users', UserController::class);

    /*
    |--------------------------------------------------------------------------
    | Modul Kelas & Manajemen Data Siswa via Excel (BAGIAN IMPORT TETAP AMAN)
    |--------------------------------------------------------------------------
    */
    Route::get('/kelas', [SiswaController::class, 'index'])
        ->name('kelas.index');

    // Endpoint utama untuk eksekusi import Excel Anda
    Route::post('/kelas/import', [SiswaController::class, 'importExcel'])
        ->name('siswa.import');

    Route::get('/kelas/export', [SiswaController::class, 'exportExcel'])
        ->name('kelas.export');

    Route::get('/kelas/{id}', [SiswaController::class, 'show'])
        ->name('kelas.show');

    /*
    |--------------------------------------------------------------------------
    | Modul CRUD Operasional Siswa 
    |--------------------------------------------------------------------------
    */
    Route::get('/siswa/tambah', [SiswaController::class, 'tambah'])
        ->name('siswa.tambah');

    Route::post('/siswa/simpan', [SiswaController::class, 'simpan'])
        ->name('siswa.simpan');

    Route::get('/siswa/edit/{id}', [SiswaController::class, 'edit'])
        ->name('siswa.edit');

    Route::put('/siswa/update/{id}', [SiswaController::class, 'update'])
        ->name('siswa.update');

    // PERUBAHAN KEAMANAN: Mengubah GET menjadi DELETE untuk mencegah manipulasi URL
    Route::delete('/siswa/hapus/{id}', [SiswaController::class, 'hapus'])
        ->name('siswa.hapus');

    Route::post('/siswa/hapus-semua', [SiswaController::class, 'hapusSemuaMassa'])
        ->name('siswa.hapus_semua');

    /*
    |--------------------------------------------------------------------------
    | Modul Terminal Gate RFID (USB PnP Automation Interface)
    |--------------------------------------------------------------------------
    */
    Route::get('/terminal-presensi', function () {
        return view('presensi.terminal');
    })->name('terminal.index');

    // Endpoint AJAX / Form Event listener saat Kartu RFID di-tap
    Route::post('/proses-tap-web', [PresensiController::class, 'tapRFIDWeb'])
        ->name('proses.tap');

   /*
    |--------------------------------------------------------------------------
    | Modul Log & Manajemen Riwayat Notifikasi WhatsApp Gateway
    |--------------------------------------------------------------------------
    */
    Route::get('/log-notifikasi', [PresensiController::class, 'logNotifikasi'])
        ->name('log.notifikasi');

    // POSISI URUTAN DIPINDAH KE SINI (DI ATAS {id}) AGAR TIDAK TERCAMPUR PARAMETER DINAMIS
    Route::delete('/log-notifikasi/truncate', [PresensiController::class, 'truncateNotifikasi'])
        ->name('log.notifikasi.truncate');

    Route::delete('/log-notifikasi/{id}', [PresensiController::class, 'destroyLog'])
        ->name('log.notifikasi.destroy');

    /*
|--------------------------------------------------------------------------
| PENGATURAN SISTEM
|--------------------------------------------------------------------------
*/

Route::get('/pengaturan', [SettingController::class, 'index'])
    ->name('settings.index');

Route::post('/pengaturan', [SettingController::class, 'update'])
    ->name('settings.update');


    /*
|--------------------------------------------------------------------------
| MODUL TAHUN AJARAN
|--------------------------------------------------------------------------
*/

Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])
    ->name('tahunajaran.index');

Route::post('/tahun-ajaran', [TahunAjaranController::class, 'store'])
    ->name('tahunajaran.store');

Route::put('/tahun-ajaran/{id}/aktifkan', [TahunAjaranController::class, 'aktifkan'])
    ->name('tahunajaran.aktifkan');

/*
|--------------------------------------------------------------------------
| MODUL AKADEMIK
|--------------------------------------------------------------------------
| Berisi seluruh proses akademik seperti:
| - Kenaikan Kelas
| - Alumni
|--------------------------------------------------------------------------
*/

Route::get('/tahun-ajaran/kenaikan-kelas', [TahunAjaranController::class, 'kenaikan'])
    ->name('tahunajaran.kenaikan');

/*
|--------------------------------------------------------------------------
| MODUL AKADEMIK (lanjutan)
|--------------------------------------------------------------------------
*/

Route::post('/tahun-ajaran/kenaikan-kelas', [TahunAjaranController::class, 'prosesKenaikan'])
    ->name('tahunajaran.proses');

    /*
|--------------------------------------------------------------------------
| MODUL ALUMNI
|--------------------------------------------------------------------------
*/

Route::get('/alumni', [AlumniController::class, 'index'])
    ->name('alumni.index');

        /*
|--------------------------------------------------------------------------
| MODUL TAHUN AJARAN
|--------------------------------------------------------------------------
*/

Route::put('/tahun-ajaran/{id}', [TahunAjaranController::class,'update'])
    ->name('tahunajaran.update');

Route::delete('/tahun-ajaran/{id}', [TahunAjaranController::class,'destroy'])
    ->name('tahunajaran.destroy');
});