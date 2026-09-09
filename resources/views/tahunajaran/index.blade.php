@extends('layouts.app')

@section('title', 'Tahun Ajaran')

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

    /* Stat Cards System */
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

    /* Input & Form Controls */
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

    /* Table SaaS Design */
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

    /* SaaS Action Buttons */
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

    .action-btn-saas.activate:hover {
        background-color: #f0fdf4;
        border-color: #bbf7d0;
        color: #16a34a !important;
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

    .action-btn-saas.promote:hover {
        background-color: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb !important;
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
    .badge-status-arsip { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }

    /* Modal Clean Style */
    .modal-clean-content {
        border: 1px solid var(--saas-border);
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    }
</style>

<div class="container-fluid text-start p-0">

    {{-- HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom text-start">
        <div>
            <h3 class="fw-bold text-dark m-0 tracking-tight" style="font-size: 1.5rem;">Tahun Ajaran</h3>
            <p class="text-muted small mb-0">Kelola Tahun Ajaran pembagi data periode yang digunakan oleh sistem presensi RFID.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-primary d-inline-flex align-items-center gap-1.5 px-3 py-2 fw-medium" style="border-radius: 8px; font-size: 0.85rem;" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg small"></i>Tambah Tahun
            </button>

            @if($tahunAjaran->where('status',1)->count())
            <a href="{{ route('tahunajaran.kenaikan') }}" class="btn btn-warning d-inline-flex align-items-center gap-1.5 px-3 py-2 fw-medium text-dark" style="border-radius: 8px; font-size: 0.85rem;" title="Akses cepat proses kenaikan kelas dari halaman manapun">
                <span>🚀</span> Kenaikan Kelas
            </a>
            @endif
        </div>
    </div>

    {{-- NOTIFIKASI SUKSES --}}
    @if(session('sukses'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0 !important;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="fw-medium small">{{ session('sukses') }}</div>
            <button class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- NOTIFIKASI ERROR --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0 text-start p-3 shadow-sm" role="alert" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca !important;">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div class="small fw-medium">{{ session('error') }}</div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- NOTIFIKASI ERRORS VALIDASI --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0 text-start p-3 shadow-sm" role="alert" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca !important;">
            <div class="d-flex align-items-center mb-1">
                <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                <strong class="small fw-bold">Terdapat kesalahan pengisian data:</strong>
            </div>
            <ul class="mb-0 small ps-4 fw-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- KARTU STATISTIK --}}
    <div class="row g-3 mb-4 text-start">
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #166534;">Tahun Aktif</div>
                        <div class="stat-value" style="color: #16a34a;">{{ $tahunAjaran->where('status',1)->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Tahun</div>
                        <div class="stat-value">{{ $tahunAjaran->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-calendar-range-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Arsip</div>
                        <div class="stat-value" style="color: #64748b;">{{ $tahunAjaran->where('status',0)->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f8fafc; color: #64748b;">
                        <i class="bi bi-archive-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN DATA CARD & SEARCH --}}
    <div class="saas-card p-3 mb-4 text-start">
        <div class="input-group-saas d-flex align-items-center px-2">
            <span class="input-group-text p-0 me-2"><i class="bi bi-search small"></i></span>
            <input type="text" id="searchTahun" class="form-control saas-form-control border-0 ps-0 shadow-none" placeholder="Cari berdasarkan nama tahun ajaran atau ID...">
        </div>
    </div>

    {{-- TABEL UTAMA MASTER TAHUN AJARAN --}}
    <div class="saas-card overflow-hidden mb-5 text-start">
        <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Daftar Master Tahun Ajaran</h6>
            <span class="badge bg-light text-secondary border fw-normal font-monospace" style="font-size: 0.775rem;">{{ $tahunAjaran->count() }} Periode</span>
        </div>

        <div class="table-responsive">
            <table class="table-saas">
                <thead>
                    <tr>
                        <th style="width: 60px;" class="text-center">No</th>
                        <th>Tahun Ajaran</th>
                        <th>Durasi Periode</th>
                        <th style="width: 140px;" class="text-center">Status</th>
                        <th style="width: 180px;" class="text-center">Aksi Operasional</th>
                    </tr>
                </thead>
                <tbody id="tbodyTahun">
                @forelse($tahunAjaran as $item)
                    <tr>
                        <td class="text-center text-muted fw-medium font-monospace small">{{ $loop->iteration }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->nama }}</div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2 text-dark">
                                <span class="font-monospace small bg-light border px-2 py-1 rounded text-secondary" style="font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}
                                </span>
                                <span class="text-muted small text-uppercase font-monospace" style="font-size: 0.7rem;">s/d</span>
                                <span class="font-monospace small bg-light border px-2 py-1 rounded text-secondary" style="font-size: 0.8rem;">
                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            @if($item->status)
                                <span class="badge-saas badge-status-aktif">● Aktif</span>
                            @else
                                <span class="badge-saas badge-status-arsip">● Arsip</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1.5">
                                @if(!$item->status)
                                <form action="{{ route('tahunajaran.aktifkan',$item->id) }}" method="POST" class="m-0 saas-aktifkan-form" data-tahun="{{ $item->nama }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="action-btn-saas activate text-secondary" title="Jadikan Aktif">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Tombol Edit --}}
                                <button type="button" class="action-btn-saas edit text-secondary" data-bs-toggle="modal" data-bs-target="#edit{{ $item->id }}" title="Ubah Konfigurasi Data">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('tahunajaran.destroy',$item->id) }}" method="POST" class="m-0 saas-delete-form" data-tahun="{{ $item->nama }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn-saas delete text-secondary" title="Hapus Dari Sistem">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>

                                @if($item->status)
                                <a href="{{ route('tahunajaran.kenaikan') }}" class="action-btn-saas promote text-secondary" title="Kelola kenaikan untuk tahun ajaran aktif ini">
                                    <span style="font-size: 0.85rem; line-height: 1;">🚀</span>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>

                    {{-- MODAL EDIT --}}
                    <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content modal-clean-content overflow-hidden">
                                <form action="{{ route('tahunajaran.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between">
                                        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Ubah Tahun Ajaran</h6>
                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="p-4 text-start">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Nama Tahun Ajaran</label>
                                            <input type="text" name="nama" class="form-control saas-form-control" value="{{ $item->nama }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Tanggal Mulai</label>
                                            <input type="text" name="tanggal_mulai" class="form-control saas-form-control datepicker-ta bg-white" value="{{ $item->tanggal_mulai }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Tanggal Selesai</label>
                                            <input type="text" name="tanggal_selesai" class="form-control saas-form-control datepicker-ta bg-white" value="{{ $item->tanggal_selesai }}" required>
                                        </div>
                                    </div>
                                    <div class="p-3 px-4 bg-light border-top d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-light border rounded-2 fw-medium px-3" style="font-size: 0.875rem;" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary rounded-2 fw-medium px-3" style="font-size: 0.875rem;">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <div class="py-3">
                                <i class="bi bi-calendar-x text-slate-300 fs-1 d-block mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Master Data</h6>
                                <p class="text-muted small mb-0">Tekan tombol 'Tambah Tahun' di bagian atas untuk menambahkan periode baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH TAHUN AJARAN --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-clean-content overflow-hidden">
            <form action="{{ route('tahunajaran.store') }}" method="POST">
                @csrf
                <div class="p-3 px-4 border-bottom bg-white d-flex align-items-center justify-content-between">
                    <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Tambah Tahun Ajaran</h6>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="p-4 text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Nama Periode</label>
                        <input type="text" name="nama" class="form-control saas-form-control" placeholder="Contoh: 2026/2027" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Tanggal Mulai</label>
                        <input type="text" name="tanggal_mulai" class="form-control saas-form-control datepicker-ta bg-white" placeholder="Pilih Tanggal Mulai" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase mb-1" style="font-size: 0.725rem;">Tanggal Selesai</label>
                        <input type="text" name="tanggal_selesai" class="form-control saas-form-control datepicker-ta bg-white" placeholder="Pilih Tanggal Selesai" required>
                    </div>
                </div>
                <div class="p-3 px-4 bg-light border-top d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border rounded-2 fw-medium px-3" style="font-size: 0.875rem;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-2 fw-medium px-3" style="font-size: 0.875rem;">Simpan Master</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL VALIDASI KEPUTUSAN SAAS PREMIUM --}}
<div class="modal fade" id="saasDecisionModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true" style="z-index: 2000;">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
        <div class="modal-content modal-clean-content p-4 text-center">
            <h5 class="fw-bold text-dark mb-2" id="saasModalTitle">Aktifkan Tahun Ajaran?</h5>
            <p class="text-muted small mb-4 px-1" id="saasModalDesc">
                Tindakan ini akan memperbarui status periode akademik utama sistem ke tahun ajaran baru. Pastikan seluruh integrasi pembagi data periodik RFID sudah sesuai.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light border w-50 rounded-2 fw-medium" data-bs-dismiss="modal">Tidak, Batal</button>
                <button type="button" class="btn btn-primary w-50 rounded-2 fw-medium" id="saasExecuteBtn">Ya, Proses</button>
            </div>
        </div>
    </div>
</div>

<script>
// 1. Live Search Logic
if (document.getElementById('searchTahun')) {
    document.getElementById('searchTahun').addEventListener('keyup', function(){
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#tbodyTahun tr');

        rows.forEach(function(row){
            if(row.querySelector('td[colspan]') === null) {
                row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
            }
        });
    });
}

// 2. SaaS Interceptor Khusus Mengaktifkan Tahun Ajaran
document.addEventListener('DOMContentLoaded', function () {
    if (typeof flatpickr !== 'undefined') {
        flatpickr(".datepicker-ta", {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d"
        });
    }

    let activeTargetForm = null;
    const saasModalElem = document.getElementById('saasDecisionModal');
    const saasModal = saasModalElem ? new bootstrap.Modal(saasModalElem) : null;
    const saasExecuteBtn = document.getElementById('saasExecuteBtn');

    document.addEventListener('submit', function (event) {
        // PERBAIKAN: Sebelumnya tombol Hapus Tahun Ajaran memakai
        // confirm() bawaan browser (kotak dialog polos), berbeda dari
        // halaman Data Siswa/Kelas yang sudah memakai modal custom.
        // Sekarang form hapus juga memakai modal yang sama dengan
        // form aktifkan ini, cuma judul/teks/warna tombolnya beda.
        const formAktifkan = event.target.closest('.saas-aktifkan-form');
        const formHapus = event.target.closest('.saas-delete-form');
        const form = formAktifkan || formHapus;
        if (!form) return;

        if (!form.dataset.confirmed) {
            event.preventDefault();
            activeTargetForm = form;

            const namaTahun = form.dataset.tahun || '';

            if (formHapus) {
                document.getElementById('saasModalTitle').textContent = 'Hapus Tahun Ajaran?';
                document.getElementById('saasModalDesc').innerHTML = `Tindakan ini akan menghapus periode <strong>${namaTahun}</strong> secara permanen. Data yang terikat kemungkinan akan terdampak.`;
                saasExecuteBtn.classList.remove('btn-primary');
                saasExecuteBtn.classList.add('btn-danger');
                saasExecuteBtn.textContent = 'Ya, Hapus';
            } else {
                document.getElementById('saasModalTitle').textContent = 'Aktifkan Tahun Ajaran?';
                document.getElementById('saasModalDesc').innerHTML = `Tindakan ini akan memperbarui status data akademik utama ke periode <strong>${namaTahun}</strong>. Pastikan seluruh verifikasi perangkat RFID dan pembagi data periodik sudah benar.`;
                saasExecuteBtn.classList.remove('btn-danger');
                saasExecuteBtn.classList.add('btn-primary');
                saasExecuteBtn.textContent = 'Ya, Proses';
            }

            if (saasModal) saasModal.show();
        }
    });

    if (saasExecuteBtn) {
        saasExecuteBtn.addEventListener('click', function () {
            if (activeTargetForm) {
                activeTargetForm.dataset.confirmed = "true";
                if (saasModal) saasModal.hide();
                activeTargetForm.submit();
            }
        });
    }
});
</script>

@endsection