<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            // Menambahkan kolom nama_orang_tua tepat setelah nama_siswa
            $table->string('nama_orang_tua')->nullable()->after('nama_siswa');
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropColumn('nama_orang_tua');
        });
    }
};