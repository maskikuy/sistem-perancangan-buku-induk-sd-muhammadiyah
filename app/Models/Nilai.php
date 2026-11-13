<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'mata_pelajaran_id',
        'semester_id',
        'guru_id',
        'jenis_nilai',
        'nilai_angka',
        'capaian_kompetensi',
    ];

    protected $casts = [
        'nilai_angka' => 'decimal:2',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
