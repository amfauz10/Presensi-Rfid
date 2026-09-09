@extends('layouts.app')

@section('title', 'Rekap Laporan Presensi')

@section('content')

@php
    $jenis = request('jenis', $jenis ?? 'bulanan');
@endphp

<style>
    .table-sekolah {
        font-family: 'Arial', sans-serif !important;
        width: 100% !important;
        min-width: 900px;
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

        /*
         * Teknik isolasi print: gunakan visibility:hidden (bukan display:none)
         * karena visibility bisa di-override oleh elemen anak,
         * sedangkan display:none tidak bisa.
         */
        body * {
            visibility: hidden !important;
        }

        /* Hanya .print-area dan semua isinya yang terlihat */
        .print-area,
        .print-area * {
            visibility: visible !important;
        }

        /* Posisikan .print-area mulai dari pojok kiri-atas tanpa space */
        .print-area {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        html, body {
            background-color: #ffffff !important;
            font-family: 'Arial', sans-serif !important;
            margin: 0 !important;
            padding: 0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
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
            margin-top: 5px !important;
            font-size: 8.5px !important;
        }
    }

    /* CSS Minimalis untuk Tree View */
    .rekap-tree summary::-webkit-details-marker {
        display: none;
    }
    .rekap-tree .icon-chevron {
        transition: transform 0.2s ease-in-out;
        color: #495057;
    }
    .rekap-tree[open] .icon-chevron {
        transform: rotate(90deg);
        color: #000000;
    }
    .rekap-tree summary {
        outline: none;
    }
    .list-item-hover {
        transition: color 0.15s ease;
    }
    .list-item-hover:hover {
        color: #0d6efd !important;
    }

    /* SEARCH BAR STYLE (disamakan dengan halaman Data Siswa) */
    .input-group-pill {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background-color: #ffffff;
        transition: all 0.15s ease-in-out;
    }
    .input-group-pill:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }
    .input-group-pill .input-group-text {
        background: transparent;
        border: none;
        color: #94a3b8;
    }
    .input-group-pill .form-control {
        border: none;
        box-shadow: none !important;
        padding-left: 0;
    }
</style>

{{-- PANEL ATAS WEB (NO-PRINT) --}}
<div class="d-flex justify-content-between align-items-start mb-4 no-print text-start">
    <div>
        {{-- Menggunakan Details & Summary ala Tree Folder yang lebih Clean --}}
        <details class="rekap-tree">
            <summary class="fw-bold m-0 d-inline-flex align-items-center" style="font-size: 1.6rem; cursor: pointer; list-style: none; user-select: none;">
                <i class="bi bi-chevron-right me-2 icon-chevron" style="font-size: 1.2rem;"></i>
                Rekap Laporan Presensi
            </summary>
            
            <div class="d-flex flex-column ms-4 mt-2 ps-1 gap-2" style="font-size: 0.95rem;">
                <a href="{{ request()->fullUrlWithQuery(['jenis' => 'bulanan']) }}"
                   class="text-decoration-none list-item-hover {{ $jenis !== 'ketidakhadiran' ? 'text-primary fw-bold' : 'text-secondary' }}">
                    <i class="bi {{ $jenis !== 'ketidakhadiran' ? 'bi-record-circle-fill' : 'bi-circle' }} me-2" style="font-size: 0.7rem;"></i> 
                    Rekap Bulanan
                </a>
                
                <a href="{{ request()->fullUrlWithQuery(['jenis' => 'ketidakhadiran']) }}"
                   class="text-decoration-none list-item-hover {{ $jenis === 'ketidakhadiran' ? 'text-primary fw-bold' : 'text-secondary' }}">
                    <i class="bi {{ $jenis === 'ketidakhadiran' ? 'bi-record-circle-fill' : 'bi-circle' }} me-2" style="font-size: 0.7rem;"></i> 
                    Rekap Ketidakhadiran
                </a>
            </div>
        </details>
    </div>
    
    <div class="d-flex gap-2 mt-1">
        @if(request()->has('kelas_id') || auth()->user()->role == 'guru')
            <a href="{{ route('laporan.export', array_merge(request()->query(), ['jenis' => $jenis])) }}" class="btn btn-success rounded-3 px-3 fw-semibold shadow-none">
                <i class="bi bi-file-earmark-excel me-2"></i> Export Excel
            </a>
        @endif
        <button onclick="window.print()" class="btn btn-primary rounded-3 px-3 fw-semibold shadow-none" style="background-color: #6366f1; border:none;">
            <i class="bi bi-printer me-2"></i> Print PDF
        </button>
    </div>
