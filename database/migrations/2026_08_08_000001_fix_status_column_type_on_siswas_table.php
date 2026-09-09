<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * REVISI DOSEN (Poin 1 & 2): Perbaikan tipe kolom.
 *
 * Migration '2026_07_18_000003_add_status_to_siswas_table' semula bermaksud
 * membuat kolom 'status' bertipe string bebas, namun karena kolom tersebut
 * ternyata sudah lebih dulu ada di database sebagai ENUM('Aktif','Alumni')
 * (dibuat manual/versi lama), migration itu dilewati oleh guard
 * Schema::hasColumn() dan tipe ENUM lama tetap terpakai. Akibatnya nilai
 * baru seperti 'Pindah' dan 'Meninggal' ditolak MySQL (Data truncated).
 *
 * Migration ini memaksa ubah tipe kolom menjadi VARCHAR(50) agar menerima
 * nilai status apa pun ke depannya, tanpa perlu migration/enum tambahan
 * setiap kali ada status baru. Tidak ada tabel baru, tidak ada kolom baru.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE `siswas` MODIFY `status` VARCHAR(50) NOT NULL DEFAULT 'Aktif'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE `siswas` MODIFY `status` ENUM('Aktif','Alumni') NOT NULL DEFAULT 'Aktif'");
    }
};
