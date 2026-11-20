<?php

use App\Http\Controllers\Admin\AkunWaliController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\KenaikanKelasController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\MutasiController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TahunAjaranController;
use App\Http\Controllers\Guru\CatatanRaporController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\JadwalMengajarController;
use App\Http\Controllers\Guru\NilaiController;
use App\Http\Controllers\Guru\PersetujuanPengajuanController;
use App\Http\Controllers\Guru\PresensiController;
use App\Http\Controllers\Piket\PenjemputanPiketController;
use App\Http\Controllers\Wali\DashboardController as WaliDashboardController;
use App\Http\Controllers\Wali\JadwalAnakController;
use App\Http\Controllers\Wali\NilaiRaporController;
use App\Http\Controllers\Wali\PengajuanController as WaliPengajuanController;
use App\Http\Controllers\Wali\PenjemputAlternatifController;
use App\Http\Controllers\Wali\PresensiAnakController;
use App\Http\Controllers\Wali\ProfilAnakController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Awal & Pengalihan Berdasarkan Peran Pengguna
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->hasRole('kepsek')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->hasRole('guru')) {
            return redirect()->route('guru.dashboard');
        }
        if ($user->hasRole('wali')) {
            return redirect()->route('wali.dashboard');
        }
    }
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| 1. AREA ADMIN / TATA USAHA (Master Data & Buku Induk)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|kepsek'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Buku Induk Siswa & Cetak PDF Resmi
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::get('/siswa/{id}', [SiswaController::class, 'show'])->name('siswa.show');
    Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
    Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::get('/siswa/{id}/cetak-buku-induk', [SiswaController::class, 'cetakPdf'])->name('siswa.cetak-pdf');

    // Mutasi Masuk & Keluar Siswa
    Route::get('/mutasi', [MutasiController::class, 'index'])->name('mutasi.index');
    Route::post('/mutasi', [MutasiController::class, 'store'])->name('mutasi.store');

    // Kenaikan Kelas Massal & Kelulusan
    Route::get('/kenaikan-kelas', [KenaikanKelasController::class, 'index'])->name('kenaikan.index');
    Route::post('/kenaikan-kelas/proses', [KenaikanKelasController::class, 'proses'])->name('kenaikan.proses');

    // Master Periode Akademik: Tahun Ajaran & Semester
    Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
    Route::post('/tahun-ajaran', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
    Route::post('/tahun-ajaran/{id}/semester/{semId}/aktif', [TahunAjaranController::class, 'setAktif'])->name('tahun-ajaran.set-aktif');

    // Master Rombel Kelas & Wali Kelas
    Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
    Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');

    // Master Mata Pelajaran & Kurikulum Ismuba
    Route::get('/mata-pelajaran', [MapelController::class, 'index'])->name('mapel.index');
    Route::post('/mata-pelajaran', [MapelController::class, 'store'])->name('mapel.store');

    // Master Guru & Akun Login
    Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
    Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');

    // Jadwal Pelajaran
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::post('/jadwal', [JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [JadwalController::class, 'destroy'])->name('jadwal.destroy');

    // Manajemen Akun Wali & Kode Aktivasi
    Route::get('/akun-wali', [AkunWaliController::class, 'index'])->name('akun-wali.index');
    Route::post('/akun-wali/{id}/generate-kode', [AkunWaliController::class, 'generateKode'])->name('akun-wali.generate-kode');
    Route::post('/akun-wali/{id}/reset-password', [AkunWaliController::class, 'resetPassword'])->name('akun-wali.reset-password');

    // Pengumuman Sekolah
    Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'destroy'])->name('pengumuman.destroy');

    // Laporan & Rekapitulasi Presensi
    Route::get('/laporan/presensi', [LaporanController::class, 'presensi'])->name('laporan.presensi');
    Route::get('/laporan/presensi/cetak', [LaporanController::class, 'cetakPresensiPdf'])->name('laporan.presensi.cetak');

    // Audit Trail Log Aktivitas
    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
});

