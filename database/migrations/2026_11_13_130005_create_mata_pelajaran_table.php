<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel mata_pelajaran mencatat daftar mapel termasuk mapel ciri khusus Muhammadiyah (Ismuba).
     */
    public function up(): void
    {
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mapel', 20)->unique(); // PAI-01, KMD-01, MTK-01
            $table->string('nama_mapel', 100);
            $table->enum('kelompok', ['umum', 'muatan_lokal', 'ciri_khusus_muhammadiyah']);
            $table->decimal('kkm', 5, 2)->default(75.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};
