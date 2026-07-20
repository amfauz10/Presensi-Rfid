<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User; // <-- Tambahan: Import model User

class Kelas extends Model
{
    // Menegaskan nama tabel di database
    protected $table = 'kelas';

    // Atribut yang diizinkan untuk mass-assignment
    protected $fillable = ['nama_kelas'];

    /**
     * Catatan Keamanan Tugas Akhir: 
     * Jika tabel 'kelas' di database Anda tidak memiliki kolom 'created_at' and 'updated_at',
     * silakan aktifkan (hilangkan tanda komentar) baris di bawah ini agar tidak error:
     */
    // public $timestamps = false;

    /**
     * Relasi One-to-Many (Satu Kelas mempunyai banyak Siswa)
     * Digunakan untuk mengelompokkan daftar siswa berdasarkan ruang kelasnya.
     */
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'id');
    }

    /**
     * Relasi One-to-Many (Satu Kelas mempunyai banyak data Presensi)
     * Menghubungkan langsung ke model Presensi via foreign key 'kelas_id'
     * Sangat berguna untuk optimasi query hitung (count) kehadiran per kelas di halaman dashboard.
     */
    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'kelas_id', 'id');
    }

    /**
     * Relasi One-to-Many (Satu Kelas mempunyai banyak User/Pengguna)
     * Tambahan Tahap 3
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Wali kelas (guru) yang mengampu kelas ini.
     * Dipakai untuk menampilkan nama & NIP wali kelas di rekap laporan (cetak & export Excel).
     */
    public function guru()
    {
        return $this->hasOne(User::class)->where('role', 'guru');
    }
}