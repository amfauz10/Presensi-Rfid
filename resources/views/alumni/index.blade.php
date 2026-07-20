@extends('layouts.app')

@section('title', 'Data Alumni')

@section('content')

<!-- Style khusus agar link nama terlihat natural tapi jelas bisa diklik -->
<style>
    .alumni-link {
        text-decoration: none;
        color: #1e293b; 
        transition: all 0.2s ease-in-out;
        font-weight: 600;
    }
    .alumni-link:hover {
        color: #0d6efd !important; 
        text-decoration: underline !important;
    }

    /* Premium Shadow & Form Controls System */
    .shadow-premium {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
    }
    .search-merge-group {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    .search-merge-group:focus-within {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
    }
    .search-merge-group .form-control,
    .search-merge-group .form-select,
    .search-merge-group .input-group-text {
        border: none !important;
        background-color: #f8f9fa !important;
    }

    /* Table Structural Layout Premium */
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
        padding: 14px 16px !important;
        border-bottom: 1px solid #f1f3f5 !important;
        font-size: 0.9rem;
    }
</style>

<div class="container-fluid py-4 text-start">

    <!-- Header Halaman & Statistik Sederhana -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3 border-bottom border-light pb-3">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;"> Data Alumni</h3>
            <p class="text-muted small mb-0">Daftar siswa yang telah menyelesaikan pendidikan di SDN Tengah 03 Jakarta Timur.</p>
        </div>
        
        <!-- Quick Stats Widget -->
        <div class="d-flex gap-3">
            <div class="bg-white p-2 px-3 rounded-4 border shadow-premium d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.3px;">Total Alumni</small>
                    <span class="fw-bold text-dark font-monospace" style="font-size: 1.05rem;">{{ $alumni->total() }} Data</span>
                </div>
            </div>
            <div class="bg-white p-2 px-3 rounded-4 border shadow-premium d-flex align-items-center gap-3 d-none d-sm-flex">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success">
                    <i class="bi bi-calendar-check fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.3px;">Tahun Aktif</small>
                    <span class="fw-bold text-dark font-monospace" style="font-size: 1.05rem;">{{ $tahunAktif->nama ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card Content -->
    <div class="card shadow-premium border-0 rounded-4 overflow-hidden bg-white">
        
        <!-- Card Header - Filter Pencarian & Angkatan Terpadu -->
        <div class="card-header bg-white py-3 px-4 border-bottom">
            <form action="{{ url()->current() }}" method="GET" id="filter-form">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <h5 class="mb-0 fw-bold text-dark" style="font-size: 1rem;">Daftar Kelulusan Alumni</h5>
                    </div>
                    
                    <!-- Filter Tahun Lulus (Angkatan) -->
                    <div class="col-sm-6 col-md-4">
                        <div class="input-group search-merge-group">
                            <span class="input-group-text bg-light text-muted px-3">
                                <i class="bi bi-filter-square small"></i>
                            </span>
                            <select name="tahun_lulus" id="filter-tahun" class="form-select bg-light shadow-none" style="font-size: 0.9rem; padding: 10px 12px;">
                                <option value="">Semua Angkatan / Tahun Lulus</option>
                                @foreach($daftarTahun as $tahun)
                                    <option value="{{ $tahun->id }}" {{ request('tahun_lulus') == $tahun->id ? 'selected' : '' }}>
                                        Lulus {{ $tahun->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Kolom Cari Nama / NISN -->
                    <div class="col-sm-6 col-md-4">
                        <div class="input-group search-merge-group position-relative">
                            <span class="input-group-text bg-light text-muted px-3">
                                <i class="bi bi-search small"></i>
                            </span>
                            <input type="text" name="search" id="search-alumni" class="form-control bg-light shadow-none" style="font-size: 0.9rem; padding: 10px 35px 10px 0;" placeholder="Cari NISN atau Nama..." value="{{ request('search') }}">
                            @if(request('search') || request('tahun_lulus'))
                                <a href="{{ url()->current() }}" class="position-absolute top-50 end-0 translate-middle-y pe-3 text-muted text-decoration-none" style="z-index: 5;" title="Bersihkan Filter">
                                    <i class="bi bi-x-circle-fill small opacity-75"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Card Body & Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead>
                        <tr>
                            <th width="70" class="text-center">No</th>
                            <th width="180">NISN</th>
                            <th>Nama Alumni</th>
                            <th width="200">Kelas Terakhir</th>
                            <th width="160">Tahun Lulus</th>
                            <th width="150" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alumni as $item)
                        <tr>
                            <td class="text-center text-muted fw-medium font-monospace">{{ ($alumni->currentPage() - 1) * $alumni->perPage() + $loop->iteration }}</td>
                            <td class="font-monospace text-secondary" style="font-size: 0.85rem;">{{ $item->nisn }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-secondary opacity-75 small">👨‍🎓</span> 
                                    <a href="{{ route('siswa.edit', $item->id) }}" class="alumni-link text-uppercase tracking-wide" style="font-size: 0.84rem;">
                                        {{ $item->nama_siswa }}
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-15 px-2.5 py-1.5 rounded-2" style="font-size: 0.75rem; fw-medium">
                                    {{ $item->kelas->nama_kelas ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1.5 rounded-2" style="font-size: 0.75rem;">
                                    {{ $item->tahunAjaranLulus->nama ?? 'Belum tercatat' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill fw-bold border border-success border-opacity-25" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    LULUS
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 bg-light bg-opacity-25">
                                <div class="py-5 opacity-75">
                                    @if(request('search') || request('tahun_lulus'))
                                        <div class="p-3 bg-white border rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                            <i class="bi bi-search text-secondary fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Data Alumni Tidak Ditemukan</h6>
                                        <p class="text-muted small mx-auto mb-0" style="max-width: 450px;">
                                            Tidak ada hasil yang cocok dengan filter atau kata kunci pencarian Anda saat ini.
                                        </p>
                                    @else
                                        <div class="p-3 bg-white border rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                            <i class="bi bi-mortarboard text-secondary fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Belum Ada Data Alumni</h6>
                                        <p class="text-muted small mx-auto mb-0" style="max-width: 400px;">
                                            Data alumni akan otomatis muncul di sini setelah proses kelulusan siswa kelas 6 dilakukan pada sistem.
                                        </p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination Footer -->
        @if(method_exists($alumni, 'links') && $alumni->hasPages())
        <div class="card-footer bg-white py-3 px-4 border-top d-flex align-items-center justify-content-between">
            <small class="text-muted fw-medium">Menampilkan {{ $alumni->firstItem() }} sampai {{ $alumni->lastItem() }} dari {{ $alumni->total() }} alumni</small>
            <div class="pagination-sm mb-0 shadow-none">
                {{ $alumni->appends(request()->all())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif

    </div>
</div>

<!-- Script Filter & Otomatisasi Submit -->
<script>
    // 1. Submit otomatis saat pilihan tahun kelulusan diganti
    document.getElementById('filter-tahun').addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });

    // 2. Debounce submit saat mengetik nama/NISN agar tidak patah-patah
    let searchTimer;
    document.getElementById('search-alumni').addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            document.getElementById('filter-form').submit();
        }, 500);
    });
</script>
@endsection