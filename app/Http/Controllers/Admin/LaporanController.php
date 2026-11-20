<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function presensi(Request $request)
    {
        $daftarKelas = Kelas::orderBy('tingkat')->get();
        $kelasId = $request->input('kelas_id', $daftarKelas->first()?->id);
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $presensi = Presensi::with('siswa')
                            ->where('kelas_id', $kelasId)
                            ->whereMonth('tanggal', $bulan)
                            ->whereYear('tanggal', $tahun)
                            ->get();

        return view('admin.laporan.presensi', compact('daftarKelas', 'kelasId', 'bulan', 'tahun', 'presensi'));
    }

    public function cetakPresensiPdf(Request $request)
    {
        $daftarKelas = Kelas::orderBy('tingkat')->get();
        $kelas = Kelas::with('waliKelas')->findOrFail($request->kelas_id);
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $presensi = Presensi::with('siswa')
                            ->where('kelas_id', $kelas->id)
                            ->whereMonth('tanggal', $bulan)
                            ->whereYear('tanggal', $tahun)
                            ->get();

        $pdf = Pdf::loadView('pdf.rekap-presensi', compact('kelas', 'bulan', 'tahun', 'presensi'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream("Rekap_Presensi_{$kelas->nama_kelas}_{$bulan}_{$tahun}.pdf");
    }
}