/*
|--------------------------------------------------------------------------
| 2. AREA GURU & WALI KELAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru|admin'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Presensi Harian Siswa
    Route::get('/presensi', [PresensiController::class, 'index'])->name('presensi.index');
    Route::post('/presensi', [PresensiController::class, 'store'])->name('presensi.store');

    // Input Nilai Rapor
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');

    // Catatan Wali Kelas untuk Rapor
    Route::get('/catatan-rapor', [CatatanRaporController::class, 'index'])->name('catatan.index');
    Route::post('/catatan-rapor', [CatatanRaporController::class, 'store'])->name('catatan.store');

    // Jadwal Mengajar Pribadi
    Route::get('/jadwal', [JadwalMengajarController::class, 'index'])->name('jadwal.index');

    // Persetujuan Izin & Penjemputan
    Route::get('/pengajuan', [PersetujuanPengajuanController::class, 'index'])->name('pengajuan.index');
    Route::post('/pengajuan/{id}/proses', [PersetujuanPengajuanController::class, 'proses'])->name('pengajuan.proses');
});

/*
|--------------------------------------------------------------------------
| 3. MEJA PIKET GERBANG (VERIFIKASI PENJEMPUTAN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:guru|admin'])->prefix('piket')->name('piket.')->group(function () {
    Route::get('/penjemputan', [PenjemputanPiketController::class, 'index'])->name('penjemputan');
    Route::post('/penjemputan/{id}/selesai', [PenjemputanPiketController::class, 'tandaiDijemput'])->name('penjemputan.selesai');
});

/*
|--------------------------------------------------------------------------
| 4. PORTAL WALI MURID (PWA MOBILE-FIRST)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:wali'])->prefix('wali')->name('wali.')->group(function () {
    Route::get('/dashboard', [WaliDashboardController::class, 'index'])->name('dashboard');
    Route::post('/pilih-anak', [WaliDashboardController::class, 'pilihAnak'])->name('pilih-anak');

    // Profil Anak & Permohonan Koreksi
    Route::get('/anak', [ProfilAnakController::class, 'index'])->name('anak');
    Route::post('/anak/koreksi', [ProfilAnakController::class, 'ajukanKoreksi'])->name('anak.koreksi');

    // Riwayat Presensi Kehadiran Anak
    Route::get('/presensi', [PresensiAnakController::class, 'index'])->name('presensi');

    // Nilai Rapor & Capaian Pembelajaran
    Route::get('/nilai', [NilaiRaporController::class, 'index'])->name('nilai');

    // Jadwal Pelajaran Mingguan Anak
    Route::get('/jadwal', [JadwalAnakController::class, 'index'])->name('jadwal');

    // Kelola Penjemput Alternatif Terpercaya
    Route::get('/penjemput', [PenjemputAlternatifController::class, 'index'])->name('penjemput.index');
    Route::post('/penjemput', [PenjemputAlternatifController::class, 'store'])->name('penjemput.store');
    Route::delete('/penjemput/{id}', [PenjemputAlternatifController::class, 'destroy'])->name('penjemput.destroy');

    // Layanan Pengajuan Izin & Penjemputan
    Route::get('/pengajuan', [WaliPengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/izin', [WaliPengajuanController::class, 'createIzin'])->name('pengajuan.izin.create');
    Route::post('/pengajuan/izin', [WaliPengajuanController::class, 'storeIzin'])->name('pengajuan.izin.store');
    Route::get('/pengajuan/penjemputan', [WaliPengajuanController::class, 'createPenjemputan'])->name('pengajuan.penjemputan.create');
    Route::post('/pengajuan/penjemputan', [WaliPengajuanController::class, 'storePenjemputan'])->name('pengajuan.penjemputan.store');
    Route::post('/pengajuan/{id}/batalkan', [WaliPengajuanController::class, 'batalkan'])->name('pengajuan.batalkan');
});

require __DIR__.'/auth.php';
