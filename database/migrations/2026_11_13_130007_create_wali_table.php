<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel wali mencatat data profil orang tua / wali murid dan status kode aktivasinya.
     */
    public function up(): void
    {
        Schema::create('wali', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('nik', 16)->nullable();
            $table->string('nama_lengkap', 100);
            $table->enum('hubungan', ['ayah', 'ibu', 'wali']);
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('pekerjaan', 60)->nullable();
            $table->string('penghasilan_bulanan', 50)->nullable();
            $table->string('no_telepon', 20);
            $table->text('alamat')->nullable();
            $table->string('kode_aktivasi', 10)->nullable();
            $table->boolean('is_teraktivasi')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wali');
    }
};
