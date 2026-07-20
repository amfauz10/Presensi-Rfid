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
        // Pastikan tabel tidak dibuat ulang jika entah bagaimana sudah terbentuk
        if (!Schema::hasTable('presensis')) {
            Schema::create('presensis', function (Blueprint $table) {
                $table->id();
                $table->string('rfid_code');      
                $table->dateTime('waktu_masuk');  
                $table->string('status');         
                $table->unsignedBigInteger('kelas_id')->nullable();
                $table->timestamps();
            });

            // Pasang foreign key terpisah dengan try-catch agar antrean tetap aman
            try {
                Schema::table('presensis', function (Blueprint $table) {
                    $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Abaikan jika tabel kelas belum siap di antrean awal
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        Schema::dropIfExists('presensis');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
};