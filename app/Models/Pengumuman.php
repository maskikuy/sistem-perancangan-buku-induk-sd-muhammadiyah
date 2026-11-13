<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'sasaran',
        'kelas_id',
        'pembuat_user_id',
        'tanggal_terbit',
        'lampiran',
    ];

    protected $casts = [
        'tanggal_terbit' => 'datetime',
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'pembuat_user_id');
    }
}
