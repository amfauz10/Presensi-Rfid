<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PERBAIKAN KONSISTENSI DOMAIN NILAI: presensis.status -> ENUM.
     *
     * `presensis.status` disimpan sebagai string bebas sejak awal, sedangkan
     * `log_notifikasis.status_presensi` (tabel yang secara logis mengacu ke
     * domain nilai yang SAMA - status kehadiran) sudah pakai
     * enum('Hadir','Terlambat','Sakit','Izin','Alpa'). Karena presensis.status
     * masih bebas, kolom ini rawan typo/nilai liar (mis. 'hadir' huruf kecil,
     * 'HADIR', atau salah ketik) yang tidak akan pernah bisa terjadi di
     * log_notifikasis. Penelusuran ke seluruh kode aplikasi (PresensiController,
     * form presensi manual) menunjukkan hanya 5 nilai yang benar-benar dipakai:
     * Hadir, Terlambat (dari tap RFID), Sakit, Izin, Alpa (dari presensi manual).
     *
     * Migrasi ini menyamakan domain nilai `presensis.status` dengan
     * `log_notifikasis.status_presensi` lewat ENUM yang identik.
     *
     * Sebelum ALTER, nilai lama yang menyimpang (kosong/NULL/ejaan berbeda)
     * dinormalisasi dulu ke 'Alpa' supaya ALTER tidak gagal karena melanggar
     * domain ENUM baru.
     */
    public function up(): void
    {
        DB::table('presensis')
            ->whereNotIn('status', ['Hadir', 'Terlambat', 'Sakit', 'Izin', 'Alpa'])
            ->update(['status' => 'Alpa']);

        DB::statement("ALTER TABLE presensis MODIFY status ENUM('Hadir','Terlambat','Sakit','Izin','Alpa') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE presensis MODIFY status VARCHAR(255) NOT NULL");
    }
};
