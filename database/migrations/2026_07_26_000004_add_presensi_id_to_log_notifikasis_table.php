<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * PERBAIKAN STRUKTUR DATABASE: Relasi Log Notifikasi -> Presensi.
     *
     * Sebelumnya `log_notifikasis` hanya tertaut ke `siswas` (lewat siswa_id
     * yang ditambahkan di migrasi sebelumnya). Itu cukup untuk menjawab
     * "notifikasi ini tentang siswa siapa", tapi TIDAK menjawab "notifikasi
     * ini dikirim untuk kejadian presensi yang mana" - padahal alur di
     * PresensiController@tapRFIDWeb membuat baris Presensi lalu langsung
     * men-dispatch SendWhatsAppNotification untuk baris itu juga. Datanya
     * sudah ada di memori saat itu juga, cuma belum diteruskan ke log.
     *
     * Migrasi ini menambahkan `presensi_id` (FK ke presensis.id, nullable,
     * nullOnDelete - riwayat notifikasi tetap tersimpan meski baris presensi
     * sumbernya kelak dihapus/direkap ulang).
     *
     * CATATAN BACKFILL: Untuk data lama, tidak ada penanda eksplisit presensi
     * mana yang memicu log mana. Backfill di sini melakukan pencocokan
     * "best effort": hanya mengisi presensi_id jika pada tanggal yang sama
     * siswa tersebut PERSIS memiliki satu baris presensi (tidak ambigu).
     * Kalau ada lebih dari satu presensi di hari yang sama untuk siswa itu,
     * presensi_id sengaja dibiarkan NULL agar tidak salah tautkan.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('log_notifikasis', 'presensi_id')) {
            Schema::table('log_notifikasis', function (Blueprint $table) {
                $table->foreignId('presensi_id')
                    ->nullable()
                    ->after('siswa_id')
                    ->constrained('presensis')
                    ->nullOnDelete();
            });
        }

        DB::table('log_notifikasis')
            ->whereNotNull('siswa_id')
            ->whereNull('presensi_id')
            ->orderBy('id')
            ->chunkById(200, function ($logs) {
                foreach ($logs as $log) {
                    $tanggal = substr($log->created_at, 0, 10);

                    $kandidat = DB::table('presensis')
                        ->where('siswa_id', $log->siswa_id)
                        ->whereDate('created_at', $tanggal)
                        ->pluck('id');

                    // Hanya isi jika cocok tunggal (tidak ambigu)
                    if ($kandidat->count() === 1) {
                        DB::table('log_notifikasis')
                            ->where('id', $log->id)
                            ->update(['presensi_id' => $kandidat->first()]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('log_notifikasis', function (Blueprint $table) {
            $table->dropForeign(['presensi_id']);
            $table->dropColumn('presensi_id');
        });
    }
};
