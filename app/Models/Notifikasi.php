<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'tautan',
        'is_dibaca',
        'dibaca_at',
    ];

    protected $casts = [
        'is_dibaca' => 'boolean',
        'dibaca_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
