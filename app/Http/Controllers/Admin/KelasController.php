<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $taAktif = TahunAjaran::where('is_aktif', true)->first();
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
                      ->withCount(['riwayatKelas' => fn($q) => $q->where('status_riwayat', 'aktif')])
                      ->orderBy('tingkat')
                      ->get();
        $daftarGuru = Guru::orderBy('nama_lengkap')->get();

        return view('admin.kelas.index', compact('kelas', 'daftarGuru', 'taAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => ['required', 'string', 'max:50'],
            'tingkat'    => ['required', 'integer', 'between:1,6'],
            'guru_id'    => ['required', 'exists:guru,id'],
            'kapasitas'  => ['required', 'integer', 'min:1'],
        ]);

        $taAktif = TahunAjaran::where('is_aktif', true)->first();

        $kelas = Kelas::create([
            'tahun_ajaran_id' => $taAktif ? $taAktif->id : 1,
            'guru_id'         => $request->guru_id,
            'nama_kelas'      => $request->nama_kelas,
            'tingkat'         => $request->tingkat,
            'kapasitas'       => $request->kapasitas,
        ]);

        // Tandai guru sebagai wali kelas
        Guru::where('id', $request->guru_id)->update(['is_wali_kelas' => true]);

        AuditTrailService::catat('CREATE', 'kelas', $kelas->id, null, ['nama_kelas' => $kelas->nama_kelas]);

        return redirect()->route('admin.kelas.index')->with('success', "Rombel kelas {$kelas->nama_kelas} berhasil ditambahkan!");
    }
}
