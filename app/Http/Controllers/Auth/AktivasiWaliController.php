<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Wali;
use App\Services\AuditTrailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AktivasiWaliController extends Controller
{
    /**
     * Tampilkan form aktivasi akun mandiri untuk wali murid
     */
    public function index()
    {
        return view('auth.aktivasi');
    }

    /**
     * Proses verifikasi NISN anak + kode aktivasi dari sekolah
     */
    public function aktivasi(Request $request)
    {
        $request->validate([
            'nisn'          => ['required', 'string', 'size:10'],
            'kode_aktivasi' => ['required', 'string'],
            'password'      => ['required', 'confirmed', Password::min(6)],
        ], [
            'nisn.required'          => 'NISN anak wajib diisi.',
            'nisn.size'              => 'NISN harus berjumlah 10 digit angka.',
            'kode_aktivasi.required' => 'Kode aktivasi dari sekolah wajib diisi.',
            'password.confirmed'     => 'Konfirmasi kata sandi tidak cocok.',
            'password.min'           => 'Kata sandi minimal 6 karakter.',
        ]);

        // 1. Cari siswa berdasarkan NISN
        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (! $siswa) {
            return back()->withInput()->withErrors([
                'nisn' => 'Siswa dengan NISN tersebut tidak ditemukan di Buku Induk sekolah.',
            ]);
        }

        // 2. Cari wali dari siswa ini yang memiliki kode aktivasi yang cocok
        $wali = $siswa->wali()
                      ->where('kode_aktivasi', strtoupper(trim($request->kode_aktivasi)))
                      ->first();

        if (! $wali) {
            return back()->withInput()->withErrors([
                'kode_aktivasi' => 'Kode aktivasi tidak valid atau tidak cocok dengan data siswa tersebut.',
            ]);
        }

        // 3. Update password user wali dan status aktivasi
        $user = $wali->user;
        if (! $user) {
            // Buat user baru jika belum ada
            $user = User::create([
                'name'       => $wali->nama_lengkap,
                'username'   => $request->nisn,
                'email'      => null,
                'no_hp'      => $wali->no_telepon,
                'role_aktif' => 'wali',
                'password'   => Hash::make($request->password),
                'is_aktif'   => true,
            ]);
            $user->assignRole('wali');
            $wali->update(['user_id' => $user->id]);
        } else {
            $user->update([
                'password' => Hash::make($request->password),
                'is_aktif' => true,
            ]);
        }

        $wali->update([
            'is_teraktivasi' => true,
        ]);

        // 4. Catat ke audit trail
        AuditTrailService::catat('AKTIVASI_AKUN', 'wali', $wali->id, null, ['nisn' => $request->nisn]);

        // 5. Login otomatis dan set session anak aktif
        Auth::login($user);
        session(['anak_aktif_id' => $siswa->id]);

        return redirect()->route('wali.dashboard')->with('success', 'Selamat, akun wali murid berhasil diaktivasi!');
    }
}
