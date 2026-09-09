<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PERBAIKAN LANJUTAN: rfid_code di `presensis` dibuat nullable.
     *
     * Kolom ini dari awal dibuat NOT NULL (lihat 2026_06_20_154137_create
     * _presensis_table.php). Karena itu, sebelum perbaikan relasi kemarin,
     * siswa yang TIDAK punya kartu RFID (rfid_code = NULL di tabel siswas)
     * terpaksa diberi kode semu 'MANUAL_<id>' hanya supaya insert ke
     * `presensis` tidak melanggar constraint NOT NULL ini.
     *
     * Sekarang relasi resminya sudah lewat `siswa_id` (foreign key asli),
     * sehingga `rfid_code` di sini seharusnya hanya catatan mentah "kartu apa
     * yang discan saat itu" - dan untuk presensi manual (tanpa scan kartu),
     * nilainya memang seharusnya boleh kosong. Kalau tetap NOT NULL, hack
     * 'MANUAL_<id>' yang sudah dihapus dari kode aplikasi akan terpaksa balik
     * lagi, padahal itu justru sumber masalah semula.
     */
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->string('rfid_code')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->string('rfid_code')->nullable(false)->change();
        });
    }
};
