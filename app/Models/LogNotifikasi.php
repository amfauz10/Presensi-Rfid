<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogNotifikasi extends Model
{
    protected $table = 'log_notifikasis';

    protected $fillable = [
        'rfid_code',
        'siswa_id',
        'presensi_id',
        'nama_siswa',
        'no_hp_orang_tua',
        'status_presensi',
        'status_notifikasi',
        'keterangan',
    ];

    /**
     * Relasi Banyak-ke-Satu ke Siswa lewat foreign key 'siswa_id'.
     *
     * CATATAN PERBAIKAN: Sebelumnya tabel ini menyimpan salinan penuh data
     * siswa (rfid_code, nama_siswa, no_hp_orang_tua) tanpa foreign key apa pun
     * -> redundansi data & tidak bisa di-join ke tabel induk. Kolom snapshot
     * tetap dipertahankan (tabel ini adalah log/audit trail pengiriman WA,
     * jadi nilai historis harus tetap terekam apa adanya), namun sekarang
     * juga tersedia 'siswa_id' agar log bisa ditelusuri relasional ke siswa
     * yang bersangkutan selama datanya masih ada.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    /**
     * Relasi Banyak-ke-Satu ke Presensi lewat foreign key 'presensi_id'.
     * Menunjukkan tepatnya baris presensi mana yang memicu notifikasi ini,
     * bukan cuma "siswa siapa" yang didapat lewat relasi siswa() di atas.
     */
    public function presensi(): BelongsTo
    {
        return $this->belongsTo(Presensi::class, 'presensi_id', 'id');
    }
}