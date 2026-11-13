<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $table = 'pengajuan';

    protected $fillable = [
        'nomor_pengajuan',
        'siswa_id',
        'wali_id',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_jemput',
        'is_mendesak',
        'penjemput_alternatif_id',
        'alasan',
        'bukti_dokumen',
        'status',
        'diproses_oleh_user_id',
        'catatan_persetujuan',
        'dijemput_pada',
        'dijemput_oleh_nama',
        'petugas_piket_user_id',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_mendesak' => 'boolean',
        'dijemput_pada' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function wali()
    {
        return $this->belongsTo(Wali::class, 'wali_id');
    }

    public function penjemputAlternatif()
    {
        return $this->belongsTo(PenjemputAlternatif::class, 'penjemput_alternatif_id');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh_user_id');
    }

    public function petugasPiket()
    {
        return $this->belongsTo(User::class, 'petugas_piket_user_id');
    }

    public function presensi()
    {
        return $this->hasOne(Presensi::class, 'pengajuan_id');
    }
}
