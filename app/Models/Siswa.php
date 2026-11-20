<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nisn',
        'nis',
        'nama_lengkap',
        'nama_panggilan',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'kewarganegaraan',
        'anak_ke',
        'jumlah_saudara',
        'status_keluarga',
        'alamat_jalan',
        'desa_kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'kode_pos',
        'tinggal_bersama',
        'jarak_ke_sekolah_km',
        'berat_badan_kg',
        'tinggi_badan_cm',
        'golongan_darah',
        'riwayat_penyakit',
        'foto',
        'tanggal_diterima',
        'diterima_di_kelas',
        'status_siswa',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_diterima' => 'date',
        'jarak_ke_sekolah_km' => 'decimal:1',
        'berat_badan_kg' => 'decimal:1',
        'tinggi_badan_cm' => 'decimal:1',
    ];

    /**
     * Orang tua / wali murid melalui tabel pivot wali_siswa
     */
    public function wali()
    {
        return $this->belongsToMany(Wali::class, 'wali_siswa', 'siswa_id', 'wali_id')
                    ->withPivot('hubungan', 'is_kontak_utama')
                    ->withTimestamps();
    }

    /**
     * Riwayat kelas dan penempatan rombel
     */
    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class, 'siswa_id');
    }

    /**
     * Mendapatkan kelas aktif saat ini
     */
    public function kelasAktif()
    {
        return $this->hasOne(RiwayatKelas::class, 'siswa_id')
                    ->where('status_riwayat', 'aktif')
                    ->latestOfMany();
    }

    /**
     * Rekam mutasi siswa (masuk/keluar)
     */
    public function mutasi()
    {
        return $this->hasMany(MutasiSiswa::class, 'siswa_id');
    }

    /**
     * Presensi harian siswa
     */
    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'siswa_id');
    }

    /**
     * Nilai akademik siswa
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }
    /**
     * Seluruh pengajuan izin dan penjemputan terkait siswa ini
     */
    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class, 'siswa_id');
    }

    /**
     * Daftar penjemput alternatif khusus untuk siswa ini
     */
    public function penjemputAlternatif()
    {
        return $this->hasMany(PenjemputAlternatif::class, 'siswa_id');
    }

    /**
     * Catatan wali kelas dan rekomendasi kenaikan kelas
     */
    public function catatanWaliKelas()
    {
        return $this->hasMany(CatatanWaliKelas::class, 'siswa_id');
    }
}