</div>

{{-- FILTER BAR (NO-PRINT) --}}
<div class="card p-4 mb-4 text-start rounded-4 border-0 shadow-sm no-print bg-white">
    <form action="/laporan" method="GET">
        @php
            $jenis = $jenis ?? 'bulanan';
        @endphp
        <input type="hidden" name="jenis" value="{{ $jenis }}">
        <div class="row align-items-end g-3">
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Pilih Bulan</label>
                <select name="bulan" class="form-select rounded-3 p-2 shadow-none">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ (int)$bulan === $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted">Pilih Tahun</label>
                <select name="tahun" class="form-select rounded-3 p-2 shadow-none">
                    @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}" {{ (int)$tahun === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            {{-- ARSIP PRESENSI: pilih Tahun Ajaran lama untuk melihat rekap siswa
                 yang sudah naik kelas/lulus. Default kosong = tahun ajaran aktif. --}}
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Tahun Ajaran</label>
                <select name="tahun_ajaran_id" class="form-select rounded-3 p-2 shadow-none">
                    <option value="">-- Tahun Ajaran Aktif --</option>
                    @foreach($daftar_tahun_ajaran as $ta)
                        <option value="{{ $ta->id }}" {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>
                            {{ $ta->nama }} {{ $ta->status ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            {{-- BLOK FILTER KELAS DISESUAIKAN BERDASARKAN ROLE --}}
            @if(auth()->user()->role == 'admin')
                <div class="col-md-3">
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
                <div class="col-md-5">
                    <button type="submit" class="btn btn-dark w-100 p-2 rounded-3 fw-semibold" style="height: 41px;">
                        <i class="bi bi-arrow-clockwise me-1"></i> Perbarui Laporan Bulan
                    </button>
                </div>
            @endif
        </div>
    </form>
</div>

{{-- SEARCH SISWA (NO-PRINT, client-side, KOTAK dari 4 langkah KOTAK-DENGAR-AMBIL-SARING) --}}
@if(request()->has('kelas_id') || auth()->user()->role == 'guru')
<div class="card p-3 mb-3 rounded-4 border-0 shadow-sm no-print bg-white">
    <div class="input-group-pill d-flex align-items-center px-2">
        <span class="input-group-text p-0 me-2"><i class="bi bi-search small"></i></span>
        <input type="text" id="cariSiswa" class="form-control border-0 ps-0 shadow-none"
               placeholder="Cari nama siswa atau nomor induk NIS/NISN...">
    </div>
</div>
@endif

@if(request()->has('kelas_id') || auth()->user()->role == 'guru')

    @if($jenis === 'ketidakhadiran')

        <div class="card p-4 rounded-4 border-0 shadow-sm bg-white text-start">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%; text-align: center;">No</th>
                        <th>Nama Siswa</th>
                        <th style="width: 15%; text-align: center;">Sakit</th>
                        <th style="width: 15%; text-align: center;">Izin</th>
                        <th style="width: 15%; text-align: center;">Alpa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data_siswa ?? [] as $i => $siswa)
                        @php
                            $countS = 0; $countI = 0; $countA = 0;
                            
                            // Hitung akumulasi dari matriks presensi yang sudah dikirim controller
                            for($d = 1; $d <= 31; $d++) {
                                if(checkdate((int)$bulan, $d, (int)$tahun)) {
                                    $tglKey = sprintf('%02d', $d);
                                    $status = $presensi_matriks[$siswa->id][$tglKey] ?? '-';
                                    
                                    if($status == 'Sakit') $countS++;
                                    elseif($status == 'Izin') $countI++;
                                    elseif($status == 'Alpa') $countA++;
                                }
                            }
                        @endphp
                        <tr class="rekap-row">
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ ucwords(strtolower($siswa->nama_siswa)) }}</td>
                            <td class="text-center fw-bold">{{ $countS > 0 ? $countS : '-' }}</td>
                            <td class="text-center fw-bold">{{ $countI > 0 ? $countI : '-' }}</td>
                            <td class="text-center fw-bold">{{ $countA > 0 ? $countA : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data siswa aktif di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    @else
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

        <div class="card p-0 border-0 bg-white print-area">
            
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
                    @if($isArsip ?? false)
                        <span class="badge bg-secondary no-print" style="font-size:10px; vertical-align: middle;">ARSIP</span>
                    @endif
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
            <div style="overflow-x:auto;">
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
                        <tr class="rekap-row">
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
                                        {{-- Hari tidak valid / akhir bulan kosong --}}
                                    @elseif($statusHariIni == 'Sakit')
                                        <span style="font-size:8px;font-weight:bold;">S</span>
                                    @elseif($statusHariIni == 'Izin')
                                        <span style="font-size:8px;font-weight:bold;">I</span>
                                    @elseif($statusHariIni == 'Alpa')
                                        <span style="font-size:8px;font-weight:bold;">A</span>
                                    @elseif(in_array($statusHariIni, ['Hadir', 'Terlambat']))
                                        <span style="font-size:8px;font-weight:bold;">H</span>
                                    @else
                                        -
                                    @endif
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
            </div>

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
                        <div class="d-flex justify-content-between text-center sk-ttd-wrapper px-3">
                            <div style="width: 200px; height: 90px;" class="d-flex flex-column justify-content-between">
                                <div>
                                    <p class="m-0">Mengetahui,</p>
                                    <p class="m-0 fw-bold" style="font-size: 8.5px; line-height: 1.2;">
                                        {{ \App\Models\Setting::getValue('nama_sekolah') ?? 'Kepala SD Negeri Tengah 03 Jakarta Timur' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="m-0 fw-bold text-decoration-underline" style="letter-spacing:0.3px; font-size: 8.5px;">
                                        {{ \App\Models\Setting::getValue('kepala_sekolah') ?? 'SUGESTI, S.Pd' }}
                                    </p>
                                    <p class="m-0 text-muted" style="font-size:7px;">
                                        NIP. {{ \App\Models\Setting::getValue('nip_kepala') ?? '196607081998032003' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div style="width: 200px; height: 90px;" class="d-flex flex-column justify-content-between">
                                <div>
                                    <p class="m-0">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                    <p class="m-0 fw-bold" style="font-size: 8.5px; line-height: 1.2;">Wali Kelas</p>
                                </div>
                                <div>
                                    @if(auth()->user()->role == 'guru')
                                        <p class="m-0 fw-bold text-decoration-underline" style="letter-spacing: 0.3px; font-size: 8.5px;">{{ strtoupper(auth()->user()->name ?? auth()->user()->nama_guru) }}</p>
                                        <p class="m-0 text-muted" style="font-size: 7px;">NIP. {{ auth()->user()->nip ?? '-' }}</p>
                                    @else
                                        <p class="m-0 fw-bold text-decoration-underline" style="letter-spacing: 0.3px; font-size: 8.5px;">{{ strtoupper($kelas_terpilih->guru->name ?? $kelas_terpilih->nama_guru ?? '-') }}</p>
                                        <p class="m-0 text-muted" style="font-size: 7px;">NIP. {{ $kelas_terpilih->guru->nip ?? '-' }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif

@endif

{{-- FILTER PENCARIAN SISWA: DENGAR -> AMBIL -> SARING --}}
<script>
document.getElementById('cariSiswa')?.addEventListener('keyup', function () {
    const kata = this.value.toLowerCase();
    document.querySelectorAll('.rekap-row').forEach(function (baris) {
        baris.style.display = baris.innerText.toLowerCase().includes(kata) ? '' : 'none';
    });
});
</script>

@endsection