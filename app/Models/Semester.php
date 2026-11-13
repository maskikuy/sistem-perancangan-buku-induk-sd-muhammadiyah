<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semester';

    protected $fillable = [
        'tahun_ajaran_id',
        'semester',
        'is_aktif',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Relasi ke Tahun Ajaran induk
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
