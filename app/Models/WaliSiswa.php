<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WaliSiswa extends Pivot
{
    protected $table = 'wali_siswa';

    protected $fillable = [
        'wali_id',
        'siswa_id',
        'hubungan',
        'is_kontak_utama',
    ];

    protected $casts = [
        'is_kontak_utama' => 'boolean',
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
