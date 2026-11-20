<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    public function index(Request $request)
    {
        $taAktif  = TahunAjaran::where('is_aktif', true)->first();
        $semAktif = Semester::where('is_aktif', true)->first();
        $daftarKelas = Kelas::orderBy('tingkat')->get();

        $kelasAsalId = $request->input('kelas_asal_id', $daftarKelas->first()?->id);

        $daftarSiswa = Siswa::whereHas('riwayatKelas', function ($q) use ($kelasAsalId) {
            $q->where('kelas_id', $kelasAsalId)->where('status_riwayat', 'aktif');
        })->with('catatanWaliKelas')->orderBy('nama_lengkap')->get();

        return view('admin.kenaikan.index', compact('daftarKelas', 'kelasAsalId', 'daftarSiswa', 'taAktif', 'semAktif'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'kelas_asal_id'   => ['required', 'exists:kelas,id'],
            'aksi'            => ['required', 'in:naik_kelas,lulus'],
            'kelas_tujuan_id' => ['required_if:aksi,naik_kelas', 'nullable', 'exists:kelas,id'],
            'siswa_id'        => ['required', 'array'],
        ]);

        $taAktif  = TahunAjaran::where('is_aktif', true)->first();
        $semAktif = Semester::where('is_aktif', true)->first();

        DB::beginTransaction();
        try {
            foreach ($request->siswa_id as $sId) {
                // 1. Nonaktifkan riwayat kelas lama
                RiwayatKelas::where('siswa_id', $sId)
                            ->where('kelas_id', $request->kelas_asal_id)
                            ->update(['status_riwayat' => $request->aksi]);

                // 2. Jika naik kelas, buat riwayat kelas baru di kelas tujuan
                if ($request->aksi === 'naik_kelas') {
                    RiwayatKelas::create([
                        'siswa_id'        => $sId,
                        'kelas_id'        => $request->kelas_tujuan_id,
                        'tahun_ajaran_id' => $taAktif ? $taAktif->id : 1,
                        'semester_id'     => $semAktif ? $semAktif->id : 1,
                        'status_riwayat'  => 'aktif',
                    ]);
                } else {
                    // Jika kelulusan, perbarui status siswa di buku induk menjadi 'lulus'
                    Siswa::where('id', $sId)->update(['status_siswa' => 'lulus']);
                }
            }

            AuditTrailService::catat('UPDATE', 'riwayat_kelas', null, null, ['aksi' => $request->aksi, 'total_siswa' => count($request->siswa_id)]);

            DB::commit();
            return back()->with('success', 'Proses kenaikan kelas/kelulusan massal berhasil dijalankan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}
