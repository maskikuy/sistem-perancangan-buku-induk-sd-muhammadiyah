<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Kolom yang dapat diisi secara mass-assignment
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'no_hp',
        'role_aktif',
        'is_aktif',
        'password',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi (JSON)
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe data casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_aktif' => 'boolean',
        ];
    }

    /**
     * Relasi ke profil Guru (jika user adalah guru)
     */
    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    /**
     * Relasi ke profil Wali Murid (jika user adalah wali)
     */
    public function wali()
    {
        return $this->hasOne(Wali::class, 'user_id');
    }

    /**
     * Relasi ke audit log aktivitas
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Relasi ke notifikasi
     */
    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }
}
