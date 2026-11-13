<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wali;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AkunWaliController extends Controller
{
    public function index(Request $request)
    {
        $query = Wali::with(['user', 'siswa']);

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('no_telepon', 'like', "%{$cari}%")
                  ->orWhereHas('siswa', function ($q) use ($cari) {
                      $q->where('nama_lengkap', 'like', "%{$cari}%")
                        ->orWhere('nisn', 'like', "%{$cari}%");
                  });
        }

        $wali = $query->latest('id')->paginate(15)->withQueryString();
        return view('admin.akun-wali.index', compact('wali'));
    }

    public function generateKode($id)
    {
        $wali = Wali::findOrFail($id);
        $kodeBaru = 'AKT-' . strtoupper(Str::random(4));
        $wali->update(['kode_aktivasi' => $kodeBaru, 'is_teraktivasi' => false]);

        AuditTrailService::catat('UPDATE', 'wali', $wali->id, null, ['aksi' => 'Generate Ulang Kode Aktivasi', 'kode' => $kodeBaru]);

        return back()->with('success', "Kode aktivasi baru untuk {$wali->nama_lengkap} adalah: {$kodeBaru}");
    }

    public function resetPassword(Request $request, $id)
    {
        $wali = Wali::findOrFail($id);
        $user = $wali->user;

        if ($user) {
            $user->update([
                'password' => Hash::make('password123'),
            ]);
            AuditTrailService::catat('UPDATE', 'users', $user->id, null, ['aksi' => 'Reset Password Wali ke Default']);
            return back()->with('success', "Kata sandi untuk {$wali->nama_lengkap} berhasil direset ke: password123");
        }

        return back()->with('error', 'Akun user belum terdaftar untuk wali ini.');
    }
}
