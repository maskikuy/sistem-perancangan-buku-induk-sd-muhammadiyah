<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel tahun_ajaran dan semester menyimpan data periode akademik aktif sekolah.
     */
    public function up(): void
    {
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 20); // Contoh: "2026/2027"
            $table->boolean('is_aktif')->default(false);
            $table->timestamps();
        });

        Schema::create('semester', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->enum('semester', ['ganjil', 'genap']);
            $table->boolean('is_aktif')->default(false);
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semester');
        Schema::dropIfExists('tahun_ajaran');
    }
};
