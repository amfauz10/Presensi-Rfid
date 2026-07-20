<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajarans';

    protected $fillable = [
        'nama',
        'tanggal_mulai',
        'tanggal_selesai',
        'status'
    ];

    /**
     * Mengambil Tahun Ajaran yang sedang aktif
     */
    public static function aktif()
    {
        return self::where('status', 1)->first();
    }

    /**---------------------------------------- */
        public function presensi()
    {
        return $this->hasMany(Presensi::class);
    }

    /**
     * Daftar siswa yang dinyatakan lulus/Alumni pada tahun ajaran ini.
     */
    public function siswaLulus()
    {
        return $this->hasMany(Siswa::class, 'tahun_ajaran_lulus_id');
    }
}