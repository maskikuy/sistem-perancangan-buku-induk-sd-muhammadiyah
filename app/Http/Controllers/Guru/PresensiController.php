<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\Semester;
use App\Models\Siswa;
use App\Services\AuditTrailService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresensiController extends Controller
{
    /**
     * Tampilkan form input presensi siswa harian
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;

        // Ambil kelas yang diampu guru ini (atau kelas pertama)
        $kelasWali = $guru ? $guru->kelasWali : null;
        $daftarKelas = Kelas::orderBy('tingkat')->get();

        $kelasId = $request->input('kelas_id', $kelasWali ? $kelasWali->id : $daftarKelas->first()?->id);
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());

        $taAktif = \App\Models\TahunAjaran::where('is_aktif', true)->first();
        $semAktif = Semester::where('is_aktif', true)->first();

        // Ambil siswa yang aktif di kelas ini
        $daftarSiswa = Siswa::whereHas('riwayatKelas', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId)->where('status_riwayat', 'aktif');
        })->orderBy('nama_lengkap')->get();

        // Ambil data presensi yang sudah tercatat pada tanggal & kelas tersebut
        $presensiTercatat = Presensi::where('kelas_id', $kelasId)
                                    ->where('tanggal', $tanggal)
                                    ->get()
                                    ->keyBy('siswa_id');

        return view('guru.presensi.index', compact(
            'daftarKelas',
            'kelasId',
            'tanggal',
            'daftarSiswa',
            'presensiTercatat',
            'taAktif',
            'semAktif'
        ));
    }

    /**
     * Simpan / perbarui presensi harian siswa sekelas
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'   => ['required', 'exists:kelas,id'],
            'tanggal'    => ['required', 'date'],
            'presensi'   => ['required', 'array'],
            'presensi.*' => ['required', 'in:hadir,izin,sakit,alpa,pulang_awal'],
        ]);

        $guru = Auth::user()->guru;
        $semAktif = Semester::where('is_aktif', true)->first();

        foreach ($request->presensi as $siswaId => $status) {
            Presensi::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal'  => $request->tanggal,
                ],
                [
                    'kelas_id'    => $request->kelas_id,
                    'semester_id' => $semAktif ? $semAktif->id : 1,
                    'status'      => $status,
                    'keterangan'  => $request->keterangan[$siswaId] ?? null,
                    'guru_id'     => $guru ? $guru->id : null,
                ]
            );
        }

        AuditTrailService::catat('UPDATE', 'presensi', null, null, ['kelas_id' => $request->kelas_id, 'tanggal' => $request->tanggal]);

        return back()->with('success', 'Presensi siswa berhasil disimpan dan diperbarui!');
    }
}
