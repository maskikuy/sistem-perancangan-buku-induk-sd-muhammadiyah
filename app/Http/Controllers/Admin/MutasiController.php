<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MutasiSiswa;
use App\Models\Siswa;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index()
    {
        $mutasi = MutasiSiswa::with('siswa')->latest('tanggal_mutasi')->paginate(15);
        $daftarSiswa = Siswa::where('status_siswa', 'aktif')->orderBy('nama_lengkap')->get();

        return view('admin.mutasi.index', compact('mutasi', 'daftarSiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'siswa_id'            => ['required', 'exists:siswa,id'],
            'jenis_mutasi'        => ['required', 'in:masuk,keluar'],
            'tanggal_mutasi'      => ['required', 'date'],
            'sekolah_asal_tujuan' => ['required', 'string', 'max:120'],
            'alasan'              => ['required', 'string'],
        ]);

        $mutasi = MutasiSiswa::create($request->all());

        // Jika mutasi keluar, ubah status siswa menjadi 'mutasi_keluar'
        if ($request->jenis_mutasi === 'keluar') {
            Siswa::where('id', $request->siswa_id)->update(['status_siswa' => 'mutasi_keluar']);
        }

        AuditTrailService::catat('CREATE', 'mutasi_siswa', $mutasi->id, null, ['jenis' => $mutasi->jenis_mutasi]);

        return redirect()->route('admin.mutasi.index')->with('success', 'Data mutasi siswa berhasil dicatat!');
    }
}
