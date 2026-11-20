<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\CatatanWaliKelas;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiRaporController extends Controller
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

        // Ambil seluruh nilai anak di semester aktif
        $nilai = Nilai::with(['mataPelajaran', 'guru'])
                      ->where('siswa_id', $anak->id)
                      ->where('semester_id', $semAktif ? $semAktif->id : 1)
                      ->get()
                      ->groupBy('mata_pelajaran_id');

        // Catatan wali kelas untuk rapor
        $catatanWali = CatatanWaliKelas::where('siswa_id', $anak->id)
                                       ->where('semester_id', $semAktif ? $semAktif->id : 1)
                                       ->first();

        return view('wali.nilai', compact('anak', 'nilai', 'catatanWali', 'taAktif', 'semAktif'));
    }
}
