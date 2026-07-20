@extends('layouts.app')

@section('title', 'Log Notifikasi Presensi')

@section('content')
<style>
    /* Kartu selaras dengan dashboard: shadow lembut + hover lift halus */
    .card-custom {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(148, 163, 184, 0.08) !important;
    }
    .shadow-premium {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
    }

    /* Form Controls */
    .form-select, .form-control {
        font-size: 0.92rem !important;
        border-radius: 8px !important;
        border: 1px solid #dee2e6;
        padding: 9px 12px;
    }
    .form-select:focus, .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.08) !important;
    }

    /* Tabel */
    .table {
        font-size: 0.88rem;
    }
    .table th {
        font-weight: 600;
        font-size: 0.72rem;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        color: #6c757d;
        padding: 12px 16px !important;
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #edeff1 !important;
    }
    .table td {
        padding: 12px 16px !important;
        border-bottom: 1px solid #f1f3f5 !important;
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa !important;
    }

    /* Status badge: teks berwarna dengan titik kecil, tanpa background/border ramai */
    .status-dot {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .status-dot::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
        flex-shrink: 0;
    }
    .badge-hadir { color: #2e7d32; }
    .badge-terlambat { color: #b8860b; }
    .badge-sakit { color: #1565c0; }
    .badge-izin { color: #616161; }
    .badge-alpa { color: #c62828; }

    /* Auto-Cleanup Banner: garis tipis, tanpa ikon besar */
    .banner-cleanup {
        background-color: #fff;
        border-left: 3px solid #f0b429 !important;
        color: #6c757d;
    }

    /* Modal sederhana */
    .modal-clean-content {
        border: none !important;
        border-radius: 14px !important;
        box-shadow: 0 12px 28px rgba(0,0,0,0.1) !important;
    }
</style>

{{-- SINKRONISASI ALERT DENGAN STYLE BARU --}}
@if(session('sukses'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm border-0 text-start d-flex align-items-center p-3" role="alert" style="background-color: #dcfce7; color: #15803d;">
        <i class="bi bi-check-circle-fill me-2 fs-5"></i> 
        <div class="fw-medium">{{ session('sukses') }}</div>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- HEADER HALAMAN --}}
<div class="d-flex justify-content-between align-items-center mb-4 text-start">
    <div>
        <h3 class="fw-bold m-0" style="letter-spacing: -0.5px;">Log Notifikasi Presensi</h3>
        <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">
            Pemantauan pengiriman WhatsApp gateway untuk sistem kehadiran siswa secara real-time.
        </p>
    </div>
</div>

{{-- BANNER AUTO-CLEANUP --}}
<div class="banner-cleanup rounded-3 mb-4 p-3 small">
    <strong class="text-dark">Auto-cleanup:</strong>
    log yang berumur lebih dari 24 jam otomatis dibersihkan sistem untuk efisiensi penyimpanan.
</div>

{{-- 1. STATISTIK LOG --}}
<div class="row g-3 mb-4 text-start">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-0 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small d-block mb-1 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.4px;">Total Aktivitas</span>
                    <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -0.5px;">{{ $totalLog }}</h3>
                </div>
                <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 text-primary">
                    <i class="bi bi-clipboard-data fs-4 lh-1"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 3px;"></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-0 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-success small d-block mb-1 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.4px;">Notifikasi Sukses</span>
                    <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -0.5px;">{{ $totalBerhasil }}</h3>
                </div>
                <div class="bg-success bg-opacity-10 p-2 px-3 rounded-3 text-success">
                    <i class="bi bi-check-circle-fill fs-4 lh-1"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px;"></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-0 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-danger small d-block mb-1 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.4px;">Notifikasi Gagal</span>
                    <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -0.5px;">{{ $totalGagal }}</h3>
                </div>
                <div class="bg-danger bg-opacity-10 p-2 px-3 rounded-3 text-danger">
                    <i class="bi bi-x-circle-fill fs-4 lh-1"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 bg-danger" style="height: 3px;"></div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
            <div class="card-body p-0 d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small d-block mb-1 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.4px;">Log Hari Ini</span>
                    <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -0.5px;">{{ $totalHariIni }}</h3>
                </div>
                <div class="bg-secondary bg-opacity-10 p-2 px-3 rounded-3 text-secondary">
                    <i class="bi bi-calendar-check-fill fs-4 lh-1"></i>
                </div>
            </div>
            <div class="position-absolute bottom-0 start-0 w-100 bg-secondary opacity-50" style="height: 3px;"></div>
        </div>
    </div>
</div>

{{-- 3. UTILITY FILTER DATA --}}
<div class="card-custom mb-4 text-start bg-white p-0">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('log.notifikasi') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold text-dark small">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search small text-muted"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control shadow-none border-start-0 ps-0" placeholder="Nama siswa, kode RFID, atau nomor WA orang tua...">
                    </div>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold text-dark small">Status Kirim</label>
                    <select name="status_kirim" class="form-select shadow-none">
                        <option value="" {{ request('status_kirim') == '' ? 'selected' : '' }}>Semua</option>
                        <option value="Berhasil" {{ request('status_kirim') == 'Berhasil' ? 'selected' : '' }}>Berhasil</option>
                        <option value="Gagal" {{ request('status_kirim') == 'Gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold text-dark small">Status Absen</label>
                    <select name="status_presensi" class="form-select shadow-none">
                        <option value="" {{ request('status_presensi') == '' ? 'selected' : '' }}>Semua</option>
                        <option value="Hadir" {{ request('status_presensi') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="Terlambat" {{ request('status_presensi') == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
                        <option value="Sakit" {{ request('status_presensi') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="Izin" {{ request('status_presensi') == 'Izin' ? 'selected' : '' }}>Izin</option>
                        <option value="Alpa" {{ request('status_presensi') == 'Alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <label class="form-label fw-semibold text-dark small">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control shadow-none">
                </div>

                <div class="col-6 col-md-1">
                    <button type="submit" class="btn btn-primary w-100" title="Terapkan filter">
                        <i class="bi bi-filter"></i>
                    </button>
                </div>
            </div>

            @if(request('search') || request('status_kirim') || request('status_presensi') || request('tanggal'))
                <div class="mt-3">
                    <a href="{{ route('log.notifikasi') }}" class="btn btn-light border btn-sm">Reset Filter</a>
                    <span class="text-muted small ms-2">Menampilkan {{ $logs->total() }} hasil sesuai filter.</span>
                </div>
            @endif
        </form>
    </div>
</div>

{{-- 2. RIWAYAT TABEL --}}
<div class="card-custom overflow-hidden text-start bg-white p-0">
    <div class="card-header bg-white border-bottom py-3 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.05rem;">Riwayat Transaksi Notifikasi WhatsApp</h5>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small">{{ $logs->total() }} record</span>
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDeleteAll">
                    Hapus Semua Log
                </button>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle m-0">
            <thead>
                <tr>
                    <th style="width: 5%" class="text-center">No</th>
                    <th style="width: 14%">Waktu Sistem</th>
                    <th style="width: 12%">RFID Code</th>
                    <th style="width: 18%">Nama Siswa</th>
                    <th style="width: 13%">No. WA Orang Tua</th>
                    <th style="width: 10%">Status Absen</th>
                    <th style="width: 10%">Status Kirim</th>
                    <th style="width: 13%">Keterangan API</th>
                    <th style="width: 5%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($logs as $index => $log)
            <tr>
                <td class="text-center text-muted fw-medium">{{ $logs->firstItem() + $index }}</td>
                
                {{-- Waktu Sistem --}}
                <td class="font-monospace small text-muted">{{ $log->created_at->format('d-m-Y H:i') }} WIB</td>
                
                {{-- RFID Code --}}
                <td class="font-monospace small text-muted">
                    @if(str_contains($log->rfid_code, 'MANUAL_'))
                        Manual
                    @else
                        {{ $log->rfid_code }}
                    @endif
                </td>
                
                {{-- Nama Siswa --}}
                <td class="fw-bold text-dark text-uppercase" style="font-size: 0.82rem; letter-spacing: 0.2px;">{{ $log->nama_siswa }}</td>
                
                {{-- No HP --}}
                <td class="font-monospace text-secondary small">{{ $log->no_hp_orang_tua }}</td>
                
                {{-- Status Presensi Badge Warna Dinamis --}}
                <td>
                    @php
                        $badgeClass = match($log->status_presensi) {
                            'Hadir' => 'badge-hadir',
                            'Terlambat' => 'badge-terlambat',
                            'Sakit' => 'badge-sakit',
                            'Izin' => 'badge-izin',
                            'Alpa' => 'badge-alpa',
                            default => 'bg-light text-dark'
                        };
                    @endphp
                    <span class="status-dot {{ $badgeClass }}">{{ $log->status_presensi }}</span>
                </td>

                {{-- Status Notifikasi Gateway --}}
                <td>
                    @if($log->status_notifikasi == 'Berhasil')
                        <span class="small fw-semibold text-success">Terkirim</span>
                    @else
                        <span class="small fw-semibold text-danger">Gagal</span>
                    @endif
                </td>

                {{-- Keterangan API --}}
                <td class="text-wrap text-muted font-monospace" style="max-width: 160px; font-size: 0.75rem; line-height: 1.4;">
                    {{ Str::limit($log->keterangan, 60, '...') }}
                </td>

                {{-- Tombol Aksi Hapus Log Manual --}}
                <td class="text-center">
                    <button type="button" class="btn btn-link p-0 text-danger" title="Hapus Permanen" data-bs-toggle="modal" data-bs-target="#modalDelete{{ $log->id }}">
                        <i class="bi bi-trash"></i>
                    </button>

                    <!-- MODAL DELETE INDIVIDUAL -->
                    <div class="modal fade" id="modalDelete{{ $log->id }}" mercantile-modal tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
                            <div class="modal-content modal-clean-content p-4 text-center">
                                <h5 class="fw-bold text-dark mb-2">Hapus Baris Riwayat?</h5>
                                <p class="text-muted small mb-4">Tindakan ini permanen. Data log untuk siswa <strong class="text-dark">{{ $log->nama_siswa }}</strong> akan dihapus dari sistem.</p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-light border w-50 " data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('log.notifikasi.destroy', $log->id) }}" method="POST" class="w-50">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100 ">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-5">
                    <div class="py-4">
                        @if(request('search') || request('status_kirim') || request('status_presensi') || request('tanggal'))
                            <h6 class="fw-bold text-dark">Tidak Ada Hasil yang Cocok</h6>
                            <p class="text-muted mb-0 small" style="max-width: 320px; margin: 0 auto;">Tidak ditemukan log yang sesuai dengan kata kunci/filter yang dipilih. Coba ubah kata kunci atau reset filter.</p>
                        @else
                            <h6 class="fw-bold text-dark">Belum Ada Riwayat Notifikasi</h6>
                            <p class="text-muted mb-0 small" style="max-width: 320px; margin: 0 auto;">Seluruh log pengiriman pesan otomatis ke nomor WhatsApp orang tua siswa akan terekam di sini.</p>
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
        <div class="card-footer bg-white p-3 border-top d-flex justify-content-center">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<!-- MODAL DELETE ALL LOGS (SaaS Style) -->
<div class="modal fade" id="modalDeleteAll" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
        <div class="modal-content modal-clean-content p-4 text-center">
            <h5 class="fw-bold text-dark mb-2">Kosongkan Semua Log?</h5>
            <p class="text-muted small mb-4">Apakah Anda yakin ingin menghapus <strong>seluruh data riwayat</strong> transaksi notifikasi WhatsApp? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light border w-50 " data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('log.notifikasi.truncate') }}" method="POST" class="w-50">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 ">Ya, Bersihkan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection