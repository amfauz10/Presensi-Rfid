<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapBulananExport implements FromView
{
    protected $data_siswa;
    protected $presensi_matriks;
    protected $kelas_terpilih;
    protected $bulan;
    protected $tahun;
    protected $tahunAktif;

    public function __construct($data_siswa, $presensi_matriks, $kelas_terpilih, $bulan, $tahun, $tahunAktif)
    {
        $this->data_siswa = $data_siswa;
        $this->presensi_matriks = $presensi_matriks;
        $this->kelas_terpilih = $kelas_terpilih;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->tahunAktif = $tahunAktif;
    }

    public function view(): View
    {
        return view('laporan.export', [
            'data_siswa' => $this->data_siswa,
            'presensi_matriks' => $this->presensi_matriks,
            'kelas_terpilih' => $this->kelas_terpilih,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'tahunAktif' => $this->tahunAktif,
        ]);
    }
}
