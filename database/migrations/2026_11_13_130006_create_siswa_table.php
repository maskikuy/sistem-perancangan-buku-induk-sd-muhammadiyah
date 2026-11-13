<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel siswa adalah inti Buku Induk Digital:
     * Menyimpan data identitas lengkap, fisik, alamat, dan status administratif siswa.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nisn', 10)->unique();
            $table->string('nis', 20)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('nama_panggilan', 50)->nullable();
            $table->string('nik', 16)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 60);
            $table->date('tanggal_lahir');
            $table->string('agama', 30)->default('Islam');
            $table->string('kewarganegaraan', 30)->default('WNI');
            $table->unsignedTinyInteger('anak_ke')->nullable();
            $table->unsignedTinyInteger('jumlah_saudara')->nullable();
            $table->enum('status_keluarga', ['anak_kandung', 'anak_angkat', 'anak_tiri'])->default('anak_kandung');
            
            // Alamat Lengkap
            $table->text('alamat_jalan');
            $table->string('desa_kelurahan', 60)->nullable();
            $table->string('kecamatan', 60)->nullable();
            $table->string('kabupaten_kota', 60)->default('Sleman');
            $table->string('provinsi', 60)->default('D.I. Yogyakarta');
            $table->string('kode_pos', 10)->nullable();
            $table->enum('tinggal_bersama', ['orang_tua', 'wali', 'asrama', 'lainnya'])->default('orang_tua');
            $table->decimal('jarak_ke_sekolah_km', 4, 1)->nullable();

            // Data Fisik & Kesehatan
            $table->decimal('berat_badan_kg', 4, 1)->nullable();
            $table->decimal('tinggi_badan_cm', 4, 1)->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'tidak_tahu'])->default('tidak_tahu');
            $table->text('riwayat_penyakit')->nullable();

            // Berkas & Riwayat Masuk
            $table->string('foto')->nullable();
            $table->date('tanggal_diterima');
            $table->string('diterima_di_kelas', 20)->default('1');
            $table->enum('status_siswa', ['aktif', 'lulus', 'mutasi_keluar', 'dikeluarkan', 'meninggal'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
