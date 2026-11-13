<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    /**
     * Admin/TU dan Kepala Sekolah memiliki akses penuh
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin') || $user->hasRole('kepsek')) {
            return true;
        }

        return null; // Lanjutkan ke method policy masing-masing
    }

    /**
     * Guru dan Wali Murid dapat melihat detail siswa
     */
    public function view(User $user, Siswa $siswa): bool
    {
        // Jika user adalah Guru
        if ($user->hasRole('guru')) {
            return true;
        }

        // Jika user adalah Wali Murid: WAJIB mengecek apakah siswa ini terhubung dengan akun walinya
        if ($user->hasRole('wali') && $user->wali) {
            return $user->wali->siswa()->where('siswa.id', $siswa->id)->exists();
        }

        return false;
    }

    /**
     * Hanya Admin yang dapat mengedit buku induk siswa
     */
    public function update(User $user, Siswa $siswa): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Hanya Admin yang dapat menghapus data buku induk
     */
    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->hasRole('admin');
    }
}
