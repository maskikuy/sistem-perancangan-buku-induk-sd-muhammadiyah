<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditTrailService
{
    /**
     * Mencatat peristiwa ke tabel audit_logs
     *
     * @param string $aksi (LOGIN, CREATE, UPDATE, DELETE, APPROVE, REJECT, BATAL, SELESAI)
     * @param string $tabelTerkait
     * @param int|null $recordId
     * @param array|null $dataLama
     * @param array|null $dataBaru
     * @return AuditLog
     */
    public static function catat(
        string $aksi,
        string $tabelTerkait,
        ?int $recordId = null,
        ?array $dataLama = null,
        ?array $dataBaru = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => Auth::id(),
            'aksi' => strtoupper($aksi),
            'tabel_terkait' => $tabelTerkait,
            'record_id' => $recordId,
            'data_lama' => $dataLama,
            'data_baru' => $dataBaru,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'created_at' => now(),
        ]);
    }
}
