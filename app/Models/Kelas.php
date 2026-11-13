<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'tahun_ajaran_id',
        'guru_id',
        'nama_kelas',
        'tingkat',
        'kapasitas',
    ];

    /**
     * Tahun ajaran kelas
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Wali kelas (Guru)
     */
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    /**
     * Siswa yang terdaftar di kelas ini melalui riwayat kelas
     */
    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class, 'kelas_id');
    }

    /**
     * Jadwal pelajaran di kelas ini
     */
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'kelas_id');
    }
}
