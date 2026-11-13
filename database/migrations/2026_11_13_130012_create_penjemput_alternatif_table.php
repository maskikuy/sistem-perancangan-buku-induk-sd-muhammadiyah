<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel penjemput_alternatif menyimpan data orang terpercaya yang didaftarkan wali untuk menjemput anak.
     */
    public function up(): void
    {
        Schema::create('penjemput_alternatif', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wali_id')->constrained('wali')->cascadeOnDelete();
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->nullOnDelete();
            $table->string('nama_lengkap', 100);
            $table->string('nik', 16)->nullable();
            $table->string('hubungan', 50); // Paman, Kakek, Supir, Tetangga
            $table->string('no_telepon', 20);
            $table->string('foto_identitas')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjemput_alternatif');
    }
};
