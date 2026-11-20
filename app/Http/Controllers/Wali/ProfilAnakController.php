<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Siswa;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilAnakController extends Controller
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

        // Policy Check: Pastikan wali berhak melihat data anak ini
        $this->authorize('view', $anak);

        $anak->load(['kelasAktif.kelas.waliKelas', 'wali', 'riwayatKelas.kelas', 'riwayatKelas.tahunAjaran']);

        return view('wali.anak', compact('anak'));
    }

    /**
     * Mengajukan permohonan koreksi data buku induk anak
     */
    public function ajukanKoreksi(Request $request)
    {
        $request->validate([
            'koreksi' => ['required', 'string', 'min:10'],
        ]);

        $anak = $this->getAnakAktif();
        $wali = Auth::user()->wali;

        Pengajuan::create([
            'nomor_pengajuan' => 'KOR-' . date('Ymd') . '-' . rand(1000, 9999),
            'siswa_id'        => $anak->id,
            'wali_id'         => $wali->id,
            'jenis'           => 'surat',
            'tanggal_mulai'   => now(),
            'alasan'          => 'Permohonan Koreksi Data Buku Induk: ' . $request->koreksi,
            'status'          => 'menunggu',
        ]);

        AuditTrailService::catat('CREATE', 'pengajuan', null, null, ['alasan' => 'Permohonan Koreksi Data']);

        return back()->with('success', 'Permohonan koreksi data berhasil dikirimkan ke pihak Tata Usaha!');
    }
}
