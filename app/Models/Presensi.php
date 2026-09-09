<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presensi extends Model
{
    // Memastikan Laravel membaca nama tabel yang benar di database
    protected $table = 'presensis';

    // Kolom yang diizinkan untuk pengisian massal (Mass Assignment)
    protected $fillable = [
        'rfid_code', 
        'siswa_id',
        'status', 
        'sumber',
        'kelas_id', 
        'keterangan',
        'dokumen',
        'tahun_ajaran_id'
    ];

    /**
     * Relasi Banyak-ke-Satu (Inverse Relationship)
     * Menghubungkan data presensi kembali ke identitas Siswa pemilik kartu,
     * lewat foreign key asli 'siswa_id' -> 'siswas.id'.
     *
     * CATATAN PERBAIKAN: Sebelumnya relasi ini mengikat kolom 'rfid_code' di
     * presensis ke 'rfid_code' di siswas (bukan foreign key sungguhan, hanya
     * pencocokan string, tanpa constraint integritas referensial di database).
     * Kolom 'rfid_code' tetap disimpan sebagai jejak mentah kartu yang discan,
     * namun kunci relasi resmi sekarang adalah 'siswa_id'.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id');
    }

    /**
     * Riwayat notifikasi WhatsApp yang dikirim untuk baris presensi ini.
     */
    public function logNotifikasi()
    {
        return $this->hasMany(LogNotifikasi::class, 'presensi_id', 'id');
    }

    /**
     * Relasi Banyak-ke-Satu (Inverse Relationship)
     * Menghubungkan rekaman presensi ke Kelas siswa saat absen itu terjadi.
     * Mengikat kolom 'kelas_id' di tabel presensis ke kolom 'id' di tabel kelas.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    /** */
        public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}