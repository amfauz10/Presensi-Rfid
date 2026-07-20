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
        'waktu_masuk', 
        'status', 
        'kelas_id', 
        'keterangan',
        'tahun_ajaran_id'
    ];

    /**
     * Relasi Banyak-ke-Satu (Inverse Relationship)
     * Menghubungkan data presensi kembali ke identitas Siswa pemilik kartu.
     * Mengikat kolom 'rfid_code' di tabel presensis ke kolom 'rfid_code' di tabel siswas.
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'rfid_code', 'rfid_code');
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