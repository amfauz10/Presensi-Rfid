<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PERBAIKAN INTEGRITAS: Maksimal Satu Tahun Ajaran Aktif.
     *
     * `TahunAjaran::aktif()` cuma menjalankan `where('status', 1)->first()`.
     * Tidak ada satupun constraint di database yang menjamin hanya boleh ada
     * SATU baris dengan status = 1. Kalau karena bug aplikasi atau input
     * manual ke database sampai ada 2 baris aktif, sistem akan diam-diam
     * memakai baris pertama yang ditemukan - padahal dampaknya besar (dashboard,
     * rekap bulanan, presensi RFID semuanya bergantung ke tahun ajaran aktif).
     *
     * MySQL/PostgreSQL tidak mendukung "unique index bersyarat" langsung pada
     * kolom boolean biasa (unique index pada `status` akan salah: itu akan
     * membatasi juga jumlah baris TIDAK aktif jadi maksimal 1, padahal yang
     * ingin dibatasi hanya baris yang AKTIF). Solusi standarnya: kolom
     * generated (virtual) yang bernilai 1 saat status aktif dan NULL saat
     * tidak aktif, lalu unique index dipasang di kolom generated itu. Unique
     * index mengizinkan banyak NULL, jadi baris tidak-aktif tetap bebas
     * jumlahnya, tapi baris aktif dijamin maksimal satu oleh database sendiri.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('tahun_ajarans', 'status_aktif_unik')) {
            DB::statement("
                ALTER TABLE tahun_ajarans
                ADD COLUMN status_aktif_unik TINYINT
                GENERATED ALWAYS AS (CASE WHEN status = 1 THEN 1 ELSE NULL END) VIRTUAL
            ");
        }

        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->unique('status_aktif_unik', 'tahun_ajarans_status_aktif_unique');
        });
    }

    public function down(): void
    {
        Schema::table('tahun_ajarans', function (Blueprint $table) {
            $table->dropUnique('tahun_ajarans_status_aktif_unique');
            $table->dropColumn('status_aktif_unik');
        });
    }
};
