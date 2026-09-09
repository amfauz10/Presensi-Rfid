@extends('layouts.app')

@section('title', 'Kelola Presensi Siswa (Manual) - SDN Tengah 03')

@section('content')

<style>
    /* Base SaaS Utility & Variables */
    :root {
        --saas-bg: #f8fafc;
        --saas-card-bg: #ffffff;
        --saas-border: #e2e8f0;
        --saas-text-main: #0f172a;
        --saas-text-muted: #64748b;
    }

    body {
        background-color: var(--saas-bg);
        color: var(--saas-text-main);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .container-fluid {
        padding: 24px;
    }

    /* Modern Card SaaS Style */
    .saas-card {
        background: var(--saas-card-bg);
        border: 1px solid var(--saas-border);
        border-radius: 12px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .saas-card:hover {
        border-color: #cbd5e1;
    }

    /* Stat Grid & Cards */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 0.85rem;
    }
    @media (max-width: 1200px) {
        .stat-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 768px) {
        .stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0.5rem; }
    }

    .stat-card {
        background: #ffffff;
        border: 1px solid var(--saas-border);
        border-radius: 12px;
        padding: 1rem;
        position: relative;
        overflow: hidden;
        transition: border-color 0.2s ease;
    }

    .stat-card:hover {
        border-color: #cbd5e1;
    }

    .stat-card .stat-label {
        font-size: 0.725rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--saas-text-muted);
        margin-bottom: 0.25rem;
    }

    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--saas-text-main);
        line-height: 1.2;
    }

    .stat-card .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* Form Controls & Search Group */
    .saas-form-control, .saas-form-select {
        border: 1px solid var(--saas-border);
        border-radius: 8px;
        padding: 0.55rem 0.85rem;
        font-size: 0.875rem;
        color: var(--saas-text-main);
        background-color: #ffffff;
        transition: all 0.15s ease-in-out;
    }

    .saas-form-control:focus, .saas-form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
        outline: none;
    }

    .input-group-saas {
        border: 1px solid var(--saas-border);
        border-radius: 8px;
        background-color: #ffffff;
        transition: all 0.15s ease-in-out;
    }

    .input-group-saas:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .input-group-saas .input-group-text {
        background: transparent;
        border: none;
        color: #94a3b8;
    }

    .input-group-saas .form-control {
        border: none;
        box-shadow: none !important;
        padding-left: 0;
    }

    /* Table Design */
    .table-saas {
        width: 100%;
        min-width: 780px;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: auto;
    }

    .table-saas th {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.725rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid var(--saas-border);
        white-space: nowrap;
    }

    .table-saas td {
        padding: 0.85rem 1rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        white-space: nowrap;
    }

    .table-saas tbody tr {
        transition: background-color 0.15s ease;
    }

    .table-saas tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Baris siswa yang presensinya terkunci (hasil scan RFID) */
    .table-saas tbody tr.baris-terkunci {
        background-color: #fafafa;
    }
    .table-saas tbody tr.baris-terkunci:hover {
        background-color: #f4f4f5;
    }

    /* Segmented Control Status Kehadiran */
    .segmented-control {
        display: flex;
        background-color: #f1f5f9;
        padding: 4px;
        border-radius: 12px;
        border: 1px solid var(--saas-border);
        width: 100%;
        min-width: 270px;
    }
    .segmented-item {
        position: relative;
        flex: 1;
        text-align: center;
    }
    .segmented-item .btn-check {
        display: none;
    }
    .segmented-item .btn-segment {
        font-size: 0.825rem;
        font-weight: 700;
        padding: 8px 12px;
        min-height: 42px;
        border-radius: 9px;
        border: none;
        color: var(--saas-text-muted);
        background: transparent;
        cursor: pointer;
        transition: all 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        user-select: none;
    }
    .segmented-item .btn-segment:hover {
        color: var(--saas-text-main);
    }
    
    .btn-check:checked + .btn-segment.seg-hadir {
        background-color: #16a34a !important;
        color: #ffffff !important;
    }
    .btn-check:checked + .btn-segment.seg-sakit {
        background-color: #2563eb !important;
        color: #ffffff !important;
    }
    .btn-check:checked + .btn-segment.seg-izin {
        background-color: #d97706 !important;
        color: #ffffff !important;
    }
    .btn-check:checked + .btn-segment.seg-alpa {
        background-color: #dc2626 !important;
        color: #ffffff !important;
    }

    /* Pulsing Blob Dot untuk RFID */
    .blob-wrapper {
        position: relative;
        width: 8px;
        height: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .blob-dot-success {
        display: inline-block;
        width: 8px;
        height: 8px;
        background-color: #16a34a;
        border-radius: 50%;
        z-index: 2;
    }
    .blob-pulse-success {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #16a34a;
        animation: pulsingBlob 2s infinite ease-in-out;
        z-index: 1;
        opacity: 0.6;
    }
    @keyframes pulsingBlob {
        0% { transform: scale(1); opacity: 0.6; }
        100% { transform: scale(2.8); opacity: 0; }
    }

    /* Modal Clean Style */
    .modal-clean-content {
        border: 1px solid var(--saas-border);
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    }

    /* Floating / Sticky Save Box */
    .sticky-save-box {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 1060;
        background: #ffffff;
        padding: 8px;
        border-radius: 10px;
        border: 1px solid var(--saas-border);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        animation: slideInRight 0.25s ease-out;
    }
    @keyframes slideInRight {
        from { transform: translateX(30px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* Scroll to Top Button */
    .btn-scroll-top {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1050;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background-color: #0f172a;
        color: #ffffff;
        border: 1px solid var(--saas-border);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        display: none;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }
    .btn-scroll-top:hover {
        background-color: #1e293b;
        color: #ffffff;
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid text-start p-0">

    {{-- HEADER HALAMAN & TOMBOL SIMPAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom text-start">
        <div>
            <h3 class="fw-bold text-dark m-0 tracking-tight" style="font-size: 1.5rem;">Kelola Presensi Siswa</h3>
            <p class="text-muted small mb-0">Kelola master log kehadiran berkala siswa secara manual berdasarkan data kelas dan tanggal aktif.</p>
        </div>
        
        <div id="wrapperSimpanPresensi">
            @if(($kelas_id || auth()->user()->role == 'guru') && isset($semua_siswa) && $semua_siswa->count() > 0)
                <button type="button" id="btnSimpanPresensi" data-bs-toggle="modal" data-bs-target="#confirmPresensiModal" class="btn btn-primary px-3 py-2 fw-medium d-inline-flex align-items-center gap-1.5" style="border-radius: 8px; font-size: 0.85rem;">
                    <i class="bi bi-check-circle-fill small"></i> Simpan Presensi
                </button>
            @endif
        </div>
    </div>

    {{-- ALERT SUKSES --}}
    @if(session('sukses'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0 !important;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="fw-medium small">{{ session('sukses') }}</div>
            <button class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ALERT GAGAL --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca !important;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div class="fw-medium small">{{ session('error') }}</div>
            <button class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- PANEL FILTER --}}
    <div class="saas-card p-3 mb-4 text-start">
        <form action="/presensi/manual" method="GET">
            <div class="row g-2 align-items-center">
                @if(auth()->user()->role == 'admin')
                    <div class="col-md-5">
                        <select name="kelas_id" class="form-select saas-form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($daftar_kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    Kelas {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="tanggal" id="filter_tanggal" class="form-control saas-form-control bg-white" value="{{ request('tanggal', date('Y-m-d')) }}" placeholder="Pilih Tanggal">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 fw-medium d-flex align-items-center justify-content-center gap-1.5" style="border-radius: 8px; padding: 0.55rem; font-size: 0.875rem;">
                            <i class="bi bi-arrow-clockwise small"></i> Tampilkan Siswa
                        </button>
                    </div>
                @else
                    <div class="col-md-9">
                        <input type="text" name="tanggal" id="filter_tanggal" class="form-control saas-form-control bg-white" value="{{ request('tanggal', date('Y-m-d')) }}" placeholder="Pilih Tanggal">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100 fw-medium d-flex align-items-center justify-content-center gap-1.5" style="border-radius: 8px; padding: 0.55rem; font-size: 0.875rem;">
                            <i class="bi bi-arrow-clockwise small"></i> Tampilkan
                        </button>
                    </div>
                @endif
            </div>
        </form>
    </div>

    {{-- KONTEN UTAMA --}}
    @if($kelas_id || auth()->user()->role == 'guru')
        @php
            $jumlahHadir = $semua_siswa->filter(function($s) {
                return $s->status_hari_ini == 'Hadir' || $s->status_hari_ini == 'Terlambat';
            })->count();
            
            $jumlahSakit = $semua_siswa->where('status_hari_ini', 'Sakit')->count();
            $jumlahIzin  = $semua_siswa->where('status_hari_ini', 'Izin')->count();
            
            $jumlahAlpa  = $semua_siswa->filter(function($s) {
                return $s->status_hari_ini == 'Alpa' || $s->status_hari_ini == 'Belum Absen' || !$s->status_hari_ini;
            })->count();
        @endphp

        {{-- KARTU STATISTIK --}}
        <div class="stat-grid mb-4 text-start">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Siswa</div>
                        <div class="stat-value">{{ $semua_siswa->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f1f5f9; color: #475569;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #166534;">Hadir</div>
                        <div class="stat-value" style="color: #16a34a;">{{ $jumlahHadir }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #1e40af;">Sakit</div>
                        <div class="stat-value" style="color: #2563eb;">{{ $jumlahSakit }}</div>
                    </div>
                    <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #b45309;">Izin</div>
                        <div class="stat-value" style="color: #d97706;">{{ $jumlahIzin }}</div>
                    </div>
                    <div class="stat-icon" style="background: #fefce8; color: #d97706;">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #991b1b;">Alpa</div>
                        <div class="stat-value" style="color: #dc2626;">{{ $jumlahAlpa }}</div>
                    </div>
                    <div class="stat-icon" style="background: #fef2f2; color: #dc2626;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAR UTILITAS PENCARIAN & ACTION HADIR SEMUA --}}
        <div class="saas-card p-3 mb-4">
            <div class="row align-items-center g-3">
                <div class="col-md-6 text-start">
                    <div class="input-group-saas d-flex align-items-center px-2">
                        <span class="input-group-text p-0 me-2"><i class="bi bi-search small"></i></span>
                        <input type="text" id="searchSiswa" class="form-control saas-form-control border-0 ps-0 shadow-none" placeholder="Cari nama siswa atau NISN...">
                    </div>
                </div>
                <div class="col-md-6 text-md-end text-start">
                    <button type="button" id="btnHadirSemua" class="btn btn-success rounded-2 px-3 py-2 fw-medium d-inline-flex align-items-center gap-1.5" style="font-size: 0.85rem;">
                        <i class="bi bi-check-all fs-5 lh-1"></i> Hadir Semua
                    </button>
                </div>
            </div>
        </div>

        {{-- TABEL UTAMA PRESENSI --}}
        <div class="saas-card overflow-hidden mb-5 text-start">
            <form action="{{ url('/presensi/manual/simpan') }}" method="POST" id="formPresensi" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}">

                <div class="table-responsive">
                    <table class="table-saas">
                        <thead>
                            <tr>
                                <th style="min-width: 50px;" class="text-center">No</th>
                                <th style="min-width: 240px;">Nama Siswa</th>
                                <th style="min-width: 140px;">RFID Code</th>
                                <th style="min-width: 290px;">Status Kehadiran</th>
                                <th style="min-width: 200px;">Keterangan Alasan</th>
                                <th style="min-width: 130px;" class="text-center">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($semua_siswa) && $semua_siswa->count() > 0)
                                @foreach($semua_siswa as $index => $siswa)
                                @php
                                    $statusAwal = 'Alpa';
                                    if($siswa->status_hari_ini == 'Hadir' || $siswa->status_hari_ini == 'Terlambat') $statusAwal = 'Hadir';
                                    elseif($siswa->status_hari_ini == 'Sakit') $statusAwal = 'Sakit';
                                    elseif($siswa->status_hari_ini == 'Izin') $statusAwal = 'Izin';
                                @endphp
                                <tr class="baris-siswa {{ $siswa->terkunci_rfid ? 'baris-terkunci' : '' }}" data-status-aktif="{{ $statusAwal }}" data-asal="{{ $siswa->terkunci_rfid ? 'rfid' : 'manual' }}">
                                    <td class="text-center text-muted fw-medium font-monospace small">{{ $index + 1 }}</td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center gap-2 text-truncate">
                                            @if($siswa->foto)
                                                <img src="{{ asset('storage/'.$siswa->foto) }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover;" class="border flex-shrink-0" alt="Foto">
                                            @else
                                                <img src="{{ asset('assets/img/default-user.png') }}" style="width:32px; height:32px; border-radius:50%; object-fit:cover;" class="border flex-shrink-0" alt="Default">
                                            @endif
                                            <div class="overflow-hidden">
                                                <div class="fw-semibold text-dark text-uppercase text-truncate mb-0" style="font-size: 0.85rem;" data-search="{{ strtolower($siswa->nama_siswa) }} {{ $siswa->nisn }}">{{ $siswa->nama_siswa }}</div>
                                                <div class="text-muted font-monospace" style="font-size: 0.725rem;">NISN: {{ $siswa->nisn }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        @if($siswa->rfid_code)
                                            <div class="d-inline-flex align-items-center gap-2 px-2.5 py-1 rounded-2 border border-success border-opacity-25" style="background-color: #f0fdf4; color: #166534;">
                                                <div class="blob-wrapper">
                                                    <span class="blob-dot-success"></span>
                                                    <span class="blob-pulse-success"></span>
                                                </div>
                                                <span class="font-monospace fw-semibold" style="font-size: 0.775rem;">
                                                    {{ $siswa->rfid_code }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="badge bg-light text-secondary border rounded-2 py-1 px-2 fw-normal" style="font-size: 0.75rem;">
                                                <i class="bi bi-keyboard me-1 opacity-75"></i>Manual
                                            </span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        {{-- Baris hasil tap RFID kini tetap bisa diubah lewat menu manual, tidak dikunci lagi --}}
                                        <div class="segmented-control">
                                            <div class="segmented-item">
                                                <input type="radio" class="btn-check input-status-radio" name="presensi[{{ $siswa->id }}][status]" id="status_h_{{ $siswa->id }}" value="Hadir" data-siswa-id="{{ $siswa->id }}" {{ $statusAwal == 'Hadir' ? 'checked' : '' }}>
                                                <label class="btn-segment seg-hadir" for="status_h_{{ $siswa->id }}">Hadir</label>
                                            </div>
                                            <div class="segmented-item">
                                                <input type="radio" class="btn-check input-status-radio" name="presensi[{{ $siswa->id }}][status]" id="status_s_{{ $siswa->id }}" value="Sakit" data-siswa-id="{{ $siswa->id }}" {{ $statusAwal == 'Sakit' ? 'checked' : '' }}>
                                                <label class="btn-segment seg-sakit" for="status_s_{{ $siswa->id }}">Sakit</label>
                                            </div>
                                            <div class="segmented-item">
                                                <input type="radio" class="btn-check input-status-radio" name="presensi[{{ $siswa->id }}][status]" id="status_i_{{ $siswa->id }}" value="Izin" data-siswa-id="{{ $siswa->id }}" {{ $statusAwal == 'Izin' ? 'checked' : '' }}>
                                                <label class="btn-segment seg-izin" for="status_i_{{ $siswa->id }}">Izin</label>
                                            </div>
                                            <div class="segmented-item">
                                                <input type="radio" class="btn-check input-status-radio" name="presensi[{{ $siswa->id }}][status]" id="status_a_{{ $siswa->id }}" value="Alpa" data-siswa-id="{{ $siswa->id }}" {{ $statusAwal == 'Alpa' ? 'checked' : '' }}>
                                                <label class="btn-segment seg-alpa" for="status_a_{{ $siswa->id }}">Alpa</label>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <input type="text" name="presensi[{{ $siswa->id }}][keterangan]" class="saas-form-control form-control-sm" value="{{ $siswa->keterangan_hari_ini }}" placeholder="Contoh: Demam, Izin Keluarga" style="width: 100%; max-width: 240px; font-size: 0.8rem;" oninput="tandaiDiubah({{ $siswa->id }})">
                                    </td>
                                    
                                    <td>
                                        <div class="dokumen-upload-wrapper text-center" id="dokumen_wrapper_{{ $siswa->id }}" style="display: {{ in_array($statusAwal, ['Sakit', 'Izin']) ? 'block' : 'none' }};">
                                            <label class="btn btn-sm btn-light border d-inline-flex align-items-center justify-content-center gap-1 py-1 px-2 rounded-2 w-100" style="font-size: 0.75rem; cursor: pointer; border-style: dashed !important;">
                                                <i class="bi bi-cloud-upload"></i>
                                                <span class="file-name-label text-truncate" style="max-width: 100px;">Pilih Berkas</span>
                                                <input type="file" name="presensi[{{ $siswa->id }}][dokumen]" class="d-none input-file-dokumen" accept=".pdf,.jpg,.jpeg,.png" onchange="updateFileName(this); tandaiDiubah({{ $siswa->id }})">
                                            </label>
                                            @if($siswa->dokumen_hari_ini)
                                                <div class="mt-1">
                                                    <a href="{{ asset('storage/' . $siswa->dokumen_hari_ini) }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-2 fw-medium" style="font-size: 0.7rem; border-radius: 4px;">
                                                        <i class="bi bi-file-earmark-pdf-fill me-1"></i>Lihat File
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <input type="hidden" name="presensi[{{ $siswa->id }}][diubah]" id="diubah_{{ $siswa->id }}" value="0">
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <div class="py-3">
                                            <i class="bi bi-people text-slate-300 fs-1 d-block mb-2"></i>
                                            <h6 class="fw-bold text-dark mb-1">Belum Ada Data Siswa</h6>
                                            <p class="text-muted small mb-0">Belum ada data entitas siswa yang terdaftar di ruang kelas ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(isset($semua_siswa) && $semua_siswa->count() > 0)
                <div class="p-3 px-4 bg-white border-top d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan <span id="count-terfilter" class="fw-bold text-dark">{{ $semua_siswa->count() }}</span> dari <span class="fw-bold text-dark">{{ $semua_siswa->count() }}</span> total keseluruhan siswa kelas
                    </div>
                </div>
                @endif
            </form>
        </div>

        {{-- MODAL KONFIRMASI SIMPAN PRESENSI --}}
        <div class="modal fade" id="confirmPresensiModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
                <div class="modal-content modal-clean-content p-4 text-center">
                    <h5 class="fw-bold text-dark mb-2">Simpan Data Presensi?</h5>
                    <p class="text-muted small mb-4 px-1">Seluruh perubahan log kehadiran berkala siswa akan langsung disimpan dan diperbarui ke dalam sistem data master.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light border w-50 rounded-2 fw-medium" data-bs-dismiss="modal">Tidak, Batal</button>
                        <button type="submit" form="formPresensi" class="btn btn-primary w-50 rounded-2 fw-medium">Ya, Simpan</button>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- EMPTY STATE DASHBOARD --}}
        <div class="saas-card text-center py-5 px-4 d-flex align-items-center justify-content-center my-4" style="min-height: 380px;">
            <div class="py-3" style="max-width: 480px;">
                <div class="mb-3 d-inline-flex align-items-center justify-content-center rounded-circle bg-light text-primary" style="width: 72px; height: 72px;">
                    <i class="bi bi-journal-check fs-2 text-primary"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2" style="font-size: 1.15rem;">Formulir Lembar Presensi Belum Dimuat</h5>
                <p class="text-muted small lh-base mb-4">Untuk memulai melakukan rekap data presensi atau mengubah status log kehadiran, silakan pilih opsi <strong>Ruang Kelas</strong> dan tentukan <strong>Tanggal Presensi</strong> melalui panel kontrol filter atas.</p>
            </div>
        </div>
    @endif

    {{-- TOMBOL SCROLL TO TOP --}}
    <button type="button" id="scrollToTopBtn" class="btn-scroll-top" title="Kembali ke atas">
        <i class="bi bi-arrow-up-short fs-4"></i>
    </button>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // REALTIME SEARCH SISWA
    if(document.getElementById('searchSiswa')) {
        document.getElementById('searchSiswa').addEventListener('keyup', function() {
            let searchKeyword = this.value.toLowerCase();
            let visibleCount = 0;

            document.querySelectorAll('.baris-siswa').forEach(function(row) {
                let searchTarget = row.querySelector('[data-search]').dataset.search;
                if (searchTarget.includes(searchKeyword)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            let labelCount = document.getElementById('count-terfilter');
            if(labelCount) labelCount.innerText = visibleCount;
        });
    }

    // LOGIKA TOMBOL HADIR SEMUA
    const btnHadirSemua = document.getElementById('btnHadirSemua');
    if (btnHadirSemua) {
        btnHadirSemua.addEventListener('click', function() {
            document.querySelectorAll('.baris-siswa').forEach(function(row) {
                // Baris hasil tap RFID dilewati: tombol ini untuk siswa yang
                // belum presensi sama sekali (mis. lupa bawa kartu), bukan
                // untuk menimpa status yang sudah tercatat lewat tap kartu.
                if (row.dataset.asal === 'rfid') return;

                let radioHadir = row.querySelector('input[value="Hadir"]');
                if (radioHadir && !radioHadir.checked) {
                    radioHadir.checked = true;
                    radioHadir.dispatchEvent(new Event('change'));
                }
            });
        });
    }

    // STICKY FLOATING ACTION BUTTON & SCROLL TO TOP DETECTOR
    const wrapperSimpan = document.getElementById('wrapperSimpanPresensi');
    const btnSimpan = document.getElementById('btnSimpanPresensi');
    const btnScrollTop = document.getElementById('scrollToTopBtn');
    
    let initialOffsetTop = wrapperSimpan ? wrapperSimpan.offsetTop : 0;

    window.addEventListener('scroll', function() {
        if (wrapperSimpan && btnSimpan) {
            if (window.scrollY > (initialOffsetTop + 60)) {
                wrapperSimpan.classList.add('sticky-save-box');
            } else {
                wrapperSimpan.classList.remove('sticky-save-box');
            }
        }

        if (btnScrollTop) {
            if (window.scrollY > 300) {
                btnScrollTop.style.display = 'flex';
            } else {
                btnScrollTop.style.display = 'none';
            }
        }
    });

    if (btnScrollTop) {
        btnScrollTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // GLOBAL FILE NAME LABEL UPDATER
    window.updateFileName = function(input) {
        let labelSpan = input.closest('label').querySelector('.file-name-label');
        if (input.files && input.files.length > 0) {
            labelSpan.innerText = input.files[0].name;
            input.closest('label').classList.remove('btn-light');
            input.closest('label').classList.add('btn-success', 'text-white');
        } else {
            labelSpan.innerText = 'Pilih Berkas';
            input.closest('label').classList.remove('btn-success', 'text-white');
            input.closest('label').classList.add('btn-light');
        }
    };

    // FLATPICKR INITIALIZATION
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#filter_tanggal", {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
            allowInput: true,
            monthSelectorType: "dropdown"
        });
    }

    // PENANDA "DIUBAH ADMIN": dipasang di semua baris (baik hasil tap RFID
    // maupun manual). Baris hasil tap RFID yang TIDAK disentuh admin akan
    // tetap memakai data tap aslinya di backend (lihat storeManual()),
    // supaya klik tombol/aksi massal tidak diam-diam menimpa data tap.
    window.tandaiDiubah = function(siswaId) {
        let hidden = document.getElementById('diubah_' + siswaId);
        if (hidden) hidden.value = '1';
    };

    // SINKRONISASI STATUS INDIVIDU RADIO BUTTON
    document.querySelectorAll('.input-status-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            let parentRow = this.closest('.baris-siswa');
            parentRow.setAttribute('data-status-aktif', this.value);

            let siswaId = this.dataset.siswaId;
            tandaiDiubah(siswaId);
            let docWrapper = document.getElementById('dokumen_wrapper_' + siswaId);
            if (docWrapper) {
                if (this.value === 'Sakit' || this.value === 'Izin') {
                    docWrapper.style.display = 'block';
                } else {
                    docWrapper.style.display = 'none';
                    let fileInput = docWrapper.querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.value = '';
                        let labelSpan = docWrapper.querySelector('.file-name-label');
                        if (labelSpan) {
                            labelSpan.innerText = 'Pilih Berkas';
                        }
                        let labelOuter = docWrapper.querySelector('label');
                        if (labelOuter) {
                            labelOuter.classList.remove('btn-success', 'text-white');
                            labelOuter.classList.add('btn-light');
                        }
                    }
                }
            }
        });
    });
});
</script>

@endsection