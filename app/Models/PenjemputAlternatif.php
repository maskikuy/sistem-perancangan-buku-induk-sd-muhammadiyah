<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjemputAlternatif extends Model
{
    use HasFactory;

    protected $table = 'penjemput_alternatif';

    protected $fillable = [
        'wali_id',
        'siswa_id',
        'nama_lengkap',
        'nik',
        'hubungan',
        'no_telepon',
        'foto_identitas',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    public function wali()
    {
        return $this->belongsTo(Wali::class, 'wali_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
