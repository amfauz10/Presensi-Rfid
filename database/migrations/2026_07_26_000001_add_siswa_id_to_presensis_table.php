<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PERBAIKAN STRUKTUR DATABASE: Relasi Presensi -> Siswa.
     *
     * Sebelumnya, tabel `presensis` hanya terhubung ke tabel `siswas` secara
     * "informal" lewat pencocokan string `rfid_code` (bukan foreign key asli).
     * Ini menimbulkan beberapa masalah:
     *  1. Tidak ada constraint referential integrity di level database
     *     (baris presensi bisa merujuk rfid_code yang sudah tidak ada di siswas).
     *  2. Siswa tanpa kartu RFID terpaksa memakai kode semu 'MANUAL_<id>'
     *     supaya tetap bisa "dicocokkan" di query (lihat SiswaController,
     *     PresensiController) - ini adalah gejala klasik desain relasi yang keliru.
     *  3. Join berbasis string (bukan integer + index FK) lebih lambat.
     *  4. Kode `$riwayat_presensi->pluck('siswa_id')` di PresensiController@dashboard
     *     sudah mengasumsikan kolom `siswa_id` ada, padahal belum pernah dibuat.
     *
     * Migrasi ini menambahkan kolom `siswa_id` sebagai foreign key yang benar
     * (references siswas.id) sebagai kunci relasi utama, sekaligus melakukan
     * backfill data lama berdasarkan rfid_code (termasuk pola 'MANUAL_<id>').
     * Kolom `rfid_code` tetap dipertahankan sebagai catatan mentah kartu yang
     * discan (nilai historis untuk audit), namun bukan lagi kunci relasi.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('presensis', 'siswa_id')) {
            Schema::table('presensis', function (Blueprint $table) {
                $table->foreignId('siswa_id')
                    ->nullable()
                    ->after('rfid_code')
                    ->constrained('siswas')
                    ->cascadeOnDelete();
            });
        }

        // --- Backfill data lama (siswa yang punya kartu RFID sungguhan) ---
        DB::table('siswas')
            ->select('id', 'rfid_code')
            ->whereNotNull('rfid_code')
            ->where('rfid_code', '!=', '')
            ->orderBy('id')
            ->chunk(200, function ($siswas) {
                foreach ($siswas as $siswa) {
                    DB::table('presensis')
                        ->where('rfid_code', $siswa->rfid_code)
                        ->whereNull('siswa_id')
                        ->update(['siswa_id' => $siswa->id]);
                }
            });

        // --- Backfill data lama (siswa tanpa RFID, memakai kode semu MANUAL_<id>) ---
        DB::table('siswas')->select('id')->orderBy('id')->chunk(200, function ($siswas) {
            foreach ($siswas as $siswa) {
                DB::table('presensis')
                    ->where('rfid_code', 'MANUAL_' . $siswa->id)
                    ->whereNull('siswa_id')
                    ->update(['siswa_id' => $siswa->id]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropColumn('siswa_id');
        });
    }
};
