<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromQuery, WithHeadings, WithMapping
{
    protected $kelas_id;

    // Menangkap parameter kelas_id dari Controller
    public function __construct($kelas_id = null)
    {
        $this->kelas_id = $kelas_id;
    }

    // Query data siswa berdasarkan filter kelas
    public function query()
    {
        $query = Siswa::with('kelas');

        if ($this->kelas_id) {
            $query->where('kelas_id', $this->kelas_id);
        }

        return $query;
    }

    // Menentukan Judul Kolom paling atas di Excel
    public function headings(): array
    {
        return [
            'No RFID',
            'NISN / NIS',
            'Nama Siswa',
            'Kelas',
            'Jenis Kelamin'
        ];
    }

    // Memetakan data dari database ke kolom Excel yang pas
    public function map($siswa): array
    {
        return [
            $siswa->rfid_code,
            $siswa->nisn ?? '-', 
            $siswa->nama_siswa,
            $siswa->kelas->nama_kelas ?? '-',
            $siswa->jenis_kelamin ?? '-'
        ];
    }
}