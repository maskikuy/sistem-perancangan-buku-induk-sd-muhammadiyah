<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel riwayat_kelas mencatat rekam jejak kelas siswa tiap semester/tahun ajaran
     * (kenaikan kelas, tinggal kelas, atau kelulusan).
     */
    public function up(): void
    {
        Schema::create('riwayat_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->enum('status_riwayat', ['aktif', 'naik_kelas', 'tinggal_kelas', 'lulus'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kelas');
    }
};
