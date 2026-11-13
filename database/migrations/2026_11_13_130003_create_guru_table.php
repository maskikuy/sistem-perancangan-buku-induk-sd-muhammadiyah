<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel guru menyimpan profil pengajar & wali kelas yang terhubung dengan akun users.
     */
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('nip', 30)->unique()->nullable();
            $table->string('nama_lengkap', 100);
            $table->string('gelar', 30)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->boolean('is_wali_kelas')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
