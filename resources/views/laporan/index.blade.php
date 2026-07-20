@extends('layouts.app')

@section('title', 'Rekap Laporan Presensi')

@section('content')
<style>
    .table-sekolah {
        font-family: 'Arial', sans-serif !important;
        width: 100% !important;
        table-layout: fixed;
        border-collapse: collapse !important;
        background-color: #ffffff;
    }
    .table-sekolah th, .table-sekolah td {
        border: 1px solid #000000 !important;
        text-align: center;
        vertical-align: middle;
        color: #000000 !important;
        padding: 2px 1px !important;
    }
    .table-sekolah thead th {
        background-color: #ffe600 !important;
        font-weight: bold !important;
        font-size: 8.5px !important;
    }
    .table-sekolah .align-left {
        text-align: left !important;
        padding-left: 6px !important;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bg-libur {
        background-color: #ff0000 !important;
        color: #ffffff !important;
    }
    .bg-sabtu {
        background-color: #ffc107 !important;
        color: #000000 !important;
    }
    .bg-disabled {
        background-color: #d1d5db !important; 
        color: #d1d5db !important;
    }
    .line-bawah {
        display: inline-block;
        border-bottom: 1px solid #000000;
        text-align: center;
        min-width: 35px;
        font-weight: bold;
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 4mm 5mm 3mm 5mm !important;
        }
        html, body {
            width: 297mm;
            height: 210mm;
            background-color: #ffffff !important;
            font-family: 'Arial', sans-serif !important;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .no-print, .sidebar, header, nav, .btn, form {
            display: none !important;
        }
        .main-content {
            margin-left: 0 !important;
            margin-top: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            background: transparent !important;
        }
        .table-sekolah {
            font-size: 7.3px !important;
            line-height: 1.0 !important;
            margin-top: 1px !important;
        }
        .table-sekolah thead th {
            background-color: #ffe600 !important;
            font-size: 7.5px !important;
            padding: 1.5px 0px !important;
        }
        .table-sekolah td {
            padding: 0.5px 0px !important;
        }
        .table-sekolah .align-left {
            padding-left: 4px !important;
            font-size: 7.3px !important;
        }
        .sk-rumus-wrapper table {
            font-size: 10px !important;
        }
        .sk-ttd-container {
            margin-top: 15px !important;
            font-size: 8.5px !important;
        }
    }
</style>

{{-- PANEL ATAS WEB (NO-PRINT) --}}
<div class="d-flex justify-content-between align-items-center mb-4 no-print text-start">
    <div>
        <h3 class="fw-bold m-0">Rekap Laporan Presensi</h3>
        <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Kelola berkas kehadiran siswa format matriks bulanan.</p>
    </div>
    <div class="d-flex gap-2">
        @if(request()->has('kelas_id') || auth()->user()->role == 'guru')
            <a href="{{ route('laporan.export', request()->query()) }}" class="btn btn-success rounded-3 px-3 fw-semibold shadow-none">
                <i class="bi bi-file-earmark-excel me-2"></i> Export ke Excel
            </a>
        @endif
        <button onclick="window.print()" class="btn btn-primary rounded-3 px-3 fw-semibold shadow-none" style="background-color: #6366f1; border:none;">
            <i class="bi bi-printer me-2"></i> Cetak ke PDF / Print
        </button>
    </div>
</div>

{{-- FILTER BAR (NO-PRINT) --}}
<div class="card p-4 mb-4 text-start rounded-4 border-0 shadow-sm no-print bg-white">
    <form action="/laporan" method="GET">
        <div class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Pilih Bulan</label>
                <select name="bulan" class="form-select rounded-3 p-2 shadow-none">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ (int)$bulan === $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Pilih Tahun</label>
                <select name="tahun" class="form-select rounded-3 p-2 shadow-none">
                    @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}" {{ (int)$tahun === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            
            {{-- BLOK FILTER KELAS DISESUAIKAN BERDASARKAN ROLE --}}
            @if(auth()->user()->role == 'admin')
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Filter Kelas</label>
                    <select name="kelas_id" class="form-select rounded-3 p-2 shadow-none" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($daftar_kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 p-2 rounded-3 fw-semibold" style="height: 41px;">
                        <i class="bi bi-search me-1"></i> Buka Rekap
                    </button>
                </div>
            @else
                {{-- Jika Guru, kelas_id disembunyikan/otomatis dari controller --}}
                <div class="col-md-6">
                    <button type="submit" class="btn btn-dark w-100 p-2 rounded-3 fw-semibold" style="height: 41px;">
                        <i class="bi bi-arrow-clockwise me-1"></i> Perbarui Laporan Bulan
                    </button>
                </div>
            @endif
        </div>
    </form>
</div>

@if(request()->has('kelas_id') || auth()->user()->role == 'guru')

@php
    $totalSakitKelas = 0; $totalIzinKelas = 0; $totalAlpaKelas = 0; $totalHadirKelas = 0;
    $jumlahSiswaSatuKelas = isset($data_siswa) ? count($data_siswa) : 0;
    
    $hariEfektifBulanIni = 0;
    for($d=1; $d<=31; $d++) {
        if(checkdate((int)$bulan, $d, (int)$tahun)) {
            $dayOfWeek = \Carbon\Carbon::create((int)$tahun, (int)$bulan, $d)->dayOfWeek;
            if($dayOfWeek != \Carbon\Carbon::SATURDAY && $dayOfWeek != \Carbon\Carbon::SUNDAY) {
                $hariEfektifBulanIni++;
            }
        }
    }
    $totalPembagiMatriks = $hariEfektifBulanIni * $jumlahSiswaSatuKelas;
@endphp

<div class="card p-0 border-0 bg-white">
    
    {{-- KOP KEPALA SURAT --}}
    <div class="text-center mb-2">
        <div style="font-size:18px;font-weight:bold;">
            DAFTAR HADIR SISWA
        </div>
        <div style="font-size:15px;font-weight:bold;">
            SDN TENGAH 03
        </div>
        <div style="font-size:13px;">
            Tahun Ajaran {{ $tahunAktif->nama ?? '-' }}
        </div>
    </div>

    <hr style="margin:6px 0;border:1px solid black;">

    {{-- DETAIL BULAN & KELAS FORMAT SEKOLAH --}}
    <table width="100%" style="margin-bottom:8px;font-size:11px;">
        <tr>
            <td>
                <b>Bulan</b> : {{ \Carbon\Carbon::create(null, (int)$bulan)->translatedFormat('F') }} {{ $tahun }}
            </td>
            <td align="right">
                <b>Kelas</b> : {{ $kelas_terpilih->nama_kelas ?? '' }}
            </td>
        </tr>
    </table>

    {{-- TABEL MATRIKS UTAMA --}}
    <table class="table table-sekolah m-0">
        <thead>
            <tr>
                <th rowspan="2" style="width: 3.0%;">N O</th>
                <th rowspan="2" style="width: 8.5%;">NISN</th>
                <th rowspan="2" style="width: 23.5%;">NAMA</th>
                <th colspan="31" style="background-color: #ffe600 !important;">BULAN : {{ strtoupper(\Carbon\Carbon::create(null, (int)$bulan)->translatedFormat('F')) }}</th>
                <th colspan="4" style="width: 6%;">JUMLAH</th>
            </tr>
            <tr>
                @for($d=1; $d<=31; $d++)
                    @php
                        $isValidDate = checkdate((int)$bulan, $d, (int)$tahun);
                        $isWeekend = false;
                        $isSaturday = false;
                        $isSunday = false;
                        if($isValidDate) {
                            $dayOfWeek = \Carbon\Carbon::create((int)$tahun, (int)$bulan, $d)->dayOfWeek;
                            $isWeekend = ($dayOfWeek == \Carbon\Carbon::SATURDAY || $dayOfWeek == \Carbon\Carbon::SUNDAY);
                            $isSaturday = ($dayOfWeek == \Carbon\Carbon::SATURDAY);
                            $isSunday = ($dayOfWeek == \Carbon\Carbon::SUNDAY);
                        }
                    @endphp
                    <th style="width: 1.8%; font-size: 7px;" class="{{ !$isValidDate ? 'bg-disabled' : ($isSunday ? 'bg-libur' : ($isSaturday ? 'bg-sabtu' : '')) }}">
                        {{ $isValidDate ? $d : '' }}
                    </th>
                @endfor
                <th style="width: 1.5%;">S</th>
                <th style="width: 1.5%;">I</th>
                <th style="width: 1.5%;">A</th>
                <th style="width: 1.5%;">H</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_siswa as $index => $siswa)
                @php $countS = 0; $countI = 0; $countA = 0; $countHadir = 0; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td style="font-size: 7px; font-family: monospace;">{{ $siswa->nisn ?? '-' }}</td>
                    <td class="align-left" style="font-size:7px;font-weight:600;">
                        {{ ucwords(strtolower($siswa->nama_siswa)) }}
                    </td>
                    
                    @for($d=1; $d<=31; $d++)
                        @php
                            $tglKey = sprintf('%02d', $d);
                            $statusHariIni = $presensi_matriks[$siswa->id][$tglKey] ?? '-';
                            
                            $isValidDate = checkdate((int)$bulan, $d, (int)$tahun);
                            $isWeekend = false;
                            $isSaturday = false;
                            $isSunday = false;
                            if($isValidDate) {
                                $dayOfWeek = \Carbon\Carbon::create((int)$tahun, (int)$bulan, $d)->dayOfWeek;
                                $isWeekend = ($dayOfWeek == \Carbon\Carbon::SATURDAY || $dayOfWeek == \Carbon\Carbon::SUNDAY);
                                $isSaturday = ($dayOfWeek == \Carbon\Carbon::SATURDAY);
                                $isSunday = ($dayOfWeek == \Carbon\Carbon::SUNDAY);
                            }

                            if($isValidDate) {
                                if($statusHariIni == 'Sakit') { $countS++; $totalSakitKelas++; }
                                elseif($statusHariIni == 'Izin') { $countI++; $totalIzinKelas++; }
                                elseif($statusHariIni == 'Alpa') { $countA++; $totalAlpaKelas++; }
                                elseif(in_array($statusHariIni, ['Hadir', 'Terlambat'])) { $countHadir++; $totalHadirKelas++; }
                            }
                        @endphp
                        <td class="{{ !$isValidDate ? 'bg-disabled' : ($isSunday ? 'bg-libur' : ($isSaturday ? 'bg-sabtu' : '')) }}">
                            @if(!$isValidDate)
                            @elseif($statusHariIni == 'Sakit') <span style="font-size:8px;font-weight:bold;">S</span>
                            @elseif($statusHariIni == 'Izin') <span style="font-size:8px;font-weight:bold;">I</span>
                            @elseif($statusHariIni == 'Alpa') <span style="font-size:8px;font-weight:bold;">A</span>
                            @elseif(in_array($statusHariIni, ['Hadir', 'Terlambat'])) <span style="font-size:8px;font-weight:bold;">H</span>
                            @else - @endif
                        </td>
                    @endfor

                    <td style="font-weight:bold;">{{ $countS > 0 ? $countS : '-' }}</td>
                    <td style="font-weight:bold;">{{ $countI > 0 ? $countI : '-' }}</td>
                    <td style="font-weight:bold;">{{ $countA > 0 ? $countA : '-' }}</td>
                    <td style="font-weight:bold;">{{ $countHadir > 0 ? $countHadir : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PROSES REAL-TIME HITUNG PERSENTASE --}}
    @php
        $pembagiAman = $jumlahSiswaSatuKelas > 0 ? $jumlahSiswaSatuKelas : 1;
        $persenSakitKelas = round(($totalSakitKelas / $pembagiAman) * 100, 1);
        $persenIzinKelas = round(($totalIzinKelas / $pembagiAman) * 100, 1);
        $persenAlpaKelas = round(($totalAlpaKelas / $pembagiAman) * 100, 1);
        $persenHadirKelas = round(100 - ($persenSakitKelas + $persenIzinKelas + $persenAlpaKelas), 1);
    @endphp

    {{-- BLOK BAWAH: RUMUS OTOMATIS & AREA TTD --}}
    <div class="mt-2 bg-white">
        <div class="row mx-0 g-0 align-items-start" style="font-size: 8px; font-family: Arial, sans-serif; color: #000;">
            
            {{-- TABEL RUMUS PERSENTASE KEHADIRAN --}}
            <div class="col-5 text-start ps-2 pt-1 sk-rumus-wrapper">
                <table style="font-size:10px;line-height:1.6;">
                    <tr>
                        <td width="20">S</td>
                        <td>=</td>
                        <td>{{ $totalSakitKelas }}</td>
                        <td>÷</td>
                        <td>{{ $jumlahSiswaSatuKelas }}</td>
                        <td>×100%</td>
                        <td>=</td>
                        <td><b>{{ number_format($persenSakitKelas, 2) }}%</b></td>
                    </tr>
                    <tr>
                        <td>I</td>
                        <td>=</td>
                        <td>{{ $totalIzinKelas }}</td>
                        <td>÷</td>
                        <td>{{ $jumlahSiswaSatuKelas }}</td>
                        <td>×100%</td>
                        <td>=</td>
                        <td><b>{{ number_format($persenIzinKelas, 2) }}%</b></td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>=</td>
                        <td>{{ $totalAlpaKelas }}</td>
                        <td>÷</td>
                        <td>{{ $jumlahSiswaSatuKelas }}</td>
                        <td>×100%</td>
                        <td>=</td>
                        <td><b>{{ number_format($persenAlpaKelas, 2) }}%</b></td>
                    </tr>
                    <tr>
                        <td>H</td>
                        <td>=</td>
                        <td colspan="4">100% − (S + I + A)</td>
                        <td>=</td>
                        <td><b>{{ number_format($persenHadirKelas, 2) }}%</b></td>
                    </tr>
                    <tr>
                        <td colspan="8" style="font-style:italic; padding-top:4px;">
                            Keterangan : S = Sakit &nbsp;&nbsp; I = Izin &nbsp;&nbsp; A = Alpa &nbsp;&nbsp; H = Hadir
                        </td>
                    </tr>
                </table>
            </div>

            {{-- BLOK TANDA TANGAN --}}
            <div class="col-7 pt-1 sk-ttd-container">
                <div class="d-flex justify-content-end text-center gap-5 sk-ttd-wrapper pe-4">
                    <div style="width: 170px;">
                        <p class="m-0">Mengetahui,</p>
                        <p class="m-0 fw-bold">
                            {{ \App\Models\Setting::getValue('nama_sekolah') ?? ' Kepala SD Negeri Tengah 03 Jakarta Timur' }}
                        </p>
                        <div style="height: 65px;"></div>
                        <p class="m-0 fw-bold text-decoration-underline" style="letter-spacing:0.3px;">
                            {{ \App\Models\Setting::getValue('kepala_sekolah') ?? 'SUGESTI, S.Pd' }}
                        </p>
                        <p class="m-0 text-muted" style="font-size:7px;">
                            NIP. {{ \App\Models\Setting::getValue('nip_kepala') ?? '196607081998032003' }}
                        </p>
                    </div>
                    
                    <div style="width: 170px;">
                        <p class="m-0">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p class="m-0 fw-bold">Wali Kelas</p>
                        <div style="height: 65px;"></div>
                        @if(auth()->user()->role == 'guru')
                            <p class="m-0 fw-bold text-decoration-underline" style="letter-spacing: 0.3px;">{{ strtoupper(auth()->user()->name ?? auth()->user()->nama_guru) }}</p>
                            <p class="m-0 text-muted" style="font-size: 7px;">NIP. {{ auth()->user()->nip ?? '-' }}</p>
                        @else
                            <p class="m-0 fw-bold text-decoration-underline" style="letter-spacing: 0.3px;">{{ strtoupper($kelas_terpilih->guru->name ?? $kelas_terpilih->nama_guru ?? '-') }}</p>
                            <p class="m-0 text-muted" style="font-size: 7px;">NIP. {{ $kelas_terpilih->guru->nip ?? '-' }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection