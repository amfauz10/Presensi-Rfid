<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel tahun_ajarans.
     *
     * Tabel ini sebelumnya tidak pernah dimigrasikan padahal sudah dipakai
     * oleh model App\Models\TahunAjaran dan banyak controller
     * (Dashboard, Laporan, Presensi Manual, TahunAjaranController),
     * sehingga menyebabkan error "no such table: tahun_ajarans" / 
     * "Unknown column" di banyak halaman.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tahun_ajarans')) {
            Schema::create('tahun_ajarans', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->date('tanggal_mulai');
                $table->date('tanggal_selesai');
                // status: 1 = aktif, 0 = tidak aktif (hanya boleh ada 1 yang aktif)
                $table->boolean('status')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_ajarans');
    }
};
