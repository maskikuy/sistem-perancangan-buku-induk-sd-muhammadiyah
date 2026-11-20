<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalAnakController extends Controller
{
    protected function getAnakAktif()
    {
        $wali = Auth::user()->wali;
        if (! $wali) return null;

        $anakAktifId = session('anak_aktif_id');
        return $wali->siswa()->where('siswa.id', $anakAktifId)->first() ?? $wali->siswa()->first();
    }

    public function index()
    {
        $anak = $this->getAnakAktif();

        if (! $anak) {
            return redirect()->route('wali.dashboard');
        }

        $this->authorize('view', $anak);

        $taAktif  = TahunAjaran::where('is_aktif', true)->first();
        $semAktif = Semester::where('is_aktif', true)->first();

        $kelasAktif = $anak->kelasAktif?->kelas;

        $jadwal = $kelasAktif ? Jadwal::with(['mataPelajaran', 'guru'])
                                     ->where('kelas_id', $kelasAktif->id)
                                     ->where('semester_id', $semAktif ? $semAktif->id : 1)
                                     ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu')")
                                     ->orderBy('jam_mulai')
                                     ->get()
                                     ->groupBy('hari') : collect();

        return view('wali.jadwal', compact('anak', 'jadwal', 'kelasAktif', 'taAktif', 'semAktif'));
    }
}
