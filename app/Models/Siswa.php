<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    // Memastikan Laravel membaca nama tabel yang benar (Sesuai skema migrasi Anda)
    protected $table = 'siswas';

    // Mendaftarkan kolom yang boleh diisi (DISINKRONKAN DENGAN SISWAIMPORT)
    protected $fillable = [
        'kelas_id',         // Foreign key pengikat ke tabel kelas
        'nisn',
        'nama_siswa', 
        'rfid_code', 
        'no_hp_orang_tua',  // Digunakan oleh FonnteService WhatsApp Gateway
        'foto',
        'status',
        'alasan_nonaktif',  // REVISI: alasan spesifik saat status = 'Tidak Aktif'
        'tahun_ajaran_lulus_id', // Mencatat tahun ajaran aktif saat siswa dinyatakan Alumni
    ];

    /**
     * Relasi Banyak-ke-Satu (Inverse)
     * Menegaskan bahwa banyak siswa bernaung di dalam satu Kelas.
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    /**
     * Relasi Satu-ke-Banyak
     * Menghubungkan identitas siswa ke rekap jejak presensi harian lewat
     * foreign key asli 'siswa_id' pada tabel presensis.
     *
     * CATATAN PERBAIKAN: Sebelumnya relasi ini mengikat 'rfid_code' <-> 'rfid_code',
     * sehingga siswa tanpa kartu RFID harus diberi kode semu 'MANUAL_<id>' agar
     * tetap bisa "match". Sekarang relasi memakai 'siswa_id' (integer, foreign key
     * beneran, terindeks) sehingga berlaku untuk semua siswa tanpa perlu kode semu.
     */
    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'siswa_id', 'id');
    }

    /**
     * Relasi ke Tahun Ajaran saat siswa ini dinyatakan lulus/Alumni.
     */
    public function tahunAjaranLulus(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_lulus_id', 'id');
    }
}