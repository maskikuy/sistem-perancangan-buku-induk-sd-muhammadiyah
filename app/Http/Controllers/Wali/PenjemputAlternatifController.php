<?php

namespace App\Http\Controllers\Wali;

use App\Http\Controllers\Controller;
use App\Models\PenjemputAlternatif;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjemputAlternatifController extends Controller
{
    public function index()
    {
        $wali = Auth::user()->wali;
        $daftarPenjemput = $wali ? $wali->penjemputAlternatif : collect();

        return view('wali.penjemput.index', compact('daftarPenjemput'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'hubungan'     => ['required', 'string', 'max:50'],
            'no_telepon'   => ['required', 'string', 'max:20'],
            'nik'          => ['nullable', 'string', 'max:16'],
        ]);

        $wali = Auth::user()->wali;

        $penjemput = PenjemputAlternatif::create([
            'wali_id'      => $wali->id,
            'nama_lengkap' => $request->nama_lengkap,
            'hubungan'     => $request->hubungan,
            'no_telepon'   => $request->no_telepon,
            'nik'          => $request->nik,
            'is_aktif'     => true,
        ]);

        AuditTrailService::catat('CREATE', 'penjemput_alternatif', $penjemput->id, null, ['nama' => $penjemput->nama_lengkap]);

        return back()->with('success', "Penjemput terpercaya {$penjemput->nama_lengkap} berhasil didaftarkan!");
    }

    public function destroy($id)
    {
        $wali = Auth::user()->wali;
        $penjemput = PenjemputAlternatif::where('wali_id', $wali->id)->findOrFail($id);
        $penjemput->delete();

        AuditTrailService::catat('DELETE', 'penjemput_alternatif', $id);

        return back()->with('success', 'Data penjemput berhasil dihapus.');
    }
}
