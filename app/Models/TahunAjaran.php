<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    /**
     * Relasi ke semester dalam tahun ajaran ini
     */
    public function semester()
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi ke kelas yang berjalan pada tahun ajaran ini
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'tahun_ajaran_id');
    }
}
