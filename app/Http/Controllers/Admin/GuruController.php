<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with(['user', 'kelasWali'])->orderBy('nama_lengkap')->get();
        return view('admin.guru.index', compact('guru'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'           => ['nullable', 'string', 'max:30', 'unique:guru,nip'],
            'nama_lengkap'  => ['required', 'string', 'max:100'],
            'gelar'         => ['nullable', 'string', 'max:30'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_telepon'    => ['nullable', 'string', 'max:20'],
            'username'      => ['required', 'string', 'max:50', 'unique:users,username'],
            'password'      => ['required', 'string', 'min:6'],
        ]);

        DB::beginTransaction();
        try {
            // Buat akun user
            $user = User::create([
                'name'       => $request->nama_lengkap . ($request->gelar ? ', ' . $request->gelar : ''),
                'username'   => $request->username,
                'email'      => $request->email,
                'no_hp'      => $request->no_telepon,
                'role_aktif' => 'guru',
                'password'   => Hash::make($request->password),
                'is_aktif'   => true,
            ]);
            $user->assignRole('guru');

            // Buat data guru
            $guru = Guru::create([
                'user_id'       => $user->id,
                'nip'           => $request->nip,
                'nama_lengkap'  => $request->nama_lengkap,
                'gelar'         => $request->gelar,
                'jenis_kelamin' => $request->jenis_kelamin,
                'no_telepon'    => $request->no_telepon,
                'alamat'        => $request->alamat,
                'is_wali_kelas' => false,
            ]);

            AuditTrailService::catat('CREATE', 'guru', $guru->id, null, ['nama' => $guru->nama_lengkap]);

            DB::commit();
            return redirect()->route('admin.guru.index')->with('success', "Data guru {$guru->nama_lengkap} beserta akun berhasil dibuat!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }
}
