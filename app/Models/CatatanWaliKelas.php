<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatatanWaliKelas extends Model
{
    use HasFactory;

    protected $table = 'catatan_wali_kelas';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'semester_id',
        'guru_id',
        'catatan',
        'status_kenaikan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
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
