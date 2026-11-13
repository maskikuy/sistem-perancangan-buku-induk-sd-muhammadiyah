<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pengajuan;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard Guru & Wali Kelas: Menampilkan kelas ampunan, jadwal hari ini, dan pengajuan menunggu
     */
    public function index()
    {
        $user = Auth::user();
        $guru = $user->guru;

        $kelasWali = $guru ? $guru->kelasWali : null;
        $totalSiswaWali = 0;
        $rekapPresensiHariIni = null;

        if ($kelasWali) {
            $totalSiswaWali = Siswa::whereHas('riwayatKelas', function ($q) use ($kelasWali) {
                $q->where('kelas_id', $kelasWali->id)->where('status_riwayat', 'aktif');
            })->count();

            $hariIni = Carbon::today()->toDateString();
            $presensiHariIni = Presensi::where('kelas_id', $kelasWali->id)->where('tanggal', $hariIni)->get();
            $rekapPresensiHariIni = [
                'hadir'  => $presensiHariIni->where('status', 'hadir')->count(),
                'izin'   => $presensiHariIni->where('status', 'izin')->count(),
                'sakit'  => $presensiHariIni->where('status', 'sakit')->count(),
                'alpa'   => $presensiHariIni->where('status', 'alpa')->count(),
                'pulang' => $presensiHariIni->where('status', 'pulang_awal')->count(),
            ];
        }

        // Jadwal mengajar hari ini
        $namaHariIni = strtolower(Carbon::now()->isoFormat('dddd')); // senin, selasa, dll.
        $jadwalHariIni = $guru ? Jadwal::with(['kelas', 'mataPelajaran'])
                                      ->where('guru_id', $guru->id)
                                      ->where('hari', $namaHariIni)
                                      ->orderBy('jam_mulai')
                                      ->get() : collect();

        // Pengajuan izin & penjemputan siswa di kelasnya yang statusnya 'menunggu'
        $pengajuanMenunggu = Pengajuan::with(['siswa', 'wali', 'penjemputAlternatif'])
            ->where('status', 'menunggu')
            ->when($kelasWali, function ($q) use ($kelasWali) {
                $q->whereHas('siswa.riwayatKelas', function ($sq) use ($kelasWali) {
                    $sq->where('kelas_id', $kelasWali->id)->where('status_riwayat', 'aktif');
                });
            })
            ->latest()
            ->get();

        return view('guru.dashboard', compact(
            'guru',
            'kelasWali',
            'totalSiswaWali',
            'rekapPresensiHariIni',
            'jadwalHariIni',
            'pengajuanMenunggu'
        ));
    }
}
