<?php

namespace App\Services;

use App\Models\Notifikasi;

class NotifikasiService
{
    /**
     * Kirim notifikasi ke user tertentu
     *
     * @param int $userId
     * @param string $judul
     * @param string $pesan
     * @param string $tipe
     * @param string|null $tautan
     * @return Notifikasi
     */
    public static function kirim(
        int $userId,
        string $judul,
        string $pesan,
        string $tipe = 'info',
        ?string $tautan = null
    ): Notifikasi {
        return Notifikasi::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'tautan' => $tautan,
            'is_dibaca' => false,
        ]);
    }
}
