<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Pengajuan;
use App\Models\Pengumuman;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan statistik ringkasan sekolah untuk Admin / TU dan Kepala Sekolah
     */
    public function index()
    {
        $totalSiswa = Siswa::where('status_siswa', 'aktif')->count();
        $totalGuru  = Guru::count();
        $totalKelas = Kelas::count();
        $taAktif    = TahunAjaran::where('is_aktif', true)->first();

        // Statistik presensi hari ini
        $hariIni = Carbon::today()->toDateString();
        $presensiHariIni = Presensi::where('tanggal', $hariIni)->get();
        $rekapPresensi = [
            'hadir'  => $presensiHariIni->where('status', 'hadir')->count(),
            'izin'   => $presensiHariIni->where('status', 'izin')->count(),
            'sakit'  => $presensiHariIni->where('status', 'sakit')->count(),
            'alpa'   => $presensiHariIni->where('status', 'alpa')->count(),
            'pulang' => $presensiHariIni->where('status', 'pulang_awal')->count(),
        ];

        // Pengajuan menunggu
        $pengajuanMenunggu = Pengajuan::with(['siswa', 'wali'])->where('status', 'menunggu')->latest()->take(5)->get();

        // Aktivitas audit log terbaru
        $auditLogsTerbaru = AuditLog::with('user')->latest('id')->take(6)->get();

        // Pengumuman sekolah
        $pengumuman = Pengumuman::latest()->take(3)->get();

        return view('admin.dashboard', compact(
            'totalSiswa',
            'totalGuru',
            'totalKelas',
            'taAktif',
            'rekapPresensi',
            'pengajuanMenunggu',
            'auditLogsTerbaru',
            'pengumuman'
        ));
    }
}
