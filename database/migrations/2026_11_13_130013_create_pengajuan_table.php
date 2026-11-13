<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pengajuan adalah SATU tabel polimorfis untuk:
     * - izin_tidak_masuk (sakit / urusan keluarga)
     * - penjemputan (penjemputan pulang lebih awal)
     * - surat (surat keterangan aktif/pindah)
     */
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan', 30)->unique(); // AJU-YYYYMMDD-XXXX
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('wali_id')->constrained('wali')->cascadeOnDelete();
            $table->enum('jenis', ['izin_tidak_masuk', 'penjemputan', 'surat']);
            
            // Rentang tanggal dan jam
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->time('jam_jemput')->nullable();
            $table->boolean('is_mendesak')->default(false);

            // Relasi penjemput
            $table->foreignId('penjemput_alternatif_id')->nullable()->constrained('penjemput_alternatif')->nullOnDelete();
            $table->text('alasan');
            $table->string('bukti_dokumen')->nullable();

            // Status Workflow
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'ditolak',
                'selesai',
                'dibatalkan',
                'kedaluwarsa'
            ])->default('menunggu');

            // Tindakan Guru / Wali Kelas
            $table->foreignId('diproses_oleh_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_persetujuan')->nullable();

            // Tindakan Petugas Piket (Gerbang)
            $table->dateTime('dijemput_pada')->nullable();
            $table->string('dijemput_oleh_nama', 100)->nullable();
            $table->foreignId('petugas_piket_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
