<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\PenjemputAlternatif;
use App\Models\Siswa;
use App\Services\AuditTrailService;
use App\Services\NotifikasiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
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
        $wali = Auth::user()->wali;
        $anak = $this->getAnakAktif();

        if (! $wali) {
            return redirect()->route('login');
        }

        $pengajuan = Pengajuan::with(['siswa', 'penjemputAlternatif'])
                              ->where('wali_id', $wali->id)
                              ->when($anak, fn($q) => $q->where('siswa_id', $anak->id))
                              ->latest()
                              ->paginate(10);

        return view('wali.pengajuan.index', compact('wali', 'anak', 'pengajuan'));
    }

    public function createIzin()
    {
        $anak = $this->getAnakAktif();
        return view('wali.pengajuan.izin', compact('anak'));
    }

    public function storeIzin(Request $request)
    {
        $anak = $this->getAnakAktif();
        $wali = Auth::user()->wali;

        $request->validate([
            'tanggal_mulai'   => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'alasan'          => ['required', 'string', 'min:5'],
        ]);

        $pengajuan = Pengajuan::create([
            'nomor_pengajuan' => 'IZN-' . date('Ymd') . '-' . rand(1000, 9999),
            'siswa_id'        => $anak->id,
            'wali_id'         => $wali->id,
            'jenis'           => 'izin_tidak_masuk',
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai ?? $request->tanggal_mulai,
            'alasan'          => $request->alasan,
            'status'          => 'menunggu',
        ]);

        AuditTrailService::catat('CREATE', 'pengajuan', $pengajuan->id, null, ['jenis' => 'izin_tidak_masuk']);

        return redirect()->route('wali.pengajuan.index')->with('success', 'Pengajuan izin tidak masuk berhasil dikirimkan!');
    }

    public function createPenjemputan()
    {
        $anak = $this->getAnakAktif();
        $wali = Auth::user()->wali;

        $daftarPenjemput = $wali ? PenjemputAlternatif::where('wali_id', $wali->id)
                                                      ->where('is_aktif', true)
                                                      ->get() : collect();

        return view('wali.pengajuan.penjemputan', compact('anak', 'daftarPenjemput'));
    }

    public function storePenjemputan(Request $request)
    {
        $anak = $this->getAnakAktif();
        $wali = Auth::user()->wali;

        $request->validate([
            'tanggal_jemput'          => ['required', 'date', 'after_or_equal:today'],
            'jam_jemput'              => ['required'],
            'alasan'                  => ['required', 'string', 'min:5'],
            'is_mendesak'             => ['nullable', 'boolean'],
            'penjemput_alternatif_id' => ['nullable', 'exists:penjemput_alternatif,id'],
        ]);

        $isMendesak = $request->boolean('is_mendesak');

        // Aturan Workflow 1: Batas pengajuan minimal 30 menit sebelum jam jemput kecuali "Mendesak"
        if (!$isMendesak && $request->tanggal_jemput === Carbon::today()->toDateString()) {
            $waktuJemput = Carbon::parse($request->tanggal_jemput . ' ' . $request->jam_jemput);
            if ($waktuJemput->diffInMinutes(Carbon::now(), false) > -30) {
                return back()->withInput()->with('error', 'Pengajuan penjemputan harus dilakukan minimal 30 menit sebelum jam jemput, kecuali jika opsi "Mendesak" diaktifkan.');
            }
        }

        // Aturan Workflow 2: Cegah dua pengajuan di jam yang sama untuk anak yang sama
        $konflik = Pengajuan::where('siswa_id', $anak->id)
                            ->where('jenis', 'penjemputan')
                            ->where('tanggal_mulai', $request->tanggal_jemput)
                            ->where('jam_jemput', $request->jam_jemput)
                            ->whereIn('status', ['menunggu', 'disetujui'])
                            ->exists();

        if ($konflik) {
            return back()->withInput()->with('error', 'Sudah ada pengajuan penjemputan aktif pada tanggal dan jam yang sama untuk ananda.');
        }

        $pengajuan = Pengajuan::create([
            'nomor_pengajuan'         => 'JMP-' . date('Ymd') . '-' . rand(1000, 9999),
            'siswa_id'                => $anak->id,
            'wali_id'                 => $wali->id,
            'jenis'                   => 'penjemputan',
            'tanggal_mulai'           => $request->tanggal_jemput,
            'jam_jemput'              => $request->jam_jemput,
            'is_mendesak'             => $isMendesak,
            'penjemput_alternatif_id' => $request->penjemput_alternatif_id,
            'alasan'                  => $request->alasan,
            'status'                  => 'menunggu',
        ]);

        AuditTrailService::catat('CREATE', 'pengajuan', $pengajuan->id, null, ['jenis' => 'penjemputan', 'jam' => $request->jam_jemput]);

        return redirect()->route('wali.pengajuan.index')->with('success', 'Permohonan penjemputan berhasil dibuat dan sedang menunggu persetujuan guru!');
    }

    public function batalkan($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $this->authorize('batalkan', $pengajuan);

        $pengajuan->update(['status' => 'dibatalkan']);
        AuditTrailService::catat('BATALKAN', 'pengajuan', $pengajuan->id);

        return back()->with('success', 'Pengajuan berhasil dibatalkan.');
    }
}
