<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiSiswa extends Model
{
    use HasFactory;

    protected $table = 'mutasi_siswa';

    protected $fillable = [
        'siswa_id',
        'jenis_mutasi',
        'tanggal_mutasi',
        'sekolah_asal_tujuan',
        'alasan',
        'no_surat',
        'dokumen_pendukung',
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
}
