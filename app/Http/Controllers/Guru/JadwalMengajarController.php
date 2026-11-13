<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class JadwalMengajarController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        $taAktif  = TahunAjaran::where('is_aktif', true)->first();
        $semAktif = Semester::where('is_aktif', true)->first();

        $jadwal = $guru ? Jadwal::with(['kelas', 'mataPelajaran'])
                                ->where('guru_id', $guru->id)
                                ->where('semester_id', $semAktif ? $semAktif->id : 1)
                                ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu')")
                                ->orderBy('jam_mulai')
                                ->get()
                                ->groupBy('hari') : collect();

        return view('guru.jadwal.index', compact('jadwal', 'guru', 'taAktif', 'semAktif'));
    }
}
