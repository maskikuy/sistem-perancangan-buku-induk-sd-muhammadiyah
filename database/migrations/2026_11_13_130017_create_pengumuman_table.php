<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pengumuman mencatat maklumat sekolah kepada semua wali atau per kelas tertentu.
     */
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->text('isi');
            $table->enum('sasaran', ['semua', 'guru', 'wali_semua', 'per_kelas']);
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('pembuat_user_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('tanggal_terbit');
            $table->string('lampiran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
