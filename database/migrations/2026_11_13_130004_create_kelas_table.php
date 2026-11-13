<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel kelas mencatat rombel belajar per tahun ajaran beserta wali kelasnya.
     */
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('guru')->restrictOnDelete(); // Wali kelas
            $table->string('nama_kelas', 50); // Contoh: "1 Abu Bakar", "4 Ahmad Dahlan"
            $table->unsignedTinyInteger('tingkat'); // 1 - 6
            $table->integer('kapasitas')->default(30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
