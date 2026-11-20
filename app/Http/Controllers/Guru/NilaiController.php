<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\Siswa;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Tampilkan form input nilai per kelas, mata pelajaran, dan jenis penilaian
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $guru = $user->guru;
        $kelasWali = $guru ? $guru->kelasWali : null;
        $mapelDiampuGuru = $guru ? \App\Models\Jadwal::where('guru_id', $guru->id)->pluck('mata_pelajaran_id')->unique() : collect();

        $daftarKelas = Kelas::orderBy('tingkat')->get();
        $daftarMapel = MataPelajaran::orderBy('nama_mapel')->get();

        $kelasId = $request->input('kelas_id', $kelasWali ? $kelasWali->id : $daftarKelas->first()?->id);
        $defaultMapel = $mapelDiampuGuru->isNotEmpty() ? $mapelDiampuGuru->first() : $daftarMapel->first()?->id;
        $mapelId = $request->input('mapel_id', $defaultMapel);
        $jenisNilai = $request->input('jenis_nilai', 'pts'); // tugas, uh, pts, pas

        $taAktif = \App\Models\TahunAjaran::where('is_aktif', true)->first();
        $semAktif = Semester::where('is_aktif', true)->first();

        // Ambil siswa di kelas ini
        $daftarSiswa = Siswa::whereHas('riwayatKelas', function ($q) use ($kelasId) {
            $q->where('kelas_id', $kelasId)->where('status_riwayat', 'aktif');
        })->orderBy('nama_lengkap')->get();

        // Ambil nilai yang sudah tersimpan
        $nilaiTersimpan = Nilai::where('kelas_id', $kelasId)
                               ->where('mata_pelajaran_id', $mapelId)
                               ->where('jenis_nilai', $jenisNilai)
                               ->where('semester_id', $semAktif ? $semAktif->id : 1)
                               ->get()
                               ->keyBy('siswa_id');

        return view('guru.nilai.index', compact(
            'daftarKelas',
            'daftarMapel',
            'kelasId',
            'mapelId',
            'jenisNilai',
            'daftarSiswa',
            'nilaiTersimpan',
            'taAktif',
            'semAktif'
        ));
    }

    /**
     * Simpan nilai sekelas
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'          => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
            'jenis_nilai'       => ['required', 'in:tugas,uh,pts,pas'],
            'nilai'             => ['required', 'array'],
            'nilai.*'           => ['nullable', 'numeric', 'between:0,100'],
        ]);

        $guru = Auth::user()->guru;
        $semAktif = Semester::where('is_aktif', true)->first();

        foreach ($request->nilai as $siswaId => $angka) {
            if ($angka !== null && $angka !== '') {
                Nilai::updateOrCreate(
                    [
                        'siswa_id'          => $siswaId,
                        'kelas_id'          => $request->kelas_id,
                        'mata_pelajaran_id' => $request->mata_pelajaran_id,
                        'jenis_nilai'       => $request->jenis_nilai,
                        'semester_id'       => $semAktif ? $semAktif->id : 1,
                    ],
                    [
                        'nilai_angka'        => $angka,
                        'capaian_kompetensi' => $request->capaian[$siswaId] ?? null,
                        'guru_id'            => $guru ? $guru->id : 1,
                    ]
                );
            }
        }

        AuditTrailService::catat('UPDATE', 'nilai', null, null, [
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mata_pelajaran_id,
            'jenis'    => $request->jenis_nilai
        ]);

        return back()->with('success', 'Nilai akademik siswa berhasil disimpan!');
    }
}
