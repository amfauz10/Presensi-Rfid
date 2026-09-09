<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PERBAIKAN ATURAN ON DELETE PADA FOREIGN KEY YANG MELIBATKAN TABEL `kelas`.
     *
     * 1. siswas.kelas_id (sebelumnya onDelete('cascade')):
     *    Artinya jika satu baris `kelas` terhapus, SELURUH data siswa di kelas
     *    tersebut ikut terhapus otomatis oleh database. Aplikasi ini tidak
     *    memiliki fitur hapus kelas sama sekali (tidak ada KelasController atau
     *    route hapus kelas), sehingga aturan cascade ini adalah risiko senyap:
     *    kalau suatu saat fitur hapus kelas ditambahkan, atau baris kelas
     *    terhapus manual lewat query, seluruh siswa (dan lewat FK di bawah,
     *    seluruh riwayat presensinya) akan hilang tanpa peringatan.
     *    Diubah menjadi restrictOnDelete(): kelas yang masih memiliki siswa
     *    tidak bisa dihapus sebelum siswanya dipindahkan/dihapus terlebih dahulu.
     *
     * 2. presensis.kelas_id (sebelumnya onDelete('cascade')):
     *    kelas_id di sini berfungsi sebagai snapshot riwayat ("siswa ini absen
     *    saat berada di kelas X"), bukan relasi struktural yang wajib selalu
     *    valid. Riwayat presensi adalah data historis/laporan yang seharusnya
     *    tidak boleh ikut terhapus hanya karena data kelas berubah/dihapus.
     *    Diubah menjadi nullOnDelete() (kolom ini memang sudah nullable) agar
     *    riwayat kehadiran tetap tersimpan untuk keperluan rekap & laporan.
     */
    public function up(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('siswas', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas')->restrictOnDelete();
        });

        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('presensis', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('siswas', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('siswas', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
        });

        Schema::table('presensis', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
        });
        Schema::table('presensis', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('id')->on('kelas')->onDelete('cascade');
        });
    }
};
