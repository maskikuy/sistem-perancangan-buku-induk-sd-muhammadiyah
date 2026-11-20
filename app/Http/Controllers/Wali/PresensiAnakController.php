<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Presensi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiAnakController extends Controller
{
    protected function getAnakAktif()
    {
        $wali = Auth::user()->wali;
        if (! $wali) return null;

        $anakAktifId = session('anak_aktif_id');
        return $wali->siswa()->where('siswa.id', $anakAktifId)->first() ?? $wali->siswa()->first();
    }

    public function index(Request $request)
    {
        $anak = $this->getAnakAktif();

        if (! $anak) {
            return redirect()->route('wali.dashboard');
        }

        $this->authorize('view', $anak);

        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $presensi = Presensi::where('siswa_id', $anak->id)
                            ->whereMonth('tanggal', $bulan)
                            ->whereYear('tanggal', $tahun)
                            ->orderBy('tanggal', 'desc')
                            ->get();

        $rekap = [
            'hadir'  => $presensi->where('status', 'hadir')->count(),
            'izin'   => $presensi->where('status', 'izin')->count(),
            'sakit'  => $presensi->where('status', 'sakit')->count(),
            'alpa'   => $presensi->where('status', 'alpa')->count(),
            'pulang' => $presensi->where('status', 'pulang_awal')->count(),
        ];

        return view('wali.presensi', compact('anak', 'presensi', 'rekap', 'bulan', 'tahun'));
    }
}
