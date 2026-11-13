<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    public $timestamps = false; // Hanya menggunakan created_at

    protected $fillable = [
        'user_id',
        'aksi',
        'tabel_terkait',
        'record_id',
        'data_lama',
        'data_baru',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * User yang melakukan aksi
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
