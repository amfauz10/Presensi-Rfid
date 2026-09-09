<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PERBAIKAN STRUKTUR DATABASE: Relasi Log Notifikasi -> Siswa.
     *
     * Tabel `log_notifikasis` sebelumnya menyimpan salinan penuh data siswa
     * (rfid_code, nama_siswa, no_hp_orang_tua) tanpa foreign key sama sekali.
     * Ini adalah redundansi data murni: informasi yang sama sudah ada di
     * tabel `siswas`, sehingga jika data siswa berubah (mis. nomor HP orang
     * tua diperbarui), riwayat log lama tidak lagi konsisten dengan data induk
     * dan tidak bisa di-join langsung ke tabel siswa.
     *
     * Migrasi ini menambahkan `siswa_id` sebagai foreign key ke `siswas.id`
     * agar log notifikasi dapat ditelusuri kembali secara relasional.
     * Kolom snapshot (nama_siswa, no_hp_orang_tua, rfid_code) tetap
     * dipertahankan dengan sengaja karena tabel ini berfungsi sebagai log/audit
     * trail pengiriman WhatsApp - nilai pada saat kejadian tetap harus terekam
     * apa adanya walau data induk siswa kelak berubah atau siswa dihapus.
     * Karena itu FK memakai nullOnDelete(), bukan cascadeOnDelete(), supaya
     * riwayat notifikasi tidak ikut hilang ketika data siswa dihapus.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('log_notifikasis', 'siswa_id')) {
            Schema::table('log_notifikasis', function (Blueprint $table) {
                $table->foreignId('siswa_id')
                    ->nullable()
                    ->after('rfid_code')
                    ->constrained('siswas')
                    ->nullOnDelete();
            });
        }

        DB::table('siswas')
            ->select('id', 'rfid_code')
            ->whereNotNull('rfid_code')
            ->where('rfid_code', '!=', '')
            ->orderBy('id')
            ->chunk(200, function ($siswas) {
                foreach ($siswas as $siswa) {
                    DB::table('log_notifikasis')
                        ->where('rfid_code', $siswa->rfid_code)
                        ->whereNull('siswa_id')
                        ->update(['siswa_id' => $siswa->id]);
                }
            });

        DB::table('siswas')->select('id')->orderBy('id')->chunk(200, function ($siswas) {
            foreach ($siswas as $siswa) {
                DB::table('log_notifikasis')
                    ->where('rfid_code', 'MANUAL_' . $siswa->id)
                    ->whereNull('siswa_id')
                    ->update(['siswa_id' => $siswa->id]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('log_notifikasis', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropColumn('siswa_id');
        });
    }
};
