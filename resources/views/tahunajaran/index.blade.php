@extends('layouts.app')

@section('title','Tahun Ajaran')

@section('content')

{{-- CUSTOM STYLES UNTUK MENINGKATKAN ESTETIKA VISUAL PREMIUM & SELARAS --}}
<style>
    /* Premium Card & Shadow System */
    .card-premium {
        border-radius: 16px !important;
        border: none !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.04) !important;
    }
    .shadow-premium {
        box-shadow: 0 8px 24px rgba(149, 157, 165, 0.06);
    }

    /* Form & Input Polish */
    .form-control, .form-select {
        font-size: 0.92rem !important;
        border-radius: 10px !important;
        border: 1px solid #dee2e6;
        padding: 10px 14px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
    }
    
    /* Search Bar Layout */
    .search-container {
        border-radius: 12px;
        background: #fdfefe;
        border: 1px solid #e9ecef;
        padding: 16px;
    }
    .search-container:focus-within {
        border-color: #a5c3f9;
    }

    /* Table Design Polish */
    .table {
        font-size: 0.9rem;
    }
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #64748b;
        padding: 14px 16px !important;
        background-color: #f8fafc !important;
        border-bottom: 2px solid #edeff1 !important;
    }
    .table td {
        padding: 16px 16px !important;
        border-bottom: 1px solid #f1f3f5 !important;
    }
    .table tbody tr {
        transition: background-color 0.2s ease;
    }
    .table-hover tbody tr:hover {
        background-color: #fcfdfe !important;
    }

    /* Badge Custom */
    .badge-status-aktif { 
        background-color: #e8f5e9; 
        color: #2e7d32; 
        border: 1px solid rgba(46,125,50,0.1); 
        font-weight: 600;
    }
    .badge-status-arsip { 
        background-color: #f5f5f5; 
        color: #616161; 
        border: 1px solid rgba(97,97,97,0.1); 
        font-weight: 600;
    }

    /* Modal Styling */
    .modal-content {
        border-radius: 16px !important;
        border: none !important;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15) !important;
    }
    .modal-header {
        border-bottom: 1px solid #f1f3f5;
        padding: 20px 24px;
    }
    .modal-footer {
        border-top: 1px solid #f1f3f5;
        padding: 16px 24px;
    }
    .modal-body {
        padding: 24px;
    }

    /* PREMIUM MODAL KOMPONEN KONFIRMASI SAAS */
    .saas-alert-icon-container {
        width: 80px;
        height: 80px;
        background-color: #fff9db;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    .saas-alert-icon {
        color: #fcc419;
        font-size: 2.8rem;
        line-height: 1;
    }
    .btn-saas-cancel {
        background-color: #f1f3f5 !important;
        color: #495057 !important;
        border: 1px solid #e9ecef !important;
        font-weight: 600 !important;
        border-radius: 12px !important;
        padding: 12px 24px !important;
        font-size: 0.95rem !important;
        transition: all 0.2s;
    }
    .btn-saas-cancel:hover {
        background-color: #e9ecef !important;
    }
    .btn-saas-confirm {
        background-color: #1d63ed !important;
        color: #ffffff !important;
        border: none !important;
        font-weight: 600 !important;
        border-radius: 12px !important;
        padding: 12px 24px !important;
        font-size: 0.95rem !important;
        box-shadow: 0 4px 12px rgba(29, 99, 237, 0.2);
        transition: all 0.2s;
    }
    .btn-saas-confirm:hover {
        background-color: #1552ca !important;
        box-shadow: 0 4px 15px rgba(29, 99, 237, 0.3);
    }
</style>

