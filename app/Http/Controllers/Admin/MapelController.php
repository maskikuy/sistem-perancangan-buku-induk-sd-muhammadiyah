<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = MataPelajaran::orderBy('kelompok')->orderBy('nama_mapel')->get();
        return view('admin.mapel.index', compact('mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mapel' => ['required', 'string', 'max:20', 'unique:mata_pelajaran,kode_mapel'],
            'nama_mapel' => ['required', 'string', 'max:100'],
            'kelompok'   => ['required', 'in:umum,muatan_lokal,ciri_khusus_muhammadiyah'],
            'kkm'        => ['required', 'numeric', 'between:0,100'],
        ]);

        $m = MataPelajaran::create($request->all());

        AuditTrailService::catat('CREATE', 'mata_pelajaran', $m->id, null, ['nama_mapel' => $m->nama_mapel]);

        return redirect()->route('admin.mapel.index')->with('success', "Mata pelajaran {$m->nama_mapel} berhasil ditambahkan!");
    }
}
