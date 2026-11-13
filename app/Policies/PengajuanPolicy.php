<?php

namespace App\Policies;

use App\Models\Pengajuan;
use App\Models\User;

class PengajuanPolicy
{
    /**
     * Admin memiliki akses penuh
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Wali hanya boleh melihat pengajuan yang ia buat sendiri
     * Guru boleh melihat pengajuan yang terkait siswa di kelasnya / piket
     */
    public function view(User $user, Pengajuan $pengajuan): bool
    {
        if ($user->hasRole('guru')) {
            return true;
        }

        if ($user->hasRole('wali') && $user->wali) {
            return $pengajuan->wali_id === $user->wali->id;
        }

        return false;
    }

    /**
     * Wali boleh membatalkan pengajuan miliknya jika status masih 'menunggu'
     */
    public function batalkan(User $user, Pengajuan $pengajuan): bool
    {
        if ($user->hasRole('wali') && $user->wali) {
            return $pengajuan->wali_id === $user->wali->id && $pengajuan->status === 'menunggu';
        }

        return false;
    }

    /**
     * Guru atau Admin yang berhak memproses / menyetujui pengajuan
     */
    public function proses(User $user, Pengajuan $pengajuan): bool
    {
        return $user->hasRole('guru') || $user->hasRole('admin');
    }
}
