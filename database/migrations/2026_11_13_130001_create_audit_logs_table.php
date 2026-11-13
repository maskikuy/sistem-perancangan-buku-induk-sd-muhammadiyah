<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel audit_logs mencatat seluruh rekaman jejak audit sistem:
     * Siapa (user_id), melakukan apa (aksi), di tabel mana, kapan, data sebelum dan sesudahnya.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('aksi', 50); // LOGIN, CREATE, UPDATE, DELETE, APPROVE, REJECT
            $table->string('tabel_terkait', 50);
            $table->unsignedBigInteger('record_id')->nullable();
            $table->longText('data_lama')->nullable();
            $table->longText('data_baru')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
