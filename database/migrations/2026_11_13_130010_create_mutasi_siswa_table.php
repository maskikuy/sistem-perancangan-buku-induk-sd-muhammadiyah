<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel mutasi_siswa mencatat perpindahan siswa (mutasi masuk atau mutasi keluar).
     */
    public function up(): void
    {
        Schema::create('mutasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->enum('jenis_mutasi', ['masuk', 'keluar']);
            $table->date('tanggal_mutasi');
            $table->string('sekolah_asal_tujuan', 120);
            $table->text('alasan');
            $table->string('no_surat', 60)->nullable();
            $table->string('dokumen_pendukung')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_siswa');
    }
};
