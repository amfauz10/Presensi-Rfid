<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan Migration
     */
    public function up(): void
    {
        Schema::create('log_notifikasis', function (Blueprint $table) {
            $table->id();

            // RFID siswa yang melakukan presensi
            $table->string('rfid_code');

            // Nama siswa
            $table->string('nama_siswa');

            // Nomor WhatsApp orang tua
            $table->string('no_hp_orang_tua');

            // Status presensi siswa
            $table->enum('status_presensi', [
                'Hadir',
                'Terlambat',
                'Sakit',
                'Izin',
                'Alpa'
            ]);

            // Status pengiriman WhatsApp
            $table->enum('status_notifikasi', [
                'Berhasil',
                'Gagal'
            ]);

            // Respon dari Fonnte / pesan error
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Rollback Migration
     */
    public function down(): void
    {
        Schema::dropIfExists('log_notifikasis');
    }
};