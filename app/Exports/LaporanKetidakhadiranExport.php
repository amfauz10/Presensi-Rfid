<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanKetidakhadiranExport implements FromView
{
    protected $rekap;
    protected $kelas_terpilih;
    protected $bulan;
    protected $tahun;

    public function __construct($rekap, $kelas_terpilih, $bulan, $tahun)
    {
        $this->rekap = $rekap;
        $this->kelas_terpilih = $kelas_terpilih;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        return view('laporan.ketidakhadiran-export', [
            'rekap' => $this->rekap,
            'kelas_terpilih' => $this->kelas_terpilih,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }
}
