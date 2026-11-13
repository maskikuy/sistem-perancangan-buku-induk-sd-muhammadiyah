<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel presensi mencatat kehadiran harian siswa di kelas,
     * termasuk status otomatis 'pulang_awal' saat penjemputan selesai.
     */
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa', 'pulang_awal']);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->foreignId('pengajuan_id')->nullable()->constrained('pengajuan')->nullOnDelete();
            $table->text('keterangan')->nullable();
            $table->foreignId('guru_id')->nullable()->constrained('guru')->nullOnDelete(); // Guru pencatat
            $table->timestamps();

            // Cegah duplikasi presensi untuk siswa di tanggal yang sama
            $table->unique(['siswa_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
