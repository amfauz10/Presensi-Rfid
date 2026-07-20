<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pengaman: Buat tabel hanya jika belum ada di database
        if (!Schema::hasTable('siswas')) {
            Schema::create('siswas', function (Blueprint $table) {
                $table->id();
                $table->string('rfid_code')->unique();
                $table->string('nisn')->unique();
                $table->string('nama_siswa');
                $table->string('no_hp_orang_tua'); // Integrasi WhatsApp Gateway
                
                // Menghubungkan langsung ke id milik tabel kelas yang sudah terbuat di awal
                $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
                
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('siswas');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};