<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::with('semester')->latest('id')->get();
        return view('admin.tahun-ajaran.index', compact('tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:20', 'unique:tahun_ajaran,nama'],
        ]);

        $ta = TahunAjaran::create([
            'nama' => $request->nama,
            'is_aktif' => false,
        ]);

        // Buat otomatis 2 semester untuk tahun ajaran baru
        Semester::create([
            'tahun_ajaran_id' => $ta->id,
            'semester' => 'ganjil',
            'is_aktif' => false,
        ]);
        Semester::create([
            'tahun_ajaran_id' => $ta->id,
            'semester' => 'genap',
            'is_aktif' => false,
        ]);

        AuditTrailService::catat('CREATE', 'tahun_ajaran', $ta->id, null, ['nama' => $ta->nama]);

        return redirect()->route('admin.tahun-ajaran.index')->with('success', "Tahun ajaran {$ta->nama} berhasil ditambahkan!");
    }

    public function setAktif($id, $semesterId)
    {
        // Nonaktifkan semua TA dan Semester
        TahunAjaran::query()->update(['is_aktif' => false]);
        Semester::query()->update(['is_aktif' => false]);

        // Aktifkan yang dipilih
        $ta = TahunAjaran::findOrFail($id);
        $ta->update(['is_aktif' => true]);

        $sem = Semester::findOrFail($semesterId);
        $sem->update(['is_aktif' => true]);

        AuditTrailService::catat('UPDATE', 'tahun_ajaran', $ta->id, null, ['status' => 'Diaktifkan', 'semester' => $sem->semester]);

        return redirect()->route('admin.tahun-ajaran.index')->with('success', "Tahun ajaran {$ta->nama} Semester {$sem->semester} berhasil diaktifkan!");
    }
}