<div class="container-fluid mt-4 text-start">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">Tahun Ajaran</h3>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Kelola Tahun Ajaran pembagi data periode yang digunakan oleh sistem presensi RFID.
            </p>
        </div>
        <div class="d-flex gap-2">
            <button
                class="btn btn-primary px-4 rounded-3 shadow-sm fw-semibold d-inline-flex align-items-center gap-2"
                style="font-size: 0.9rem; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);"
                data-bs-toggle="modal"
                data-bs-target="#modalTambah">
                <i class="bi bi-plus-circle-fill"></i> Tambah Tahun
            </button>

            @if($tahunAjaran->where('status',1)->count())
            <a
                href="{{ route('tahunajaran.kenaikan') }}"
                class="btn btn-warning px-4 rounded-3 shadow-sm fw-semibold d-inline-flex align-items-center gap-2"
                style="font-size: 0.9rem; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.15);">
                🚀 Kenaikan Kelas
            </a>
            @endif
        </div>
    </div>

    {{-- Notifikasi Sukses / Error --}}
    @if(session('sukses'))
        <div class="alert alert-success rounded-4 border-0 shadow-sm d-flex align-items-center p-3 mb-4" style="background-color: #dcfce7; color: #15803d;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="fw-medium">{{ session('sukses') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm p-3 mb-4">
            <div class="fw-bold mb-2"><i class="bi bi-x-circle-fill me-2"></i> Terdapat kesalahan pengisian data:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Statistik Counter Atas --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card card-premium bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Tahun Aktif</span>
                        <h3 class="fw-bold text-success mb-0 font-monospace fs-2" style="letter-spacing: -1px;">{{ $tahunAjaran->where('status',1)->count() }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 px-3 rounded-3 text-success">
                        <i class="bi bi-calendar-check fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-premium bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Total Tahun</span>
                        <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -1px;">{{ $tahunAjaran->count() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 text-primary">
                        <i class="bi bi-calendar fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-premium bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Arsip</span>
                        <h3 class="fw-bold text-secondary mb-0 font-monospace fs-2" style="letter-spacing: -1px;">{{ $tahunAjaran->where('status',0)->count() }}</h3>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-2 px-3 rounded-3 text-secondary">
                        <i class="bi bi-archive fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-secondary" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- TABEL DATA --}}
        <div class="col-12">
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden bg-white">
                <div class="card-header bg-white border-0 pt-4 pb-3 px-4">
                    <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Daftar Master Tahun Ajaran</h5>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    
                    {{-- Input Pencarian --}}
                    <div class="search-container mb-3">
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute" style="left:15px; top:50%; transform:translateY(-50%); color:#9ca3af;"></i>
                            <input
                                id="searchTahun"
                                class="form-control ps-5 shadow-none"
                                placeholder="Cari berdasarkan nama tahun ajaran atau ID...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th width="60" class="text-center">No</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Durasi Periode</th>
                                    <th width="130" class="text-center">Status</th>
                                    <th width="200" class="text-center">Aksi Operasional</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyTahun">
                            @forelse($tahunAjaran as $item)
                                <tr>
                                    <td class="text-center text-muted fw-medium">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">
                                            {{ $item->nama }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2 text-dark">
                                            <span class="font-monospace small bg-light border px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</span>
                                            <span class="text-muted small text-uppercase fw-semibold" style="font-size: 0.7rem;">s/d</span>
                                            <span class="font-monospace small bg-light border px-2 py-0.5 rounded">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status)
                                            <span class="badge badge-status-aktif rounded-pill px-3 py-1.5 small">● Aktif</span>
                                        @else
                                            <span class="badge badge-status-arsip rounded-pill px-3 py-1.5 small">● Arsip</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            @if(!$item->status)
                                            <form action="{{ route('tahunajaran.aktifkan',$item->id) }}" method="POST" class="m-0 saas-aktifkan-form" data-tahun="{{ $item->nama }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-link p-0 text-success shadow-none border-0 bg-transparent" title="Jadikan Aktif" style="outline: none; box-shadow: none;">
                                                    <div class="p-2 bg-success bg-opacity-10 rounded-3 text-success d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                        <i class="bi bi-check-circle-fill"></i>
                                                    </div>
                                                </button>
                                            </form>
                                            @endif

                                            {{-- Tombol Edit --}}
                                            <button
                                                class="btn btn-link p-0 text-warning shadow-none"
                                                data-bs-toggle="modal"
                                                data-bs-target="#edit{{ $item->id }}"
                                                title="Ubah Konfigurasi Data">
                                                <div class="p-2 bg-warning bg-opacity-10 rounded-3 text-warning d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </div>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('tahunajaran.destroy',$item->id) }}" method="POST" class="m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="btn btn-link p-0 text-danger shadow-none"
                                                    onclick="return confirm('Hapus Tahun Ajaran ini? Data yang terikat kemungkinan akan terdampak.')"
                                                    title="Hapus Dari Sistem">
                                                    <div class="p-2 bg-danger bg-opacity-10 rounded-3 text-danger d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </div>
                                                </button>
                                            </form>

                                            @if($item->status)
                                            <a href="{{ route('tahunajaran.kenaikan') }}" class="btn btn-link p-0 text-primary shadow-none" title="Kelola Kenaikan Kelas">
                                                <div class="p-2 bg-primary bg-opacity-10 rounded-3 text-primary d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                                    🚀
                                                </div>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL EDIT --}}
                                <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="{{ route('tahunajaran.update', $item->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-pencil-square text-warning me-2"></i>Ubah Tahun Ajaran</h5>
                                                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold text-muted small text-uppercase">Nama Tahun Ajaran</label>
                                                        <input type="text" name="nama" class="form-control shadow-none" value="{{ $item->nama }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold text-muted small text-uppercase">Tanggal Mulai</label>
                                                        <input type="date" name="tanggal_mulai" class="form-control shadow-none" value="{{ $item->tanggal_mulai }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold text-muted small text-uppercase">Tanggal Selesai</label>
                                                        <input type="date" name="tanggal_selesai" class="form-control shadow-none" value="{{ $item->tanggal_selesai }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light border px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <div class="py-5 opacity-75">
                                            <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                                <i class="bi bi-calendar-x text-secondary fs-3"></i>
                                            </div>
                                            <h6 class="fw-bold text-dark">Belum Ada Master Data</h6>
                                            <p class="text-muted mb-0 small">Tekan tombol 'Tambah Tahun' di bagian atas untuk menambahkan periode baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH TAHUN AJARAN --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('tahunajaran.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Tahun Ajaran</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Nama Periode</label>
                        <input type="text" name="nama" class="form-control shadow-none" placeholder="Contoh: 2026/2027" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control shadow-none" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-muted small text-uppercase">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control shadow-none" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border px-4 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-3 fw-semibold">Simpan Master</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL VALIDASI KEPUTUSAN SAAS PREMIUM --}}
