@extends('layouts.app')

@section('title', 'Proses Kenaikan Kelas')

@section('content')

<style>
    /* SaaS Dashboard Design System (Slate & Blue Accent) */
    :root {
        --saas-bg: #f8fafc;
        --saas-card: #ffffff;
        --saas-border: #e2e8f0;
        --saas-text-dark: #0f172a;
        --saas-text-muted: #64748b;
        --saas-primary: #2563eb;
        --saas-primary-hover: #1d4ed8;
        --saas-primary-soft: #eff6ff;
        --saas-success: #16a34a;
        --saas-success-soft: #f0fdf4;
        --saas-warning: #d97706;
        --saas-warning-soft: #fffbeb;
    }

    body {
        background-color: var(--saas-bg);
        color: var(--saas-text-dark);
        font-family: 'Inter', 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
    }

    /* Badge & Button Style SaaS */
    .saas-title-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.813rem;
        font-weight: 600;
        color: #334155;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 4px 12px;
        border-radius: 6px;
    }

    .btn-back-saas {
        font-size: 0.825rem;
        font-weight: 500;
        color: var(--saas-text-muted);
        background-color: #ffffff;
        border: 1px solid var(--saas-border);
        border-radius: 8px;
        padding: 7px 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .btn-back-saas:hover {
        color: var(--saas-text-dark);
        background-color: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Card Wrapper SaaS */
    .saas-card-wrapper {
        background: #ffffff;
        border: 1px solid var(--saas-border);
        border-radius: 12px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    /* Panel Panduan Clean */
    .saas-info-alert {
        background-color: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 12px;
        padding: 14px 18px;
    }

    .saas-search-card {
        background-color: #ffffff;
        border: 1px solid var(--saas-border);
        border-radius: 12px;
        padding: 12px 16px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    /* Data Table Custom Styling SaaS */
    .table-kenaikan {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .table-kenaikan thead th {
        background-color: #f8fafc;
        color: var(--saas-text-muted);
        font-size: 0.725rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 20px;
        border-bottom: 1px solid var(--saas-border);
    }

    .table-kenaikan tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid var(--saas-border);
        font-size: 0.875rem;
    }

    .table-kenaikan tbody tr:last-child td {
        border-bottom: none;
    }

    .table-kenaikan tbody tr {
        transition: background-color 0.15s ease;
    }

    .table-kenaikan tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Status Pill Badge */
    .kk-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 20px;
        border: 1px solid;
    }

    .kk-status-pill.ok {
        color: var(--saas-success);
        background: var(--saas-success-soft);
        border-color: #bbf7d0;
    }

    .kk-status-pill.warn {
        color: var(--saas-warning);
        background: var(--saas-warning-soft);
        border-color: #fde68a;
    }

    /* Button Action - Soft Blue Style */
    .kk-kelola-btn {
        font-size: 0.813rem;
        font-weight: 600;
        color: var(--saas-primary);
        background: var(--saas-primary-soft);
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 6px 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
    }

    .kk-kelola-btn:hover {
        background: var(--saas-primary);
        color: #ffffff;
        border-color: var(--saas-primary);
        box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
    }

    .kelas-status-checkbox { display: none; }

    /* Modal Clean SaaS */
    .modal-clean-content {
        border: 1px solid var(--saas-border);
        border-radius: 14px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .student-scroll-container {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .student-scroll-container::-webkit-scrollbar {
        width: 4px;
    }
    .student-scroll-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .student-item {
        padding: 8px 12px;
        border-radius: 6px;
        background-color: #f8fafc;
        border: 1px solid var(--saas-border);
        transition: all 0.15s ease;
    }

    .student-item:hover {
        background-color: #ffffff;
        border-color: #94a3b8;
    }

    .student-item:has(.siswa-checkbox:checked) {
        background-color: #eff6ff;
        border-color: #bfdbfe;
    }

    .form-check-input:checked {
        background-color: var(--saas-primary);
        border-color: var(--saas-primary);
    }

    .form-check-input:checked + .student-name {
        color: var(--saas-primary) !important;
        font-weight: 600;
    }

    /* Sticky Bottom Action Bar */
    .sticky-action-bar {
        position: sticky;
        bottom: 20px;
        z-index: 1020;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border: 1px solid var(--saas-border);
        border-radius: 12px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
    }

    .btn-action-primary {
        background-color: var(--saas-primary);
        color: #ffffff;
        border: none;
        transition: all 0.2s ease;
    }

    .btn-action-primary:hover {
        background-color: var(--saas-primary-hover);
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    }
</style>

<div class="container-fluid text-start p-0">

    {{-- HEADER HALAMAN SAAS CLEAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-3 border-bottom">
        <div class="d-flex align-items-center gap-3 wrap">
            <h3 class="fw-bold text-dark m-0 tracking-tight" style="font-size: 1.35rem;">Proses Kenaikan Kelas</h3>
            <span class="saas-title-badge">
                <i class="bi bi-calendar-event"></i>
                Tahun Ajaran Aktif: {{ $tahunAktif->nama ?? '-' }}
            </span>
        </div>
        <div>
            <a href="{{ route('tahunajaran.index') }}" class="btn-back-saas">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Tahun Ajaran</span>
            </a>
        </div>
    </div>

    {{-- PANEL PANDUAN & PENCARIAN (RAPI, PRESISI, DAN TIDAK TERPOTONG) --}}
    <div class="row g-3 mb-4 align-items-stretch">
        <div class="col-lg-7">
            <div class="saas-info-alert h-100 d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px; font-size: 0.9rem;">
                    <i class="bi bi-info-circle-fill"></i>
                </div>
                <div style="font-size: 0.825rem; line-height: 1.45;" class="text-primary-emphasis">
                    <strong class="d-block mb-0.5 text-primary-emphasis" style="font-weight: 700;">Panduan Kenaikan Kelas:</strong>
                    Seluruh siswa default <strong>Naik Kelas</strong> (Kelas 6 ke <strong>Alumni</strong>). Klik <strong>Kelola Siswa</strong> jika ada yang tinggal kelas.
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="saas-search-card h-100 d-flex align-items-center">
                <div class="position-relative w-100">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 0.85rem;"></i>
                    <input type="text" id="search-global" class="form-control ps-5 py-2 border-1 rounded-3 text-dark shadow-none" style="font-size: 0.85rem; background-color: #f8fafc;" placeholder="Cari nama kelas ">
                </div>
            </div>
        </div>
    </div>

    <form id="formKenaikanKelas" action="{{ route('tahunajaran.proses') }}" method="POST">
        @csrf

        {{-- TABEL PROSES KENAIKAN KELAS SAAS --}}
        <div class="saas-card-wrapper overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-kenaikan align-middle">
                    <thead>
                        <tr>
                            <th style="width: 28%;">Tingkat / Nama Kelas</th>
                            <th style="width: 22%;">Jumlah Siswa</th>
                            <th style="width: 30%;">Status Kenaikan</th>
                            <th style="width: 20%; text-align: right;">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelas as $item)
                            @if($item->siswa->count() > 0)
                            <tr class="class-card-wrapper" data-kelas-nama="{{ strtolower($item->nama_kelas) }}">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-2 bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                                            <i class="bi bi-mortarboard-fill fs-6"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark d-block lh-sm" style="font-size: 0.925rem;">
                                                Kelas {{ $item->nama_kelas }}
                                            </span>
                                            <span class="text-muted small" style="font-size: 0.75rem;">Tingkat SD</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace px-2.5 py-1.5 rounded-2" style="font-size: 0.775rem; font-weight: 500;">
                                        <i class="bi bi-people me-1 text-muted"></i> 
                                        <span id="textCountKelas{{ $item->id }}">{{ $item->siswa->count() }} / {{ $item->siswa->count() }} Siswa Naik</span>
                                    </span>
                                </td>
                                <td>
                                    <input type="checkbox" class="kelas-status-checkbox" id="statusKelas{{ $item->id }}" checked disabled>
                                    <span class="kk-status-pill ok" id="statusPillKelas{{ $item->id }}">
                                        <i class="bi bi-check-circle-fill" id="statusIconKelas{{ $item->id }}"></i>
                                        <span id="statusTextKelas{{ $item->id }}">Semua Naik Kelas</span>
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <button type="button" class="kk-kelola-btn" data-bs-toggle="modal" data-bs-target="#modalKelas{{ $item->id }}">
                                        <i class="bi bi-person-gear"></i> Kelola Siswa
                                    </button>
                                </td>
                            </tr>

                            {{-- MODAL DETAIL SISWA PER KELAS --}}
                            <div class="modal fade" id="modalKelas{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content modal-clean-content">
                                        <div class="modal-header border-bottom px-4 py-3 bg-light rounded-top-4">
                                            <div>
                                                <h6 class="modal-title fw-bold text-dark m-0">
                                                    Daftar Siswa Kelas {{ $item->nama_kelas }}
                                                </h6>
                                                <span class="text-muted small" style="font-size: 0.775rem;">Total: {{ $item->siswa->count() }} Siswa Terdaftar</span>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="alert alert-light border small text-muted p-2.5 mb-3 rounded-2 d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                                                <i class="bi bi-info-circle text-primary fs-6"></i>
                                                <span>Hilangkan centang pada siswa yang <strong>TIDAK NAIK KELAS</strong>.</span>
                                            </div>

                                            {{-- FITUR PENCARIAN SISWA DI DALAM MODAL --}}
                                            <div class="position-relative mb-3">
                                                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 0.8rem;"></i>
                                                <input type="text" class="form-control form-control-sm ps-5 py-2 search-modal-siswa" data-target-group="{{ $item->id }}" placeholder="Cari nama siswa di kelas ini...">
                                            </div>

                                            {{-- LIST SELURUH SISWA KELAS --}}
                                            <div class="student-scroll-container">
                                                <div class="d-flex flex-column gap-2" data-kelas-group="{{ $item->id }}">
                                                    @foreach($item->siswa as $siswa)
                                                    <div class="form-check m-0 d-flex align-items-center student-item">
                                                        <input
                                                            class="form-check-input border-secondary-subtle m-0 shadow-none me-3 siswa-checkbox"
                                                            type="checkbox"
                                                            name="siswa[]"
                                                            value="{{ $siswa->id }}"
                                                            data-kelas-id="{{ $item->id }}"
                                                            checked
                                                            id="siswa{{ $siswa->id }}">

                                                        <label
                                                            class="form-check-label text-dark student-name text-uppercase w-100 py-0.5"
                                                            for="siswa{{ $siswa->id }}"
                                                            style="font-size: 0.825rem; cursor: pointer;">
                                                            {{ $siswa->nama_siswa }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top px-4 py-2.5 bg-light rounded-bottom-4">
                                            <button type="button" class="btn btn-primary btn-sm rounded-2 px-4 fw-medium" data-bs-dismiss="modal">
                                                Simpan & Selesai
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- FLOATING ACTIONS STICKY BOTTOM BAR --}}
        <div class="card sticky-action-bar mb-4 p-0">
            <div class="card-body p-3 px-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="text-start">
                        <h6 class="mb-0.5 fw-bold text-dark" style="font-size: 0.925rem;">Proses Kenaikan Kelas Siap Dieksekusi?</h6>
                        <p class="text-muted mb-0 small" style="font-size: 0.8rem;">
                            Siswa yang tidak dicentang akan menetap di kelas saat ini pada tahun ajaran baru.
                        </p>
                    </div>
                    <button
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmModal"
                        class="btn btn-action-primary px-4 py-2.5 rounded-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-2 shadow-sm"
                        style="font-size: 0.875rem; white-space: nowrap;">
                        <i class="bi bi-arrow-up-circle-fill"></i>
                        <span>Proses Kenaikan Kelas</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL KONFIRMASI --}}
        <div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
                <div class="modal-content modal-clean-content p-4 text-center">
                    <div class="mb-3 text-primary">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 52px; height: 52px;">
                            <i class="bi bi-arrow-up-circle fs-3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.1rem;">Eksekusi Kenaikan Kelas?</h5>
                    <p class="text-muted small mb-4 px-1" style="font-size: 0.8rem;">
                        Tindakan ini akan memperbarui status data akademik siswa ke tahun ajaran baru. Pastikan verifikasi siswa sudah sesuai.
                    </p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button type="button" class="btn btn-light border w-50 rounded-2 fw-medium btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary w-50 rounded-2 fw-medium btn-sm">Ya, Proses</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- SCRIPT PENDUKUNG --}}
<script>
    function updateStatusKelas(kelasId) {
        const checkboxes = document.querySelectorAll(`.siswa-checkbox[data-kelas-id="${kelasId}"]`);
        const total = checkboxes.length;
        const checked = document.querySelectorAll(`.siswa-checkbox[data-kelas-id="${kelasId}"]:checked`).length;

        const pill = document.getElementById(`statusPillKelas${kelasId}`);
        const icon = document.getElementById(`statusIconKelas${kelasId}`);
        const text = document.getElementById(`statusTextKelas${kelasId}`);
        const textCount = document.getElementById(`textCountKelas${kelasId}`);
        const trRow = pill.closest('tr');

        // UPDATE COUNT REAL-TIME
        if (textCount) {
            textCount.textContent = `${checked} / ${total} Siswa Naik`;
        }

        if (checked === total) {
            pill.classList.remove('warn');
            pill.classList.add('ok');
            icon.className = 'bi bi-check-circle-fill';
            text.textContent = 'Semua Naik Kelas';
            trRow.style.backgroundColor = '';
        } else if (checked === 0) {
            pill.classList.remove('ok');
            pill.classList.add('warn');
            icon.className = 'bi bi-exclamation-triangle-fill';
            text.textContent = 'Tidak Ada yang Naik Kelas';
            trRow.style.backgroundColor = '#fffbeb';
        } else {
            pill.classList.remove('ok');
            pill.classList.add('warn');
            icon.className = 'bi bi-exclamation-triangle-fill';
            const tidakNaik = total - checked;
            text.textContent = `${tidakNaik} Siswa Tidak Naik`;
            trRow.style.backgroundColor = '#fffbeb';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const semuaKelasId = new Set();

        document.querySelectorAll('.siswa-checkbox').forEach(cb => {
            const kelasId = cb.getAttribute('data-kelas-id');
            semuaKelasId.add(kelasId);
            cb.addEventListener('change', () => updateStatusKelas(kelasId));
        });

        semuaKelasId.forEach(kelasId => updateStatusKelas(kelasId));

        // PENCARIAN SISWA KHUSUS DI DALAM MODAL
        document.querySelectorAll('.search-modal-siswa').forEach(input => {
            input.addEventListener('keyup', function() {
                const targetGroupId = this.getAttribute('data-target-group');
                const filter = this.value.toLowerCase().trim();
                const studentContainer = document.querySelector(`[data-kelas-group="${targetGroupId}"]`);
                const studentItems = studentContainer.querySelectorAll('.student-item');

                studentItems.forEach(item => {
                    const name = item.querySelector('.student-name').textContent.toLowerCase();
                    if (name.includes(filter)) {
                        item.style.setProperty('display', 'flex', 'important');
                    } else {
                        item.style.setProperty('display', 'none', 'important');
                    }
                });
            });
        });
    });

    // PENCARIAN GLOBAL LINTAS KELAS (PERBAIKAN)
document.getElementById('search-global').addEventListener('keyup', function() {
    const filter = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.class-card-wrapper');

    rows.forEach(row => {
        if (filter === '') {
            row.style.setProperty('display', 'table-row', 'important');
            return;
        }

        // 1. Cek Pencarian berdasarkan Nama Kelas
        const namaKelas = row.getAttribute('data-kelas-nama') || '';
        const namaKelasCocok = namaKelas.includes(filter);

        // 2. Cek Pencarian berdasarkan Nama Siswa di Modal Terkait
        // Mengambil ID modal dari atribut data-bs-target tombol "Kelola Siswa"
        const btnKelola = row.querySelector('[data-bs-target]');
        let adaSiswaCocok = false;

        if (btnKelola) {
            const targetModalId = btnKelola.getAttribute('data-bs-target');
            const targetModal = document.querySelector(targetModalId);

            if (targetModal) {
                const labelsSiswa = targetModal.querySelectorAll('.student-name');
                labelsSiswa.forEach(label => {
                    if (label.textContent.toLowerCase().includes(filter)) {
                        adaSiswaCocok = true;
                    }
                });
            }
        }

        // 3. Tampilkan baris jika Nama Kelas atau Nama Siswa cocok
        if (namaKelasCocok || adaSiswaCocok) {
            row.style.setProperty('display', 'table-row', 'important');
        } else {
            row.style.setProperty('display', 'none', 'important');
        }
    });
});
</script>

@endsection