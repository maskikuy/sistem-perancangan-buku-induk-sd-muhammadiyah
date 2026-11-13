<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel wali_siswa adalah tabel pivot yang menghubungkan wali dengan satu atau banyak siswa (multi-anak).
     */
    public function up(): void
    {
        Schema::create('wali_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wali_id')->constrained('wali')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->enum('hubungan', ['ayah', 'ibu', 'wali']);
            $table->boolean('is_kontak_utama')->default(false);
            $table->timestamps();

            $table->unique(['wali_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wali_siswa');
    }
};
