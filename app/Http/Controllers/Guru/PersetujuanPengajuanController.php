<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Presensi;
use App\Services\AuditTrailService;
use App\Services\NotifikasiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersetujuanPengajuanController extends Controller
{
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;
        $kelasWali = $guru ? $guru->kelasWali : null;

        $pengajuan = Pengajuan::with(['siswa.kelasAktif.kelas', 'wali', 'penjemputAlternatif'])
                              ->when($kelasWali, function ($q) use ($kelasWali) {
                                  $q->whereHas('siswa.riwayatKelas', function ($sq) use ($kelasWali) {
                                      $sq->where('kelas_id', $kelasWali->id)->where('status_riwayat', 'aktif');
                                  });
                              })
                              ->latest()
                              ->paginate(15);

        return view('guru.pengajuan.index', compact('pengajuan'));
    }

    public function proses(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('siswa')->findOrFail($id);

        $request->validate([
            'status'  => ['required', 'in:disetujui,ditolak'],
            'catatan' => ['nullable', 'string', 'required_if:status,ditolak'],
        ], [
            'catatan.required_if' => 'Alasan wajib diisi jika permohonan ditolak.',
        ]);

        $statusAwal = $pengajuan->status;
        $pengajuan->update([
            'status'                => $request->status,
            'diproses_oleh_user_id' => Auth::id(),
            'catatan_persetujuan'   => $request->catatan,
        ]);

        // Jika pengajuan izin disetujui, otomatis rekam di presensi
        if ($request->status === 'disetujui' && $pengajuan->jenis === 'izin_tidak_masuk') {
            $semAktif = \App\Models\Semester::where('is_aktif', true)->first();
            $kelasAktif = $pengajuan->siswa->kelasAktif?->kelas;

            Presensi::updateOrCreate(
                [
                    'siswa_id' => $pengajuan->siswa_id,
                    'tanggal'  => $pengajuan->tanggal_mulai,
                ],
                [
                    'kelas_id'     => $kelasAktif ? $kelasAktif->id : 1,
                    'semester_id'  => $semAktif ? $semAktif->id : 1,
                    'status'       => 'izin',
                    'pengajuan_id' => $pengajuan->id,
                    'keterangan'   => 'Izin: ' . $pengajuan->alasan,
                    'guru_id'      => Auth::user()->guru?->id,
                ]
            );
        }

        // Notifikasi ke wali murid
        if ($pengajuan->wali?->user_id) {
            $pesanNotif = $request->status === 'disetujui' 
                ? "Pengajuan {$pengajuan->jenis} untuk {$pengajuan->siswa->nama_lengkap} telah DISETUJUI oleh guru."
                : "Pengajuan {$pengajuan->jenis} untuk {$pengajuan->siswa->nama_lengkap} DITOLAK. Alasan: {$request->catatan}";

            NotifikasiService::kirim(
                $pengajuan->wali->user_id,
                'Status Pengajuan: ' . ucfirst($request->status),
                $pesanNotif,
                'status_pengajuan',
                '/wali/pengajuan'
            );
        }

        AuditTrailService::catat(strtoupper($request->status), 'pengajuan', $pengajuan->id, ['status' => $statusAwal], ['status' => $request->status]);

        return back()->with('success', "Pengajuan berhasil diproses menjadi: " . strtoupper($request->status));
    }
}
