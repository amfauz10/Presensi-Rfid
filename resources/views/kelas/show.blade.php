@extends('layouts.app')

@section('title', 'Data Siswa - ' . $kelas->nama_kelas)

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

    /* Stat Cards */
    .stat-card {
        background: #ffffff;
        border: 1px solid var(--saas-border);
        border-radius: 12px;
        padding: 1.25rem;
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
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--saas-text-main);
        line-height: 1.2;
    }

    .stat-card .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Custom Input Controls & Search */
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

    /* Filter Buttons */
    .btn-filter-saas {
        border: 1px solid var(--saas-border);
        background-color: #ffffff;
        color: var(--saas-text-muted);
        font-size: 0.825rem;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.45rem 0.85rem;
        transition: all 0.15s ease-in-out;
    }

    .btn-filter-saas:hover {
        background-color: #f8fafc;
        color: var(--saas-text-main);
    }

    .btn-filter-saas.active {
        background-color: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    /* Table Design */
    .table-saas {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
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
    }

    .table-saas td {
        padding: 0.9rem 1rem;
        font-size: 0.875rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-saas tbody tr {
        transition: background-color 0.15s ease;
    }

    .table-saas tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Action Buttons */
    .action-btn-saas {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--saas-border);
        background-color: #ffffff;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .action-btn-saas.edit:hover {
        background-color: #fffbeb;
        border-color: #fcd34d;
        color: #d97706 !important;
    }

    .action-btn-saas.delete:hover {
        background-color: #fef2f2;
        border-color: #fca5a5;
        color: #dc2626 !important;
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

    .badge-rfid-active { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .badge-rfid-empty { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    /* Modal Clean */
    .modal-clean-content {
        border: 1px solid var(--saas-border);
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    }

    .scroll-x-mobile { overflow-x: auto; white-space: nowrap; max-width: 100%; }
</style>

<div class="container-fluid text-start p-0">

    {{-- ALERT BERHASIL --}}
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

    {{-- ALERT ERRORS VALIDASI --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0 text-start p-3 shadow-sm" role="alert" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca !important;">
            <div class="d-flex align-items-center mb-1">
                <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                <strong class="small fw-bold">Terdapat kesalahan validasi berkas:</strong>
            </div>
            <ul class="mb-0 small ps-4 fw-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- HEADER HALAMAN --}}
    <div class="row align-items-center g-3 mb-4 text-start">
        <div class="col-12 col-lg-7">
            <a href="{{ route('kelas.index') }}" class="btn btn-sm btn-white border rounded-2 px-2.5 py-1 fw-medium text-secondary d-inline-flex align-items-center gap-1.5 mb-2 shadow-none" style="font-size: 0.8rem;">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <h3 class="fw-bold text-dark m-0 tracking-tight" style="font-size: 1.5rem;">Data Siswa Kelas {{ $kelas->nama_kelas }}</h3>
        </div>

        <div class="col-12 col-lg-5 text-lg-end">
            <div class="d-inline-flex flex-wrap gap-2">
                <a href="{{ route('siswa.tambah') }}?kelas_id={{ $kelas->id }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3 py-2 fw-medium" style="border-radius: 8px; font-size: 0.85rem;">
                    <i class="bi bi-plus-lg small"></i>Tambah Siswa
                </a>
                
                <button class="btn btn-success d-inline-flex align-items-center gap-1.5 px-3 py-2 fw-medium" style="border-radius: 8px; font-size: 0.85rem;" data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="bi bi-file-earmark-excel small"></i>Import Excel
                </button>

                @if($semua_siswa->count())
                <button type="button" class="btn btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-2 fw-medium" style="border-radius: 8px; font-size: 0.85rem;" data-bs-toggle="modal" data-bs-target="#confirmDeleteAllModal">
                    <i class="bi bi-trash3 small"></i> Hapus Semua
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="row g-3 mb-4 text-start">
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Siswa</div>
                        <div class="stat-value">{{ $semua_siswa->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #166534;">RFID Terdaftar</div>
                        <div class="stat-value" style="color: #16a34a;">{{ $semua_siswa->whereNotNull('rfid_code')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-credit-card-2-front-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #d97706;">Belum Miliki RFID</div>
                        <div class="stat-value" style="color: #d97706;">{{ $semua_siswa->whereNull('rfid_code')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #fefce8; color: #d97706;">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- UTILITIES: SEARCH & FILTER SYSTEM --}}
    <div class="saas-card p-3 mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-md-6">
                <div class="input-group-saas d-flex align-items-center px-2">
                    <span class="input-group-text p-0 me-2"><i class="bi bi-search small"></i></span>
                    <input type="text" id="siswaSearch" class="form-control saas-form-control border-0 ps-0 shadow-none" placeholder="Cari nama siswa atau nomor induk NIS/NISN...">
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="d-flex gap-2 justify-content-md-end scroll-x-mobile">
                    <button type="button" class="btn-filter-saas filter-btn active" data-filter="all">Semua Data</button>
                    <button type="button" class="btn-filter-saas filter-btn" data-filter="rfid-active">🟢 RFID Aktif</button>
                    <button type="button" class="btn-filter-saas filter-btn" data-filter="rfid-empty">⚪ Belum Terdaftar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE DATA CARD --}}
    <div class="saas-card overflow-hidden mb-5 text-start">
        <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Daftar Siswa Aktif</h6>
            <span class="badge bg-light text-secondary border fw-normal font-monospace" style="font-size: 0.775rem;" id="counterRows">{{ $semua_siswa->count() }} Data</span>
        </div>

        <div class="table-responsive">
            <table class="table-saas" id="tableSiswa">
                <thead>
                    <tr>
                        <th style="width: 5%" class="text-center">No</th>
                        <th style="width: 20%">NIS / NISN</th>
                        <th style="width: 32%">Nama Lengkap Siswa</th>
                        <th style="width: 20%">No. WhatsApp Wali</th>
                        <th style="width: 23%">Status Registrasi RFID</th>
                        <th style="width: 10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($semua_siswa as $index => $siswa)
                    <tr class="siswa-row" data-rfid-status="{{ $siswa->rfid_code ? 'active' : 'empty' }}">
                        <td class="text-center text-muted fw-medium font-monospace small">{{ $index + 1 }}</td>
                        <td class="text-secondary font-monospace small">
                            {{ $siswa->nis ?? $siswa->nisn ?? '-' }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2 text-truncate">
                                @if($siswa->foto)
                                    <img src="{{ asset('storage/'.$siswa->foto) }}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; flex-shrink: 0;" class="border" alt="Foto {{ $siswa->nama_siswa }}">
                                @else
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-secondary" style="width: 32px; height: 32px; background-color: #f1f5f9; flex-shrink: 0; font-size: 0.85rem;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                @endif
                                <span class="fw-semibold text-dark text-name-target text-truncate">{{ $siswa->nama_siswa }}</span>
                            </div>
                        </td>
                        <td class="small">
                            @if(empty($siswa->no_hp_orang_tua))
                                <span class="text-muted fst-italic"><i class="bi bi-slash-circle me-1"></i>Belum Diisi</span>
                            @else
                                <span class="font-monospace text-dark d-inline-flex align-items-center gap-1.5">
                                    <i class="bi bi-whatsapp text-success"></i> {{ $siswa->no_hp_orang_tua }}
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($siswa->rfid_code)
                                <span class="badge-saas badge-rfid-active font-monospace">
                                    <i class="bi bi-credit-card-2-front"></i> {{ $siswa->rfid_code }}
                                </span>
                            @else
                                <span class="badge-saas badge-rfid-empty">
                                    <i class="bi bi-card-heading opacity-75"></i> Belum Terdaftar
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1.5">
                                <a href="{{ route('siswa.edit', $siswa->id) }}" class="action-btn-saas edit text-secondary" title="Edit Log Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <button type="button"
                                        class="action-btn-saas delete text-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteModal"
                                        data-siswa-id="{{ $siswa->id }}"
                                        data-siswa-name="{{ $siswa->nama_siswa }}"
                                        title="Hapus Permanen">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRowInitial">
                        <td colspan="6" class="text-center text-muted py-5">
                            <div class="py-3">
                                <i class="bi bi-people text-slate-300 fs-1 d-block mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Data Siswa</h6>
                                <p class="text-muted small mb-0">Kelas ini masih kosong. Silakan tambahkan entitas siswa secara manual atau gunakan fitur import template berkas excel.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse

                <tr id="emptyRowSearch" style="display: none;">
                    <td colspan="6" class="text-center text-muted py-5">
                        <div class="py-3">
                            <i class="bi bi-search text-slate-300 fs-1 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Data Siswa Tidak Ditemukan</h6>
                            <p class="text-muted small mb-0">Kata kunci atau filter pencarian tidak cocok dengan record siswa mana pun di kelas ini.</p>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL IMPORT --}}
<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data" class="w-100">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

            <div class="modal-content modal-clean-content overflow-hidden">
                <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Import Spreadsheet Siswa</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="p-4 text-start">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-2" style="font-size: 0.725rem;">Unggah Dokumen Berkas</label>
                        <div class="position-relative border border-2 border-dashed rounded-3 p-4 text-center bg-light">
                            <input type="file" name="file_excel" id="fileExcelInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".xlsx,.xls,.csv" required style="cursor: pointer;">
                            <div class="py-2">
                                <i class="bi bi-cloud-arrow-up text-success fs-1 mb-2 d-block"></i>
                                <span class="fw-semibold text-dark d-block mb-1" id="fileNamePlaceholder" style="font-size: 0.875rem;">Pilih berkas excel atau drop disini</span>
                                <span class="text-muted small" style="font-size: 0.75rem;">Mendukung ekstensi berkas (.xlsx, .xls, .csv)</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <span class="fw-bold text-muted small text-uppercase d-block mb-2" style="font-size: 0.725rem;">
                            <i class="bi bi-info-circle-fill text-info me-1"></i> Aturan Kolom Spreadsheet:
                        </span>
                        <div class="table-responsive rounded-2 border">
                            <table class="table table-sm table-bordered mb-0 font-monospace text-center align-middle bg-white" style="font-size: 0.775rem;">
                                <thead class="table-light fw-bold text-secondary">
                                    <tr>
                                        <th style="width: 25%">Kolom A</th>
                                        <th style="width: 35%">Kolom B</th>
                                        <th style="width: 40%">Kolom C</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="fw-semibold text-dark">
                                        <td>No Urut</td>
                                        <td><code class="text-danger fw-bold">nisn</code> / NIS</td>
                                        <td><code class="text-danger fw-bold">nama</code> Siswa</td>
                                    </tr>
                                    <tr class="text-muted">
                                        <td>1</td>
                                        <td>0045261882</td>
                                        <td>ACHMAD JANUAR P.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <span class="text-muted d-block mt-2" style="font-size: 0.725rem;">*Pastikan baris pertama pada file excel Anda langsung berupa data siswa (Bukan baris judul/header kolom).</span>
                    </div>
                </div>

                <div class="p-3 px-4 bg-light border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border rounded-2 fw-medium px-3" style="font-size: 0.875rem;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success rounded-2 fw-medium px-3 d-inline-flex align-items-center gap-1.5" style="font-size: 0.875rem;">
                        <i class="bi bi-cloud-check"></i> Mulai Sinkronisasi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL CONFIRM HAPUS SEMUA --}}
<div class="modal fade" id="confirmDeleteAllModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
        <div class="modal-content modal-clean-content p-4 text-center">
            <h5 class="fw-bold text-dark mb-2">Kosongkan Seluruh Kelas?</h5>
            <p class="text-muted small mb-4 px-1">Tindakan destruktif ini akan menghapus seluruh entitas data master siswa yang terdaftar di kelas ini secara permanen dari basis data.</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light border w-50 rounded-2 fw-medium" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('siswa.hapus_semua') }}" method="POST" class="w-50">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                    <button type="submit" class="btn btn-danger w-100 rounded-2 fw-medium">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CONFIRM HAPUS SISWA TUNGGAL --}}
<div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
        <div class="modal-content modal-clean-content p-4 text-center">
            <h5 class="fw-bold text-dark mb-2">Hapus Data Siswa?</h5>
            <p class="text-muted small mb-4 px-1">Tindakan ini akan menghapus log data master siswa atas nama <strong id="deleteModalTargetName" class="text-dark"></strong> secara permanen dari sistem.</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light border w-50 rounded-2 fw-medium" data-bs-dismiss="modal">Batal</button>
                <form id="deleteModalForm" method="POST" class="w-50">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 rounded-2 fw-medium">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- CLIENT-SIDE JAVASCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById('siswaSearch');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const tableRows = document.querySelectorAll('.siswa-row');
    const emptyRowSearch = document.getElementById('emptyRowSearch');
    const counterRows = document.getElementById('counterRows');
    const fileInput = document.getElementById('fileExcelInput');
    const fileNamePlaceholder = document.getElementById('fileNamePlaceholder');

    // Binding Modal Hapus Tunggal
    const confirmDeleteModal = document.getElementById('confirmDeleteModal');
    if (confirmDeleteModal) {
        confirmDeleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const siswaId = button.getAttribute('data-siswa-id');
            const siswaName = button.getAttribute('data-siswa-name');
            
            const modalForm = confirmDeleteModal.querySelector('#deleteModalForm');
            const modalTargetName = confirmDeleteModal.querySelector('#deleteModalTargetName');
            
            modalForm.action = `/siswa/hapus/${siswaId}`;
            modalTargetName.textContent = siswaName;
        });
    }

    let currentFilter = 'all';
    let currentSearchTerm = '';

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                fileNamePlaceholder.textContent = e.target.files[0].name;
                fileNamePlaceholder.classList.remove('text-dark');
                fileNamePlaceholder.classList.add('text-success');
            }
        });
    }

    function filterTable() {
        let visibleCount = 0;
        let totalExist = tableRows.length;

        if (totalExist === 0) return;

        tableRows.forEach(row => {
            const rfidStatus = row.getAttribute('data-rfid-status');
            const studentName = row.querySelector('.text-name-target').textContent.toLowerCase();
            const studentInduk = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            
            const matchFilter = (currentFilter === 'all') ||
                                (currentFilter === 'rfid-active' && rfidStatus === 'active') ||
                                (currentFilter === 'rfid-empty' && rfidStatus === 'empty');
                                
            const matchSearch = studentName.includes(currentSearchTerm) || studentInduk.includes(currentSearchTerm);

            if (matchFilter && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (counterRows) {
            counterRows.textContent = visibleCount + " Data";
        }

        if (emptyRowSearch) {
            if (visibleCount === 0) {
                emptyRowSearch.style.display = '';
            } else {
                emptyRowSearch.style.display = 'none';
            }
        }
    }

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            currentSearchTerm = e.target.value.toLowerCase().trim();
            filterTable();
        });
    }

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            currentFilter = this.getAttribute('data-filter');
            filterTable();
        });
    });
});
</script>

@endsection