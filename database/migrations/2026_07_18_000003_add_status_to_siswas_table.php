<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom status ke tabel siswas.
     *
     * Dipakai untuk menandai status siswa 'Aktif' atau 'Alumni'
     * (dipakai di AlumniController & TahunAjaranController@kenaikan/prosesKenaikan),
     * tapi kolom ini belum pernah dibuat lewat migrasi.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('siswas', 'status')) {
            Schema::table('siswas', function (Blueprint $table) {
                $table->string('status')->default('Aktif')->after('kelas_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
