<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Kelas; // <-- Tambahan: Import model Kelas

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kelas_id', // <-- Catatan Penting: Pastikan foreign key ini ada agar relasi bekerja
        'nip', // <-- NIP guru/wali kelas, ditampilkan di blok tanda tangan Rekap Laporan
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Hubungan relasi antara User dan Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}