<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class RekapBulananExport implements FromView, ShouldAutoSize, WithEvents
{
    protected $data_siswa;
    protected $presensi_matriks;
    protected $kelas_terpilih;
    protected $bulan;
    protected $tahun;
    protected $tahunAktif;

    public function __construct($data_siswa, $presensi_matriks, $kelas_terpilih, $bulan, $tahun, $tahunAktif)
    {
        $this->data_siswa       = $data_siswa;
        $this->presensi_matriks = $presensi_matriks;
        $this->kelas_terpilih   = $kelas_terpilih;
        $this->bulan            = $bulan;
        $this->tahun            = $tahun;
        $this->tahunAktif       = $tahunAktif;
    }

    public function view(): View
    {
        return view('laporan.export', [
            'data_siswa'       => $this->data_siswa,
            'presensi_matriks' => $this->presensi_matriks,
            'kelas_terpilih'   => $this->kelas_terpilih,
            'bulan'            => $this->bulan,
            'tahun'            => $this->tahun,
            'tahunAktif'       => $this->tahunAktif,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $pageSetup = $sheet->getPageSetup();

                // Orientasi landscape agar 38 kolom muat dalam 1 halaman
                $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
                $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A4);

                // Fit to 1 halaman lebar, tinggi bebas (auto)
                $pageSetup->setFitToPage(true);
                $pageSetup->setFitToWidth(1);
                $pageSetup->setFitToHeight(0);

                // Margin kecil (satuan inci) agar tidak ada space kosong
                $margins = $sheet->getPageMargins();
                $margins->setTop(0.5);
                $margins->setRight(0.2);
                $margins->setBottom(0.5);
                $margins->setLeft(0.2);
                $margins->setHeader(0);
                $margins->setFooter(0);
            },
        ];
    }
}
