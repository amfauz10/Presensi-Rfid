<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom tahun_ajaran_id ke tabel presensis.
     *
     * Model App\Models\Presensi sudah mendefinisikan relasi belongsTo(TahunAjaran::class)
     * dan 'tahun_ajaran_id' ada di $fillable, tapi kolomnya belum pernah dibuat
     * lewat migrasi sehingga query di PresensiController selalu gagal.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('presensis', 'tahun_ajaran_id')) {
            Schema::table('presensis', function (Blueprint $table) {
                $table->foreignId('tahun_ajaran_id')
                    ->nullable()
                    ->after('kelas_id')
                    ->constrained('tahun_ajarans')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign(['tahun_ajaran_id']);
            $table->dropColumn('tahun_ajaran_id');
        });
    }
};
