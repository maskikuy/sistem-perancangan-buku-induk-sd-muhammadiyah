<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $taAktif  = TahunAjaran::where('is_aktif', true)->first();
        $semAktif = Semester::where('is_aktif', true)->first();
        $daftarKelas = Kelas::orderBy('tingkat')->get();

        $kelasId = $request->input('kelas_id', $daftarKelas->first()?->id);

        $jadwal = Jadwal::with(['mataPelajaran', 'guru', 'kelas'])
                        ->when($kelasId, fn($q) => $q->where('kelas_id', $kelasId))
                        ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu')")
                        ->orderBy('jam_mulai')
                        ->get();

        $daftarMapel = MataPelajaran::orderBy('nama_mapel')->get();
        $daftarGuru  = Guru::orderBy('nama_lengkap')->get();

        return view('admin.jadwal.index', compact('jadwal', 'daftarKelas', 'daftarMapel', 'daftarGuru', 'kelasId', 'taAktif', 'semAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'          => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
            'guru_id'           => ['required', 'exists:guru,id'],
            'hari'              => ['required', 'in:senin,selasa,rabu,kamis,jumat,sabtu'],
            'jam_mulai'         => ['required'],
            'jam_selesai'       => ['required', 'after:jam_mulai'],
        ]);

        $semAktif = Semester::where('is_aktif', true)->first();

        $jadwal = Jadwal::create([
            'kelas_id'          => $request->kelas_id,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'guru_id'           => $request->guru_id,
            'semester_id'       => $semAktif ? $semAktif->id : 1,
            'hari'              => $request->hari,
            'jam_mulai'         => $request->jam_mulai,
            'jam_selesai'       => $request->jam_selesai,
        ]);

        AuditTrailService::catat('CREATE', 'jadwal', $jadwal->id, null, ['hari' => $jadwal->hari, 'jam' => $jadwal->jam_mulai]);

        return redirect()->route('admin.jadwal.index', ['kelas_id' => $request->kelas_id])->with('success', 'Jadwal pelajaran berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kelasId = $jadwal->kelas_id;
        $jadwal->delete();

        AuditTrailService::catat('DELETE', 'jadwal', $id);

        return redirect()->route('admin.jadwal.index', ['kelas_id' => $kelasId])->with('success', 'Jadwal pelajaran berhasil dihapus!');
    }
}