<div class="modal fade" id="saasDecisionModal" tabindex="-1" aria-hidden="true" style="z-index: 2000;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <div class="modal-content text-center border-0 p-3" style="border-radius: 28px !important; box-shadow: 0 15px 50px rgba(0,0,0,0.2) !important;">
            <div class="modal-body pt-4 position-relative">
                <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Close" style="top: 15px; right: 15px; opacity: 0.5; box-shadow: none; outline: none;"></button>
                <div class="saas-alert-icon-container">
                    <i class="bi bi-exclamation-triangle-fill saas-alert-icon"></i>
                </div>
                <h4 class="fw-bold text-dark mb-3" id="saasModalTitle" style="font-size: 1.45rem; letter-spacing: -0.3px;">Aktifkan Tahun Ajaran?</h4>
                <p class="text-muted mb-4 px-3" id="saasModalDesc" style="font-size: 0.92rem; line-height: 1.6;">
                    Tindakan ini akan memperbarui status periode akademik utama sistem ke tahun ajaran baru. Pastikan seluruh integrasi pembagi data periodik RFID sudah sesuai.
                </p>
                <div class="d-flex gap-3 justify-content-center px-2 mb-2">
                    <button type="button" class="btn btn-saas-cancel w-50" data-bs-dismiss="modal">Tidak, Batal</button>
                    <button type="button" class="btn btn-saas-confirm w-50" id="saasExecuteBtn">Ya, Proses</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// 1. Live Search Logic
document.getElementById('searchTahun').addEventListener('keyup', function(){
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll('#tbodyTahun tr');

    rows.forEach(function(row){
        if(row.querySelector('td[colspan]') === null) {
            row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
        }
    });
});

// 2. SaaS Interceptor Khusus Mengaktifkan Tahun Ajaran
document.addEventListener('DOMContentLoaded', function () {
    let activeTargetForm = null;
    const saasModal = new bootstrap.Modal(document.getElementById('saasDecisionModal'));
    const saasExecuteBtn = document.getElementById('saasExecuteBtn');

    document.addEventListener('submit', function (event) {
        const form = event.target.closest('.saas-aktifkan-form');
        if (!form) return;

        if (!form.dataset.confirmed) {
            event.preventDefault();
            activeTargetForm = form;

            const namaTahun = form.dataset.tahun || '';
            document.getElementById('saasModalTitle').textContent = 'Aktifkan Tahun Ajaran?';
            document.getElementById('saasModalDesc').innerHTML = `Tindakan ini akan memperbarui status data akademik utama ke periode <strong>${namaTahun}</strong>. Pastikan seluruh verifikasi perangkat RFID dan pembagi data periodik sudah benar.`;

            saasModal.show();
        }
    });

    saasExecuteBtn.addEventListener('click', function () {
        if (activeTargetForm) {
            activeTargetForm.dataset.confirmed = "true";
            saasModal.hide();
            activeTargetForm.submit();
        }
    });
});
</script>

@endsection