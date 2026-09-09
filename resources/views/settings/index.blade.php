@extends('layouts.app')

@section('title', 'Pengaturan Sistem - SDN Tengah 03')

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

    /* Kotak pratinjau: sengaja dibedakan dari kotak edit —
       latar abu menandakan area hasil sistem, bukan area yang bisa diketik */
    .preview-pesan {
        background-color: #f8fafc;
        border: 1px solid var(--saas-border);
        border-radius: 10px;
        padding: 16px 18px;
        font-size: 0.82rem;
        line-height: 1.7;
        color: #334155;
        white-space: pre-wrap;
        word-break: break-word;
    }

    /* Kotak edit dibuat kontras: putih bersih + fokus jelas */
    #pesan_template {
        background-color: #ffffff;
        font-size: 0.875rem;
        line-height: 1.7;
        resize: none;
        overflow: hidden;   /* tinggi menyesuaikan isi, jadi tak perlu scroll sendiri */
    }

    /* Chip penyisip variabel pada template pesan */
    .var-chip {
        background: #ffffff;
        border: 1px solid var(--saas-border);
        color: #475569;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1.4;
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }

    .var-chip:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: var(--saas-text-main);
    }

    .var-chip:active {
        background: #e2e8f0;
    }

    .var-chip:focus-visible {
        outline: 2px solid #0d6efd;
        outline-offset: 2px;
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

    /* Segmented Control / Modern Tabs */
    .segmented-control {
        background-color: #f1f5f9;
        padding: 4px;
        border-radius: 10px;
        display: inline-flex;
        gap: 4px;
        border: 1px solid var(--saas-border);
    }

    .segmented-control .nav-link {
        color: #64748b;
        border-radius: 7px;
        font-weight: 600;
        padding: 6px 16px;
        border: none;
        background: transparent;
        font-size: 0.85rem;
        transition: all 0.15s ease-in-out;
    }

    .segmented-control .nav-link:hover {
        color: #0f172a;
    }

    .segmented-control .nav-link.active {
        background-color: #ffffff !important;
        color: #2563eb !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    }

    /* Input & Form Controls SaaS */
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
        overflow: hidden;
        transition: all 0.15s ease-in-out;
    }

    .input-group-saas:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
    }

    .input-group-saas .input-group-text {
        background: #f8fafc;
        border: none;
        border-right: 1px solid var(--saas-border);
        color: #64748b;
    }

    .input-group-saas .form-control {
        border: none;
        box-shadow: none !important;
    }

    /* Modal Clean Style */
    .modal-clean-content {
        border: 1px solid var(--saas-border);
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    }

    /* SaaS Badges */
    .badge-saas {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-status-aktif { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-status-arsip { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }

    /* Compact Radio Cards for Mode WA */
    .mode-radio-card {
        border: 1px solid var(--saas-border);
        border-radius: 10px;
        padding: 12px;
        background-color: #ffffff;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .mode-radio-card:hover {
        border-color: #93c5fd;
        background-color: #f8fafc;
    }

    .mode-radio-card:has(input:checked) {
        border-color: #2563eb;
        background-color: #eff6ff;
    }
</style>

<div class="container-fluid text-start p-0">

    {{-- ALERT NOTIFIKASI — selaras dengan halaman Kelas/Siswa --}}
    @if(session('sukses'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0 !important;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="small fw-medium">{{ session('sukses') }}</div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0 text-start p-3 shadow-sm" role="alert" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca !important;">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div class="small fw-medium">{{ session('error') }}</div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- HEADER HALAMAN KONSISTEN (KEMBALI KE SEMULA) --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-3 border-bottom border-light text-start">
        <div>
            <h3 class="fw-bold m-0" style="letter-spacing: -0.5px; color: #1e293b;">Pengaturan Sistem</h3>
            <p class="text-muted m-0 mt-1 small" style="max-width: 600px;">
                Atur jam presensi & hari aktif, serta koneksi WhatsApp Gateway untuk notifikasi orang tua siswa.
            </p>
        </div>

        <button type="button" class="btn btn-light border rounded-circle shadow-none d-flex align-items-center justify-content-center"
                style="width: 42px; height: 42px;"
                data-bs-toggle="modal" data-bs-target="#modalKepalaSekolah"
                title="Identitas Kepala Sekolah">
            <i class="bi bi-person-badge text-muted"></i>
        </button>
    </div>

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-4 p-3 mb-4 shadow-sm" style="background-color: #fef2f2; color: #b91c1c;">
                <div class="fw-bold mb-2"><i class="bi bi-x-circle-fill me-2"></i>Terdapat kesalahan pengisian data:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- NAV TABS --}}
        <div class="mb-4">
            <ul class="nav segmented-control" id="settingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-waktu" type="button" role="tab">
                        <i class="bi bi-clock-history me-1.5"></i> Waktu & Hari
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-whatsapp" type="button" role="tab">
                        <i class="bi bi-whatsapp me-1.5"></i> WhatsApp Gateway
                    </button>
                </li>
            </ul>
        </div>

        <div class="saas-card overflow-hidden mb-5 text-start">
            <div class="p-4">

                <div class="tab-content">

                    {{-- =========================================================
                         TAB 1: WAKTU & HARI
                    ========================================================== --}}
                    <div class="tab-pane fade show active" id="tab-waktu" role="tabpanel">

                        <p class="text-muted mb-4 small">
                            Tentukan jam berapa siswa mulai bisa tap kartu, batas jam supaya tidak dianggap terlambat, dan jam presensi ditutup.
                        </p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Jam Mulai Presensi</label>
                                <div class="input-group-saas d-flex align-items-center">
                                    <span class="input-group-text px-3 py-2"><i class="bi bi-clock small"></i></span>
                                    <input id="jam_mulai" type="text" name="jam_mulai" class="form-control saas-form-control shadow-none"
                                        value="{{ old('jam_mulai', $settings['jam_mulai'] ?? '') }}">
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.75rem;">Siswa bisa mulai tap kartu sejak jam ini.</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Batas Hadir</label>
                                <div class="input-group-saas d-flex align-items-center">
                                    <span class="input-group-text px-3 py-2"><i class="bi bi-clock-history small"></i></span>
                                    <input id="batas_hadir" type="text" name="batas_hadir" class="form-control saas-form-control shadow-none"
                                        value="{{ old('batas_hadir', $settings['batas_hadir'] ?? '') }}">
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.75rem;">Setelah jam ini, tercatat "Terlambat".</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Jam Presensi Ditutup</label>
                                <div class="input-group-saas d-flex align-items-center">
                                    <span class="input-group-text px-3 py-2"><i class="bi bi-lock small"></i></span>
                                    <input id="jam_tutup" type="text" name="jam_tutup" class="form-control saas-form-control shadow-none"
                                        value="{{ old('jam_tutup', $settings['jam_tutup'] ?? '') }}">
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.75rem;">Setelah jam ini, alat RFID tidak menerima presensi.</div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: var(--saas-border);">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">
                                <i class="bi bi-calendar-check me-2 text-success"></i>Hari Aktif Presensi
                            </h6>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill fw-bold" id="hariAktifBadge">
                                {{ collect(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'])->sum(fn($d) => $settings[$d] ?? 0) }} Hari Aktif
                            </span>
                        </div>

                        <div class="row g-2">
                            @foreach(['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu', 'minggu' => 'Minggu'] as $key => $day)
                            @php $isActive = old($key, $settings[$key] ?? 0); @endphp
                            <div class="col-6 col-md-3">
                                <div class="form-check form-switch border rounded-3 px-3 py-2.5 m-0 d-flex align-items-center justify-content-between bg-light">
                                    <label class="form-check-label fw-semibold text-dark small m-0" style="cursor: pointer;">{{ $day }}</label>
                                    <input class="form-check-input day-switch shadow-none m-0" type="checkbox" name="{{ $key }}" value="1" {{ $isActive ? 'checked' : '' }}>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- =========================================================
                         TAB 2: WHATSAPP GATEWAY
                    ========================================================== --}}
                    @php
                        $providerAktif = $settings['wa_provider'] ?? 'Fonnte';
                        $providerDikenal = in_array($providerAktif, ['Fonnte', 'Wablas']);
                        $waAktif = !empty($settings['wa_token'] ?? null);

                        // Default queue agar perilaku sistem lama tetap dipertahankan
                        $waMode = old('wa_mode', $settings['wa_mode'] ?? 'queue');

                        $defaultUrl = [
                            'Fonnte' => 'https://api.fonnte.com/send',
                            'Wablas' => 'https://(sesuai domain akun Anda, contoh: https://sby.wablas.com)',
                        ];
                    @endphp
                    <div class="tab-pane fade" id="tab-whatsapp" role="tabpanel">

                        @php
                            $templateBawaan = \App\Models\Setting::defaultTemplatePesan();
                            $templateTampil = old('pesan_template', $settings['pesan_template'] ?? $templateBawaan);
                            if (trim($templateTampil) === '') { $templateTampil = $templateBawaan; }

                            $modeLabel = [
                                'off'    => 'Nonaktif — orang tua tidak menerima pesan',
                                'queue'  => 'Antre di latar belakang',
                                'direct' => 'Langsung setelah presensi',
                            ];
                        @endphp

                        {{-- KONEKSI --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Provider</label>
                                <select name="wa_provider" id="wa_provider" class="form-select saas-form-select">
                                    <option value="Fonnte" {{ $providerAktif == 'Fonnte' ? 'selected' : '' }}>Fonnte</option>
                                    <option value="Wablas" {{ $providerAktif == 'Wablas' ? 'selected' : '' }}>Wablas</option>
                                    @unless($providerDikenal)
                                        <option value="{{ $providerAktif }}" selected disabled hidden>{{ $providerAktif }} (belum didukung)</option>
                                    @endunless
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Nomor WhatsApp Admin</label>
                                <input type="text" name="wa_admin" class="form-control saas-form-control"
                                    placeholder="628xxxxxxxxxx"
                                    value="{{ old('wa_admin', $settings['wa_admin'] ?? '') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Endpoint API</label>
                                <input type="text" id="wa_url" name="wa_url" class="form-control saas-form-control"
                                    placeholder="{{ $defaultUrl[$providerAktif] ?? 'https://...' }}"
                                    value="{{ old('wa_url', $settings['wa_url'] ?? ($defaultUrl[$providerAktif] ?? '')) }}">
                                @if($providerAktif == 'Wablas')
                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">Isi domain akun Wablas Anda, tanpa "/api/send-message".</div>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">API Key / Token</label>
                                <div class="input-group-saas d-flex align-items-center">
                                    <input type="password" id="wa_token" name="wa_token" class="form-control saas-form-control border-0"
                                        value="{{ old('wa_token', $settings['wa_token'] ?? '') }}">
                                    <button class="btn btn-link text-secondary shadow-none border-0 px-3 py-2" type="button" onclick="toggleToken()">
                                        <i class="bi bi-eye" id="toggle_icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: var(--saas-border);">

                        {{-- ISI PESAN --}}
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <label for="pesan_template" class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Isi Pesan ke Orang Tua</label>

                                <textarea name="pesan_template" id="pesan_template" rows="12" spellcheck="false"
                                    class="form-control saas-form-control">{{ $templateTampil }}</textarea>

                                <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                                    <span class="text-muted" style="font-size: 0.75rem;">Sisipkan</span>
                                    @foreach(\App\Models\Setting::variabelPesan() as $kode => $label)
                                        <button type="button" class="var-chip insert-var-btn" data-var="{{ $kode }}" title="{{ $kode }}">{{ $label }}</button>
                                    @endforeach
                                </div>

                                <p class="text-muted mt-3 mb-0" style="font-size: 0.75rem;">
                                    Teks dalam kurung kurawal diganti otomatis dengan data siswa.
                                    <button type="button" id="btn_reset_template" class="btn btn-link p-0 align-baseline text-decoration-underline text-secondary" style="font-size: 0.75rem;">Kembalikan ke format bawaan</button>
                                </p>
                            </div>

                            <div class="col-lg-5">
                                <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Pratinjau</label>
                                <div id="preview_pesan" class="preview-pesan"></div>
                            </div>
                        </div>

                        <hr class="my-4" style="border-color: var(--saas-border);">

                        {{-- PENGIRIMAN --}}
                        <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Pengiriman</label>
                        <div class="d-flex align-items-center justify-content-between gap-3 border rounded-3 px-3 py-2" style="border-color: var(--saas-border) !important;">
                            <span class="text-dark" style="font-size: 0.875rem;">{{ $modeLabel[$waMode] ?? $waMode }}</span>
                            <button type="button" class="btn btn-sm btn-light border rounded-2 fw-semibold text-secondary shadow-none px-3" data-bs-toggle="modal" data-bs-target="#modalModeWA" style="font-size: 0.78rem;">
                                Ubah
                            </button>
                        </div>

                    </div>

                </div>

                {{-- FOOTER BUTTONS --}}
                <div class="border-top pt-4 mt-2 d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light border rounded-3 px-4 fw-semibold text-secondary shadow-none">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Batalkan Perubahan
                    </button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm" style="box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">
                        <i class="bi bi-check-circle-fill me-2"></i> Simpan Konfigurasi
                    </button>
                </div>

            </div>
        </div>

        {{-- MODAL: Identitas Kepala Sekolah (KEMBALI EXAK KODE SEMULA) --}}
        @php $sekolahLengkap = !empty($settings['kepala_sekolah'] ?? null) && !empty($settings['nip_kepala'] ?? null); @endphp
        <div class="modal fade" id="modalKepalaSekolah" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header border-0 pb-0">
                        <h6 class="modal-title fw-bold" style="color: #1e293b;"><i class="bi bi-person-badge me-2"></i>Identitas Kepala Sekolah</h6>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-3">
                        <p class="text-muted mb-3 small">
                            Nama dan NIP di bawah ini otomatis muncul sebagai tanda tangan pengesahan pada laporan presensi yang dicetak.
                        </p>

                        <div class="mb-3">
                            <span class="badge {{ $sekolahLengkap ? 'bg-success' : 'bg-warning' }} bg-opacity-10 {{ $sekolahLengkap ? 'text-success' : 'text-warning-emphasis' }} px-3 py-1.5 rounded-pill fw-bold">
                                {{ $sekolahLengkap ? 'Data Lengkap' : 'Belum Lengkap' }}
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold mb-2">Nama Sekolah</label>
                                <input type="text" name="nama_sekolah" class="form-control shadow-none" style="padding: 10px 12px;"
                                    value="{{ old('nama_sekolah', $settings['nama_sekolah'] ?? 'SD Negeri Tengah 03 Jakarta Timur') }}"
                                    placeholder="Contoh: SD Negeri Tengah 03 Jakarta Timur">
                                <div class="text-muted mt-1" style="font-size: 0.75rem;">Dipakai pada variabel <code>{nama_sekolah}</code> di pesan WhatsApp.</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold mb-2">Nama Kepala Sekolah</label>
                                <input type="text" name="kepala_sekolah" class="form-control shadow-none" style="padding: 10px 12px;"
                                    value="{{ old('kepala_sekolah', $settings['kepala_sekolah'] ?? '') }}"
                                    placeholder="Contoh: SUGESTI, S.Pd">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold mb-2">NIP Kepala Sekolah</label>
                                <input type="text" name="nip_kepala" class="form-control shadow-none" style="padding: 10px 12px;"
                                    value="{{ old('nip_kepala', $settings['nip_kepala'] ?? '') }}"
                                    placeholder="196607081998032003">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light border rounded-3 px-3 fw-semibold text-secondary shadow-none" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-3 fw-semibold shadow-sm" style="box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">
                            <i class="bi bi-check-circle-fill me-1"></i> Simpan Konfigurasi
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL: Konfirmasi Kembalikan Template Pesan ke Format Bawaan --}}
        <div class="modal fade" id="modalResetTemplate" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
                <div class="modal-content modal-clean-content overflow-hidden text-start">
                    <div class="p-4 pb-0 text-center">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                             style="width: 52px; height: 52px; background-color: #fffbeb;">
                            <i class="bi bi-arrow-counterclockwise text-warning" style="font-size: 1.35rem;"></i>
                        </div>
                        <h6 class="fw-bold mb-2 text-dark" style="font-size: 1rem;">Kembalikan ke Format Bawaan?</h6>
                        <p class="text-muted small mb-0">
                            Isi pesan yang sedang diketik akan diganti dengan format bawaan sistem. Perubahan yang belum disimpan akan hilang.
                        </p>
                    </div>
                    <div class="p-4 pt-3 d-flex gap-2">
                        <button type="button" class="btn btn-light border rounded-3 fw-semibold text-secondary shadow-none flex-fill" data-bs-dismiss="modal">
                            Tidak
                        </button>
                        <button type="button" id="btn_confirm_reset_template" class="btn btn-primary rounded-3 fw-semibold shadow-none flex-fill">
                            Ya, Kembalikan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL: Mode Notifikasi WhatsApp (Disembunyikan Rapi) --}}
        <div class="modal fade" id="modalModeWA" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-clean-content overflow-hidden text-start">
                    <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Mode Notifikasi WhatsApp</h6>
                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="p-4">
                        <p class="text-muted small mb-3">Tentukan mekanisme eksekusi pengiriman pesan WhatsApp oleh sistem presensi.</p>

                        <div class="d-flex flex-column gap-2.5">
                            {{-- NONAKTIF --}}
                            <label class="mode-radio-card d-flex align-items-start gap-2.5">
                                <input class="form-check-input mt-1 shadow-none" type="radio" name="wa_mode" id="wa_mode_off" value="off" {{ $waMode === 'off' ? 'checked' : '' }}>
                                <div>
                                    <div class="fw-bold text-dark small">Nonaktif</div>
                                    <div class="text-muted" style="font-size: 0.775rem;">Presensi tetap berjalan tanpa mengirim notifikasi WhatsApp.</div>
                                </div>
                            </label>

                            {{-- QUEUE --}}
                            <label class="mode-radio-card d-flex align-items-start gap-2.5">
                                <input class="form-check-input mt-1 shadow-none" type="radio" name="wa_mode" id="wa_mode_queue" value="queue" {{ $waMode === 'queue' ? 'checked' : '' }}>
                                <div>
                                    <div class="fw-bold text-dark small">Queue / Background (Rekomendasi)</div>
                                    <div class="text-muted" style="font-size: 0.775rem;">Diproses di background via Laravel Queue. Memerlukan queue worker.</div>
                                </div>
                            </label>

                            {{-- DIRECT --}}
                            <label class="mode-radio-card d-flex align-items-start gap-2.5">
                                <input class="form-check-input mt-1 shadow-none" type="radio" name="wa_mode" id="wa_mode_direct" value="direct" {{ $waMode === 'direct' ? 'checked' : '' }}>
                                <div>
                                    <div class="fw-bold text-dark small">Langsung (Direct)</div>
                                    <div class="text-muted" style="font-size: 0.775rem;">Pesan dikirim seketika saat tap tanpa antrean worker.</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="p-3 px-4 bg-light border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light border rounded-2 fw-medium px-3" style="font-size: 0.875rem;" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary rounded-2 fw-medium px-3" style="font-size: 0.875rem;">Simpan Konfigurasi</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
    if (typeof flatpickr !== 'undefined') {
        flatpickr("#jam_mulai", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, minuteIncrement: 5 });
        flatpickr("#batas_hadir", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, minuteIncrement: 5 });
        flatpickr("#jam_tutup", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, minuteIncrement: 5 });
    }

    document.querySelectorAll('.day-switch').forEach(item => {
        item.addEventListener('change', updateHariAktifBadge);
    });
    function updateHariAktifBadge() {
        let total = document.querySelectorAll('.day-switch:checked').length;
        document.getElementById('hariAktifBadge').textContent = total + ' Hari Aktif';
    }

    // Auto-isi Endpoint API default saat provider WhatsApp diganti
    const defaultEndpoints = {
        'Fonnte': 'https://api.fonnte.com/send',
        'Wablas': '',
    };
    if (document.getElementById('wa_provider')) {
        document.getElementById('wa_provider').addEventListener('change', function() {
            const urlField = document.getElementById('wa_url');
            const selected = this.value;
            if (selected === 'Fonnte') {
                urlField.value = defaultEndpoints['Fonnte'];
                urlField.placeholder = defaultEndpoints['Fonnte'];
            } else if (selected === 'Wablas') {
                urlField.value = '';
                urlField.placeholder = 'https://(sesuai domain akun Wablas Anda)';
            }
        });
    }

    /* =======================================================
       TEMPLATE PESAN WHATSAPP
       ======================================================= */
    const kotakTemplate = document.getElementById('pesan_template');

    if (kotakTemplate) {

        // Data contoh untuk pratinjau
        const contohData = {
            '{nama_siswa}'   : 'Aisyah Nur Fadhilah',
            '{kelas}'        : 'IV-B',
            '{tanggal}'      : new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }),
            '{jam}'          : '07:12',
            '{status}'       : 'Hadir',
            '{nama_sekolah}' : @json(\App\Models\Setting::getValue('nama_sekolah', 'SD Negeri Tengah 03 Jakarta Timur'))
        };

        const templateBawaan = @json(\App\Models\Setting::defaultTemplatePesan());

        function perbaruiPratinjau() {
            let hasil = kotakTemplate.value;

            for (const kode in contohData) {
                hasil = hasil.split(kode).join(contohData[kode]);
            }

            document.getElementById('preview_pesan').textContent =
                hasil.trim() === '' ? 'Pesan kosong — sistem akan memakai format bawaan.' : hasil;
        }

        // Tinggi kotak mengikuti panjang pesan supaya tidak muncul
        // scrollbar di dalam halaman yang juga sudah bisa di-scroll.
        function samakanTinggi() {
            kotakTemplate.style.height = 'auto';
            const tinggi = Math.max(kotakTemplate.scrollHeight, 280);
            kotakTemplate.style.height = tinggi + 'px';
            document.getElementById('preview_pesan').style.minHeight = (tinggi + 2) + 'px';
        }

        kotakTemplate.addEventListener('input', function() {
            perbaruiPratinjau();
            samakanTinggi();
        });

        perbaruiPratinjau();
        samakanTinggi();

        // Tab baru dibuka: hitung ulang karena scrollHeight elemen tersembunyi bernilai 0
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(t) {
            t.addEventListener('shown.bs.tab', samakanTinggi);
        });

        // Tombol sisip variabel: taruh di posisi kursor, tanpa perlu mengetik manual
        document.querySelectorAll('.insert-var-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const teksVar = btn.getAttribute('data-var');
                const mulai = kotakTemplate.selectionStart;
                const akhir = kotakTemplate.selectionEnd;
                const teksSekarang = kotakTemplate.value;

                kotakTemplate.value = teksSekarang.substring(0, mulai) + teksVar + teksSekarang.substring(akhir);

                const posisiBaru = mulai + teksVar.length;
                kotakTemplate.focus();
                kotakTemplate.setSelectionRange(posisiBaru, posisiBaru);
                perbaruiPratinjau();
                samakanTinggi();
            });
        });

        // Tombol "Kembalikan ke format bawaan" -> buka modal konfirmasi SaaS
        // (menggantikan confirm() bawaan browser)
        const btnReset = document.getElementById('btn_reset_template');
        const modalResetEl = document.getElementById('modalResetTemplate');
        const modalReset = modalResetEl ? new bootstrap.Modal(modalResetEl) : null;

        if (btnReset && modalReset) {
            btnReset.addEventListener('click', function() {
                modalReset.show();
            });
        }

        const btnConfirmReset = document.getElementById('btn_confirm_reset_template');
        if (btnConfirmReset) {
            btnConfirmReset.addEventListener('click', function() {
                kotakTemplate.value = templateBawaan;
                perbaruiPratinjau();
                samakanTinggi();
                if (modalReset) modalReset.hide();
                kotakTemplate.focus();
            });
        }
    }
</script>
@endpush

<script>
function toggleToken(){
    let input = document.getElementById('wa_token');
    let icon = document.getElementById('toggle_icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

@endsection