<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value'
    ];

    /**
     * Mengambil nilai setting berdasarkan key
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    /**
     * Menyimpan atau memperbarui setting
     */
    public static function setValue($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Format bawaan pesan notifikasi WhatsApp.
     *
     * Disimpan di satu tempat saja supaya halaman Pengaturan dan
     * PresensiController selalu memakai teks yang sama persis.
     */
    public static function defaultTemplatePesan()
    {
        return "📢 INFORMASI PRESENSI SISWA\n\n"
             . "Yth. Bapak/Ibu Orang Tua/Wali,\n\n"
             . "Ananda : {nama_siswa}\n"
             . "Kelas : {kelas}\n"
             . "Tanggal : {tanggal}\n"
             . "Jam Masuk : {jam} WIB\n"
             . "Status : {status}\n\n"
             . "Terima kasih.\n"
             . "{nama_sekolah}";
    }

    /**
     * Template pesan yang sedang aktif (kustom admin, atau bawaan bila kosong).
     */
    public static function templatePesanAktif()
    {
        $template = self::getValue('pesan_template');

        return trim($template ?? '') !== ''
            ? $template
            : self::defaultTemplatePesan();
    }

    /**
     * Daftar variabel yang dikenali sistem beserta labelnya.
     * Dipakai untuk tombol sisip variabel dan pratinjau di halaman Pengaturan.
     */
    public static function variabelPesan()
    {
        return [
            '{nama_siswa}'   => 'Nama Siswa',
            '{kelas}'        => 'Kelas',
            '{tanggal}'      => 'Tanggal',
            '{jam}'          => 'Jam',
            '{status}'       => 'Status',
            '{nama_sekolah}' => 'Nama Sekolah',
        ];
    }
}