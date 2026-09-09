<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Membuat tabel siswas dengan skema final.
     * Sudah mencakup: foto, status, alasan_nonaktif, tahun_ajaran_lulus_id.
     * (Kolom nama_orang_tua sengaja tidak disertakan karena tidak dipakai.)
     */
    public function up(): void
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();

            // Kode RFID kartu fisik siswa (nullable: siswa tanpa kartu tetap bisa didata)
            $table->string('rfid_code')->nullable()->unique();

            $table->string('nisn')->unique();
            $table->string('nama_siswa');
            $table->string('no_hp_orang_tua');   // Digunakan oleh WhatsApp Gateway

            // Pengelompokan siswa ke dalam kelas
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            // Status keaktifan: Aktif | Alumni | Tidak Aktif
            // VARCHAR(50) dipilih agar fleksibel untuk nilai status baru tanpa perlu migrate ulang
            $table->string('status', 50)->default('Aktif');

            // Alasan spesifik saat status = 'Tidak Aktif' (Pindah Sekolah, Meninggal Dunia, dll.)
            $table->string('alasan_nonaktif')->nullable();

            // Tahun ajaran saat siswa dinyatakan lulus/Alumni
            $table->foreignId('tahun_ajaran_lulus_id')
                  ->nullable()
                  ->constrained('tahun_ajarans')
                  ->nullOnDelete();

            // Path foto profil siswa (disimpan di storage/app/public/siswa/)
            $table->string('foto')->nullable();

            $table->timestamps();
        });
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