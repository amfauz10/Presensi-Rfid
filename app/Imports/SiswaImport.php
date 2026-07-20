<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;

class SiswaImport implements ToModel, WithUpserts
{
    protected $kelas_id;

    public function __construct($kelas_id = null)
    {
        $this->kelas_id = $kelas_id;
    }

    /**
     * Menentukan kolom patokan kunci unik untuk mencegah duplikasi data ganda
     */
    public function uniqueBy()
    {
        return 'nisn';
    }

    /**
     * Memproses baris data Excel instansi ke rancangan database
     */
    public function model(array $row)
    {
        // PENGAMAN 1: SINKRONISASI CSV - Gunakan str_getcsv agar tanda kutip pembungkus otomatis hilang bersih
        if (count($row) === 1 && isset($row[0])) {
            $delimiter = (strpos($row[0], ';') !== false) ? ';' : ',';
            $row = str_getcsv($row[0], $delimiter);
        }

        // PENGAMAN 2: Pastikan kolom indeks 1 (NISN) dan 2 (Nama) tersedia setelah di-parse
        if (!isset($row[1]) || !isset($row[2])) {
            return null;
        }

        // PEMBERSIH UTAMA: Kikis semua karakter gaib, spasi, atau simbol selain angka pada kolom NISN
        $nisnClean = preg_replace('/[^0-9]/', '', $row[1]);
        $namaClean = trim($row[2]);

        // PENGAMAN 3: DETEKSI BARIS KOSONG & HEADER
        // Jika setelah dikikis kolom NISN kosong, atau nama kosong, atau baris tersebut merupakan teks header, lewati.
        if ($nisnClean === '' || $namaClean === '' || strtolower(trim($row[1])) === 'nisn') {
            return null;
        }

        // PENANGANAN ANGKA NOL (0) DI DEPAN NISN
        // Menjaga agar NISN yang diawali angka 0 (seperti milik Rizky Alfiansyah) tetap utuh 10 digit
        if (strlen($nisnClean) < 10) {
            $nisnClean = str_pad($nisnClean, 10, '0', STR_PAD_LEFT);
        }

        // 3. PROSES MEMASUKKAN DATA KE RANCANGAN SISTEM ANDA
        return new Siswa([
        'kelas_id'        => $this->kelas_id,
        'nisn'            => $nisnClean,
        'nama_siswa'      => $namaClean,
        'rfid_code'       => null,
        'nama_orang_tua'  => '-',
        'no_hp_orang_tua' => '-',
        'status'          => 'Aktif',
        ]);
    }
}