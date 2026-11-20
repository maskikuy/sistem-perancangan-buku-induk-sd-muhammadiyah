<?php

namespace App\Http\Controllers\Piket;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Presensi;
use App\Models\Semester;
use App\Services\AuditTrailService;
use App\Services\NotifikasiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjemputanPiketController extends Controller
{
    /**
     * Tampilan meja piket / gerbang sekolah: Menampilkan siswa yang diizinkan dijemput hari ini
     */
    public function index()
    {
        $hariIni = Carbon::today()->toDateString();

        $daftarJemput = Pengajuan::with(['siswa.kelasAktif.kelas', 'wali', 'penjemputAlternatif'])
                                 ->where('jenis', 'penjemputan')
                                 ->whereDate('tanggal_mulai', $hariIni)
                                 ->whereIn('status', ['disetujui', 'selesai'])
                                 ->orderBy('status', 'asc') // yang disetujui tampil paling atas
                                 ->orderBy('jam_jemput', 'asc')
                                 ->get();

        return view('piket.index', compact('daftarJemput'));
    }

    /**
     * Petugas piket memverifikasi penjemput & menandai "Sudah Dijemput"
     * Otomatis memperbarui status presensi siswa hari ini menjadi 'pulang_awal'
     */
    public function tandaiDijemput(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('siswa')->findOrFail($id);

        $penjemputNama = $request->input('dijemput_oleh_nama') 
            ?: ($pengajuan->penjemputAlternatif ? $pengajuan->penjemputAlternatif->nama_lengkap : $pengajuan->wali->nama_lengkap);

        $jamSekarang = Carbon::now();

        // 1. Update status pengajuan
        $pengajuan->update([
            'status'                 => 'selesai',
            'dijemput_pada'          => $jamSekarang,
            'dijemput_oleh_nama'     => $penjemputNama,
            'petugas_piket_user_id'  => Auth::id(),
        ]);

        // 2. Otomatis sinkronkan presensi siswa hari ini menjadi 'pulang_awal'
        $semAktif = Semester::where('is_aktif', true)->first();
        $kelasAktif = $pengajuan->siswa->kelasAktif?->kelas;

        Presensi::updateOrCreate(
            [
                'siswa_id' => $pengajuan->siswa_id,
                'tanggal'  => Carbon::today()->toDateString(),
            ],
            [
                'kelas_id'     => $kelasAktif ? $kelasAktif->id : 1,
                'semester_id'  => $semAktif ? $semAktif->id : 1,
                'status'       => 'pulang_awal',
                'jam_pulang'   => $jamSekarang->toTimeString(),
                'pengajuan_id' => $pengajuan->id,
                'keterangan'   => 'Dijemput pulang lebih awal oleh: ' . $penjemputNama . ' (' . $pengajuan->alasan . ')',
                'guru_id'      => Auth::id(),
            ]
        );

        // 3. Kirim notifikasi PWA ke wali murid
        if ($pengajuan->wali?->user_id) {
            NotifikasiService::kirim(
                $pengajuan->wali->user_id,
                'Anak Telah Dijemput',
                "Ananda {$pengajuan->siswa->nama_lengkap} telah dijemput oleh {$penjemputNama} pada jam {$jamSekarang->format('H:i')} WIB.",
                'penjemputan_selesai',
                '/wali/dashboard'
            );
        }

        // 4. Catat jejak audit
        AuditTrailService::catat('SELESAI', 'pengajuan', $pengajuan->id, ['status' => 'disetujui'], ['status' => 'selesai', 'dijemput_oleh' => $penjemputNama]);

        return back()->with('success', "Konfirmasi penjemputan untuk {$pengajuan->siswa->nama_lengkap} berhasil! Presensi otomatis ditandai 'Pulang Lebih Awal'.");
    }
}
