<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel notifikasi menyimpan pesan masuk untuk user (status pengajuan, pengumuman, pengingat).
     */
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 100);
            $table->text('pesan');
            $table->string('tipe', 50); // pengajuan_baru, status_pengajuan, pengumuman, rapor
            $table->string('tautan', 255)->nullable();
            $table->boolean('is_dibaca')->default(false);
            $table->dateTime('dibaca_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
