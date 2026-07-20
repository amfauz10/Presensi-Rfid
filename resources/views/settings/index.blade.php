@extends('layouts.app')

@section('title', 'Pengaturan Sistem - SDN Tengah 03')

@section('content')

<style>
    body {
        background-color: #f8fafc;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .shadow-premium {
        box-shadow: 0 8px 24px rgba(149, 157, 165, 0.05);
    }

    .segmented-control {
        background-color: #f1f3f5;
        padding: 5px;
        border-radius: 10px;
        display: inline-flex;
        flex-wrap: wrap;
    }
    .segmented-control .nav-link {
        color: #495057;
        border-radius: 8px;
        font-weight: 600;
        padding: 8px 20px;
        border: none;
        background: transparent;
        font-size: 0.9rem;
        white-space: nowrap;
    }
    .segmented-control .nav-link.active {
        background-color: #ffffff !important;
        color: #0d6efd !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08) !important;
    }
</style>

<div class="container-fluid text-start">

    {{-- ALERT NOTIFIKASI — selaras dengan halaman Kelas/Siswa --}}
    @if(session('sukses'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4 d-flex align-items-center text-start shadow-sm" role="alert" style="background:#dcfce7; color:#15803d;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="small fw-medium">{{ session('sukses') }}</div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 p-3 mb-4 d-flex align-items-center text-start shadow-sm" role="alert" style="background-color: #fef2f2; color: #b91c1c;">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div class="small fw-medium">{{ session('error') }}</div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- HEADER HALAMAN KONSISTEN --}}
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
                        <i class="bi bi-clock-history me-1"></i> Waktu & Hari
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-whatsapp" type="button" role="tab">
                        <i class="bi bi-whatsapp me-1"></i> WhatsApp Gateway
                    </button>
                </li>
            </ul>
        </div>

        <div class="card border-0 rounded-4 bg-white shadow-premium">
            <div class="card-body p-4">

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
                                <label class="form-label fw-semibold mb-2">Jam Mulai Presensi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-clock"></i></span>
                                    <input id="jam_mulai" type="text" name="jam_mulai" class="form-control shadow-none" style="padding: 10px 12px;"
                                        value="{{ old('jam_mulai', $settings['jam_mulai'] ?? '') }}">
                                </div>
                                <small class="text-muted">Siswa bisa mulai tap kartu sejak jam ini.</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2">Batas Hadir</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-clock-history"></i></span>
                                    <input id="batas_hadir" type="text" name="batas_hadir" class="form-control shadow-none" style="padding: 10px 12px;"
                                        value="{{ old('batas_hadir', $settings['batas_hadir'] ?? '') }}">
                                </div>
                                <small class="text-muted">Setelah jam ini, tercatat "Terlambat".</small>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold mb-2">Jam Presensi Ditutup</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                    <input id="jam_tutup" type="text" name="jam_tutup" class="form-control shadow-none" style="padding: 10px 12px;"
                                        value="{{ old('jam_tutup', $settings['jam_tutup'] ?? '') }}">
                                </div>
                                <small class="text-muted">Setelah jam ini, alat RFID tidak menerima presensi.</small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0" style="color: #1e293b;"><i class="bi bi-calendar-check me-2 text-success"></i>Hari Aktif Presensi</h6>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill fw-bold" id="hariAktifBadge">
                                {{ collect(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'])->sum(fn($d) => $settings[$d] ?? 0) }} Hari Aktif
                            </span>
                        </div>

                        <div class="row g-2">
                            @foreach(['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu', 'minggu' => 'Minggu'] as $key => $day)
                            @php $isActive = old($key, $settings[$key] ?? 0); @endphp
                            <div class="col-6 col-md-3">
                                <div class="form-check form-switch border rounded-3 px-3 py-2 m-0 d-flex align-items-center justify-content-between">
                                    <label class="form-check-label fw-semibold" style="font-size: 0.9rem;">{{ $day }}</label>
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
                        $defaultUrl = [
                            'Fonnte' => 'https://api.fonnte.com/send',
                            'Wablas' => 'https://(sesuai domain akun Anda, contoh: https://sby.wablas.com)',
                        ];
                    @endphp
                    <div class="tab-pane fade" id="tab-whatsapp" role="tabpanel">

                        <p class="text-muted mb-3 small">
                            Notifikasi WhatsApp ke orang tua siswa dikirim lewat provider di bawah ini. Pilih provider yang Anda pakai, lalu isi nomor & token.
                        </p>

                        <div class="mb-4">
                            <span class="badge {{ $waAktif ? 'bg-success' : 'bg-warning' }} bg-opacity-10 {{ $waAktif ? 'text-success' : 'text-warning-emphasis' }} px-3 py-1.5 rounded-pill fw-bold">
                                {{ $waAktif ? 'Sudah Terhubung' : 'Belum Diatur' }}
                            </span>
                            <span class="text-muted small ms-2">Provider aktif: {{ $providerAktif }}</span>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2">Provider WhatsApp Gateway</label>
                                <select name="wa_provider" id="wa_provider" class="form-select shadow-none" style="padding: 10px 12px;">
                                    <option value="Fonnte" {{ $providerAktif == 'Fonnte' ? 'selected' : '' }}>Fonnte</option>
                                    <option value="Wablas" {{ $providerAktif == 'Wablas' ? 'selected' : '' }}>Wablas</option>
                                    @unless($providerDikenal)
                                        <option value="{{ $providerAktif }}" selected disabled hidden>{{ $providerAktif }} (belum didukung)</option>
                                    @endunless
                                </select>
                                <small class="text-muted">Ganti provider di sini — endpoint di bawah otomatis menyesuaikan.</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2">Nomor WhatsApp Admin</label>
                                <input type="text" name="wa_admin" class="form-control shadow-none" style="padding: 10px 12px;"
                                    placeholder="Contoh: 628xxxxxxxxxx"
                                    value="{{ old('wa_admin', $settings['wa_admin'] ?? '') }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2">Endpoint API</label>
                                <input type="text" id="wa_url" name="wa_url" class="form-control shadow-none" style="padding: 10px 12px;"
                                    placeholder="{{ $defaultUrl[$providerAktif] ?? 'https://...' }}"
                                    value="{{ old('wa_url', $settings['wa_url'] ?? ($defaultUrl[$providerAktif] ?? '')) }}">
                                <small class="text-muted">
                                    @if($providerAktif == 'Wablas')
                                        Wablas memakai domain unik per akun — isi sesuai dashboard Wablas Anda (tanpa "/api/send-message" di belakang, sistem menambahkannya otomatis).
                                    @else
                                        Biarkan default kecuali diminta mengubah oleh penyedia layanan.
                                    @endif
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold mb-2">API Key / Token</label>
                                <div class="input-group">
                                    <input type="password" id="wa_token" name="wa_token" class="form-control shadow-none" style="padding: 10px 12px;"
                                        value="{{ old('wa_token', $settings['wa_token'] ?? '') }}">
                                    <button class="btn btn-outline-secondary shadow-none" type="button" onclick="toggleToken()">
                                        <i class="bi bi-eye" id="toggle_icon"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Token dari dashboard akun provider yang dipilih di atas.</small>
                            </div>
                        </div>

                        <div class="alert alert-light border rounded-4 p-3 mt-2 small mb-0">
                            <i class="bi bi-info-circle me-1"></i>
                            Provider baru harus didukung dulu di kode sistem (format API tiap provider berbeda) — <strong>Fonnte</strong> dan <strong>Wablas</strong> sudah bisa dipakai langsung. Kalau butuh provider lain, sampaikan ke pengembang sistem.
                        </div>
                    </div>

                </div>

                <div class="border-top pt-4 mt-4 d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light border rounded-3 px-4 fw-semibold text-secondary shadow-none">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Batalkan Perubahan
                    </button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm" style="box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">
                        <i class="bi bi-check-circle-fill me-2"></i> Simpan Konfigurasi
                    </button>
                </div>

            </div>
        </div>

        {{-- MODAL: Identitas Kepala Sekolah (dipicu ikon pojok kanan atas) --}}
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

    </form>
</div>

@push('scripts')
<script>
    flatpickr("#jam_mulai", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, minuteIncrement: 5 });
    flatpickr("#batas_hadir", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, minuteIncrement: 5 });
    flatpickr("#jam_tutup", { enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, minuteIncrement: 5 });

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