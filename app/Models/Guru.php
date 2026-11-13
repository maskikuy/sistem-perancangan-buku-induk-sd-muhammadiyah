<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'gelar',
        'jenis_kelamin',
        'no_telepon',
        'alamat',
        'is_wali_kelas',
    ];

    protected $casts = [
        'is_wali_kelas' => 'boolean',
    ];

    /**
     * Akun user guru
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Kelas yang diampu sebagai wali kelas
     */
    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'guru_id');
    }

    /**
     * Jadwal mengajar
     */
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }

    /**
     * Helper nama dengan gelar
     */
    public function getNamaGelarAttribute()
    {
        return $this->gelar ? "{$this->nama_lengkap}, {$this->gelar}" : $this->nama_lengkap;
    }
}
