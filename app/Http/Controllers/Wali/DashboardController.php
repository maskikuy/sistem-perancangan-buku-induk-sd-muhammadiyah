<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pengajuan;
use App\Models\Pengumuman;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Helper untuk mendapatkan siswa aktif yang dipilih oleh wali murid
     */
    protected function getAnakAktif()
    {
        $wali = Auth::user()->wali;
        if (! $wali) {
            return null;
        }

        $anakAktifId = session('anak_aktif_id');
        $anak = $wali->siswa()->where('siswa.id', $anakAktifId)->first();

        if (! $anak) {
            $anak = $wali->siswa()->first();
            if ($anak) {
                session(['anak_aktif_id' => $anak->id]);
            }
        }

        return $anak;
    }

    /**
     * Beranda Portal Wali Murid (PWA Mobile-First)
     */
    public function index()
    {
        $wali = Auth::user()->wali;
        $anak = $this->getAnakAktif();

        $presensiHariIni = null;
        $jadwalHariIni   = collect();
        $pengajuanTerbaru = collect();

        if ($anak) {
            $hariIni = Carbon::today()->toDateString();
            $presensiHariIni = Presensi::where('siswa_id', $anak->id)->where('tanggal', $hariIni)->first();

            // Jadwal pelajaran hari ini berdasarkan kelas aktif anak
            $kelasAktif = $anak->kelasAktif?->kelas;
            if ($kelasAktif) {
                $namaHariIni = strtolower(Carbon::now()->isoFormat('dddd'));
                $jadwalHariIni = Jadwal::with(['mataPelajaran', 'guru'])
                                       ->where('kelas_id', $kelasAktif->id)
                                       ->where('hari', $namaHariIni)
                                       ->orderBy('jam_mulai')
                                       ->get();
            }

            // Pengajuan izin & penjemputan anak ini
            $pengajuanTerbaru = Pengajuan::where('siswa_id', $anak->id)
                                         ->latest()
                                         ->take(3)
                                         ->get();
        }

        // Pengumuman sekolah
        $pengumuman = Pengumuman::whereIn('sasaran', ['semua', 'wali_semua'])->latest()->take(3)->get();

        return view('wali.dashboard', compact(
            'wali',
            'anak',
            'presensiHariIni',
            'jadwalHariIni',
            'pengajuanTerbaru',
            'pengumuman'
        ));
    }

    /**
     * Switcher: Mengganti anak aktif yang sedang dilihat
     */
    public function pilihAnak(Request $request)
    {
        $wali = Auth::user()->wali;
        $request->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
        ]);

        // Verifikasi bahwa siswa tersebut benar anak dari wali ini (Policy/Security)
        if ($wali && $wali->siswa()->where('siswa.id', $request->siswa_id)->exists()) {
            session(['anak_aktif_id' => $request->siswa_id]);
        }

        return back();
    }
}
