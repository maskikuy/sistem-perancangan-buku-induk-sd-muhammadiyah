<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wali extends Model
{
    use HasFactory;

    protected $table = 'wali';

    protected $fillable = [
        'user_id',
        'nik',
        'nama_lengkap',
        'hubungan',
        'pendidikan_terakhir',
        'pekerjaan',
        'penghasilan_bulanan',
        'no_telepon',
        'alamat',
        'kode_aktivasi',
        'is_teraktivasi',
    ];

    protected $casts = [
        'is_teraktivasi' => 'boolean',
    ];

    /**
     * Akun user wali murid untuk login sistem/PWA
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Anak kandung / asuh melalui tabel pivot wali_siswa (Mendukung multi-anak)
     */
    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'wali_siswa', 'wali_id', 'siswa_id')
                    ->withPivot('hubungan', 'is_kontak_utama')
                    ->withTimestamps();
    }

    /**
     * Daftar penjemput alternatif yang didaftarkan oleh wali ini
     */
    public function penjemputAlternatif()
    {
        return $this->hasMany(PenjemputAlternatif::class, 'wali_id');
    }

    /**
     * Riwayat pengajuan izin & penjemputan oleh wali ini
     */
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'wali_id');
    }
}
