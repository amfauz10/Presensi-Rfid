<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogNotifikasi extends Model
{
    protected $table = 'log_notifikasis';

    protected $fillable = [
        'rfid_code',
        'nama_siswa',
        'no_hp_orang_tua',
        'status_presensi',
        'status_notifikasi',
        'keterangan',
    ];
}