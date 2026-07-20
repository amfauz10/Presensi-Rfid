<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom tahun_ajaran_lulus_id ke tabel siswas.
     *
     * Sebelumnya, saat siswa dinaikkan status menjadi 'Alumni' (TahunAjaranController@prosesKenaikan),
     * sistem tidak pernah mencatat tahun ajaran mana yang aktif saat kelulusan terjadi.
     * Akibatnya, filter "Angkatan / Tahun Lulus" & "Tahun Aktif" di halaman Alumni
     * hanya menebak dari date('Y') / updated_at, bukan dari data Tahun Ajaran sesungguhnya.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('siswas', 'tahun_ajaran_lulus_id')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->foreignId('tahun_ajaran_lulus_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('tahun_ajarans')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_lulus_id']);
            $table->dropColumn('tahun_ajaran_lulus_id');
        });
    }
};
