@extends('layouts.app')

@section('title', 'Log Notifikasi Presensi')

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

    /* Custom Form Controls */
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

    /* Modal Clean */
    .modal-clean-content {
        border: 1px solid var(--saas-border);
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    }
</style>

{{-- ALERT SUCCESS --}}
@if(session('sukses'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0 !important;">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
        <div class="fw-medium small">{{ session('sukses') }}</div>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- HEADER HALAMAN --}}
<div class="d-flex justify-content-between align-items-center mb-4 text-start">
    <div>
        <h3 class="fw-bold m-0 tracking-tight" style="color: var(--saas-text-main); font-size: 1.5rem;">Log Notifikasi Presensi</h3>
        <p class="text-muted m-0 mt-1 small">
            Pemantauan pengiriman WhatsApp gateway untuk sistem kehadiran siswa secara real-time.
        </p>
    </div>
</div>

{{-- 1. STATISTIK LOG --}}
<div class="row g-3 mb-4 text-start">
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Aktivitas</div>
                    <div class="stat-value">{{ $totalLog }}</div>
                </div>
                <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="bi bi-clipboard-data"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label" style="color: #166534;">Notifikasi Sukses</div>
                    <div class="stat-value">{{ $totalBerhasil }}</div>
                </div>
                <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label" style="color: #991b1b;">Notifikasi Gagal</div>
                    <div class="stat-value">{{ $totalGagal }}</div>
                </div>
                <div class="stat-icon" style="background: #fef2f2; color: #dc2626;">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- 3. RIWAYAT TABEL --}}
<div class="saas-card overflow-hidden text-start">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2" style="background: #ffffff;">
        <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Riwayat Transaksi Notifikasi WhatsApp</h6>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.775rem;">{{ $logs->total() }} records</span>
            <button type="button" class="btn btn-outline-danger btn-sm rounded-2 fw-medium" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#modalDeleteAll">
                Hapus Semua Log
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table-saas">
            <thead>
                <tr>
                    <th style="width: 5%" class="text-center">No</th>
                    <th style="width: 18%">Waktu Sistem</th>
                    <th style="width: 15%">RFID Code</th>
                    <th style="width: 25%">Nama Siswa</th>
                    <th style="width: 17%">No. WA Orang Tua</th>
                    <th style="width: 12%">Status Kirim</th>
                    <th style="width: 8%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($logs as $index => $log)
            <tr>
                <td class="text-center text-muted fw-medium small">{{ $logs->firstItem() + $index }}</td>
                
                {{-- Waktu Sistem --}}
                <td class="font-monospace small text-muted">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                
                {{-- RFID Code --}}
                <td class="font-monospace small text-secondary">
                    @if(str_contains($log->rfid_code, 'MANUAL_'))
                        <span class="text-muted italic">Manual</span>
                    @else
                        {{ $log->rfid_code }}
                    @endif
                </td>
                
                {{-- Nama Siswa --}}
                <td class="fw-semibold text-dark">{{ $log->nama_siswa }}</td>
                
                {{-- No HP --}}
                <td class="font-monospace text-secondary small">{{ $log->no_hp_orang_tua }}</td>

                {{-- Status Notifikasi Gateway --}}
                <td>
                    @if($log->status_notifikasi == 'Berhasil')
                        <span class="fw-semibold text-success small d-flex align-items-center gap-1">
                            <i class="bi bi-check2"></i> Terkirim
                        </span>
                    @else
                        <span class="fw-semibold text-danger small d-flex align-items-center gap-1">
                            <i class="bi bi-x-lg"></i> Gagal
                        </span>
                    @endif
                </td>

                {{-- Tombol Aksi Hapus Log Manual --}}
                <td class="text-center">
                    <button type="button"
                            class="btn btn-sm btn-link text-danger p-0 border-0 btn-delete-log"
                            title="Hapus Permanen"
                            data-bs-toggle="modal"
                            data-bs-target="#modalDeleteLog"
                            data-delete-url="{{ route('log.notifikasi.destroy', $log->id) }}"
                            data-nama="{{ $log->nama_siswa }}">
                        <i class="bi bi-trash3 fs-6"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-5">
                    <div class="py-3">
                        <i class="bi bi-inbox text-slate-300 fs-1 d-block mb-2"></i>
                        @if(request('search') || request('status_kirim') || request('tanggal'))
                            <h6 class="fw-bold text-dark mb-1">Tidak Ada Hasil yang Cocok</h6>
                            <p class="text-muted mb-0 small" style="max-width: 360px; margin: 0 auto;">Coba ubah filter pencarian atau tanggal untuk menemukan data riwayat.</p>
                        @else
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Riwayat Notifikasi</h6>
                            <p class="text-muted mb-0 small" style="max-width: 360px; margin: 0 auto;">Seluruh log pengiriman pesan otomatis ke nomor WhatsApp orang tua siswa akan terekam di sini.</p>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- LINK PAGINATION --}}
    @if($logs->hasPages())
        <div class="px-4 py-3 bg-white border-top d-flex justify-content-center">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- MODAL DELETE INDIVIDUAL -->
<div class="modal fade" id="modalDeleteLog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
        <div class="modal-content modal-clean-content p-4 text-center">
            <h5 class="fw-bold text-dark mb-2">Hapus Baris Riwayat?</h5>
            <p class="text-muted small mb-4">
                Tindakan ini permanen. Data log untuk siswa 
                <strong class="text-dark" id="deleteLogNama"></strong> 
                akan dihapus dari sistem.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light border w-50 rounded-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <form id="deleteLogForm" method="POST" class="w-50">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 rounded-2 fw-medium">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DELETE ALL LOGS -->
<div class="modal fade" id="modalDeleteAll" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
        <div class="modal-content modal-clean-content p-4 text-center">
            <h5 class="fw-bold text-dark mb-2">Kosongkan Semua Log?</h5>
            <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus <strong>seluruh data riwayat</strong> transaksi notifikasi WhatsApp? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light border w-50 rounded-2 fw-medium" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('log.notifikasi.truncate') }}" method="POST" class="w-50">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 rounded-2 fw-medium">Ya, Bersihkan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    flatpickr("#filter_tanggal", {
        altInput: true,
        altFormat: "d-m-Y",
        dateFormat: "Y-m-d"
    });

    const modalDeleteLog = document.getElementById('modalDeleteLog');
    if (modalDeleteLog) {
        modalDeleteLog.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const deleteUrl = button.getAttribute('data-delete-url');
            const namaSiswa = button.getAttribute('data-nama');

            document.getElementById('deleteLogForm').action = deleteUrl;
            document.getElementById('deleteLogNama').textContent = namaSiswa;
        });
    }
});
</script>
@endpush
@endsection