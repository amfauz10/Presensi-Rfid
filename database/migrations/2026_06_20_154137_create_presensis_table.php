<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Membuat tabel presensis dengan skema final.
     * Sudah mencakup: siswa_id (FK), tahun_ajaran_id (FK), keterangan,
     *                 dokumen, sumber (ENUM rfid/manual), status (ENUM),
     *                 tanpa kolom waktu_masuk yang redundan.
     */
    public function up(): void
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();

            // Jejak mentah kartu RFID yang discan (audit trail), nullable
            // karena presensi manual tidak butuh rfid_code
            $table->string('rfid_code')->nullable();

            // Relasi utama ke siswa (FK asli, menggantikan rfid_code sebagai kunci relasi)
            $table->foreignId('siswa_id')
                  ->nullable()
                  ->constrained('siswas')
                  ->cascadeOnDelete();

            // Status kehadiran dengan domain nilai terbatas (mencegah typo/nilai liar)
            $table->enum('status', ['Hadir', 'Terlambat', 'Sakit', 'Izin', 'Alpa']);

            // Penanda asal data: 'rfid' = hasil tap kartu | 'manual' = input manual
            // Presensi hasil tap RFID hanya boleh ditimpa jika admin menyentuh baris secara eksplisit
            $table->enum('sumber', ['rfid', 'manual'])->nullable();

            // Kelas siswa saat presensi terjadi (snapshot, meski siswa kelak pindah kelas)
            $table->foreignId('kelas_id')
                  ->nullable()
                  ->constrained('kelas')
                  ->cascadeOnDelete();

            // Tahun ajaran aktif saat presensi terjadi
            $table->foreignId('tahun_ajaran_id')
                  ->nullable()
                  ->constrained('tahun_ajarans')
                  ->nullOnDelete();

            // Keterangan tambahan (diisi saat Sakit/Izin)
            $table->text('keterangan')->nullable();

            // Surat keterangan / dokumen pendukung (path file)
            $table->string('dokumen')->nullable();

            $table->timestamps();
        });
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