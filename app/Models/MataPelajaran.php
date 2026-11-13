<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kelompok',
        'kkm',
    ];

    protected $casts = [
        'kkm' => 'decimal:2',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'mata_pelajaran_id');
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mata_pelajaran_id');
    }
}
