<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\CatatanWaliKelas;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Siswa;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatanRaporController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;
        $kelasWali = $guru ? $guru->kelasWali : null;
        $daftarKelas = Kelas::orderBy('tingkat')->get();

        $kelasId = $request->input('kelas_id', $kelasWali ? $kelasWali->id : $daftarKelas->first()?->id);
        $semAktif = Semester::where('is_aktif', true)->first();

        $daftarSiswa = Siswa::whereHas('riwayatKelas', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId)->where('status_riwayat', 'aktif');
        })->orderBy('nama_lengkap')->get();

        $catatanTersimpan = CatatanWaliKelas::where('kelas_id', $kelasId)
                                            ->where('semester_id', $semAktif ? $semAktif->id : 1)
                                            ->get()
                                            ->keyBy('siswa_id');

        return view('guru.catatan.index', compact('daftarKelas', 'kelasId', 'daftarSiswa', 'catatanTersimpan', 'semAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => ['required', 'exists:kelas,id'],
            'catatan'  => ['required', 'array'],
        ]);

        $guru = Auth::user()->guru;
        $semAktif = Semester::where('is_aktif', true)->first();

        foreach ($request->catatan as $siswaId => $teks) {
            if ($teks) {
                CatatanWaliKelas::updateOrCreate(
                    [
                        'siswa_id'    => $siswaId,
                        'kelas_id'    => $request->kelas_id,
                        'semester_id' => $semAktif ? $semAktif->id : 1,
                    ],
                    [
                        'guru_id'         => $guru ? $guru->id : 1,
                        'catatan'         => $teks,
                        'status_kenaikan' => $request->status_kenaikan[$siswaId] ?? 'belum_ditentukan',
                    ]
                );
            }
        }

        AuditTrailService::catat('UPDATE', 'catatan_wali_kelas', null, null, ['kelas_id' => $request->kelas_id]);

        return back()->with('success', 'Catatan wali kelas untuk rapor berhasil disimpan!');
    }
}
