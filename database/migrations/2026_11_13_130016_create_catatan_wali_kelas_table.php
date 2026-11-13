<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel catatan_wali_kelas mencatat deskripsi perkembangan siswa & rekomendasi kenaikan kelas untuk rapor.
     */
    public function up(): void
    {
        Schema::create('catatan_wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('guru')->cascadeOnDelete();
            $table->text('catatan');
            $table->enum('status_kenaikan', ['naik', 'tinggal', 'lulus', 'belum_ditentukan'])->default('belum_ditentukan');
            $table->timestamps();

            $table->unique(['siswa_id', 'kelas_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_wali_kelas');
    }
};
