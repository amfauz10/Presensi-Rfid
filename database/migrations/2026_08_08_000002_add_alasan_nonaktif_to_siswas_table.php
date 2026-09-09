<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * REVISI DOSEN (Poin 1 & 2 - disederhanakan): Alih-alih status terpisah
 * "Pindah" dan "Meninggal" (kurang etis ditampilkan blak-blakan sebagai label
 * di UI, dan kurang fleksibel untuk alasan lain seperti putus sekolah),
 * digabung menjadi 1 status umbrella "Tidak Aktif" pada kolom 'status' yang
 * sudah ada, ditambah 1 kolom baru 'alasan_nonaktif' untuk menyimpan alasan
 * spesifiknya (Pindah Sekolah / Meninggal Dunia / Putus Sekolah / Lainnya).
 * Ini satu-satunya kolom baru yang ditambahkan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            if (!Schema::hasColumn('siswas', 'alasan_nonaktif')) {
                $table->string('alasan_nonaktif')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('alasan_nonaktif');
        });
    }
};
