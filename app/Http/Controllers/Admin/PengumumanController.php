<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Pengumuman;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::with(['pembuat', 'kelas'])->latest('tanggal_terbit')->paginate(15);
        $daftarKelas = Kelas::orderBy('tingkat')->get();

        return view('admin.pengumuman.index', compact('pengumuman', 'daftarKelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'   => ['required', 'string', 'max:150'],
            'isi'     => ['required', 'string'],
            'sasaran' => ['required', 'in:semua,guru,wali_semua,per_kelas'],
        ]);

        $p = Pengumuman::create([
            'judul'           => $request->judul,
            'isi'             => $request->isi,
            'sasaran'         => $request->sasaran,
            'kelas_id'        => $request->sasaran === 'per_kelas' ? $request->kelas_id : null,
            'pembuat_user_id' => Auth::id(),
            'tanggal_terbit'  => now(),
        ]);

        AuditTrailService::catat('CREATE', 'pengumuman', $p->id, null, ['judul' => $p->judul]);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman sekolah berhasil diterbitkan!');
    }

    public function destroy($id)
    {
        $p = Pengumuman::findOrFail($id);
        $p->delete();

        AuditTrailService::catat('DELETE', 'pengumuman', $id);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus!');
    }
}
