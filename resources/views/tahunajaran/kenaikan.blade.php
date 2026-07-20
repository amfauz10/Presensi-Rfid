@extends('layouts.app')

@section('title','Proses Kenaikan Kelas')

@section('content')

{{-- CUSTOM STYLES UNTUK MENINGKATKAN ESTETIKA VISUAL PREMIUM & SELARAS --}}
<style>
    /* Premium Card & Shadow System */
    .card-premium {
        border-radius: 16px !important;
        border: none !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
    }
    .shadow-premium {
        box-shadow: 0 8px 24 rgba(149, 157, 165, 0.06);
    }

    /* Form & Input Polish */
    .form-control {
        font-size: 0.92rem !important;
        border-radius: 10px !important;
        border: 1px solid #dee2e6;
        padding: 10px 14px;
    }
    .form-control:focus {
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

    /* Card Header & Body Polish untuk Kelas */
    .class-card {
        border-radius: 16px !important;
        border: 1px solid #e9ecef !important;
        overflow: hidden;
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .class-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04) !important;
    }
    .class-card-header {
        background-color: #f8fafc !important;
        border-bottom: 1px solid #edeff1 !important;
        padding: 14px 20px !important;
    }
    .student-scroll-container {
        max-height: 320px; 
        overflow-y: auto;
        padding: 16px 20px !important;
    }

    /* Custom Checkbox Student List */
    .student-item {
        padding: 10px 14px;
        border-radius: 8px;
        background-color: #f8fafc;
        border: 1px solid #e9ecef;
        transition: all 0.2s ease;
    }
    .student-item:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }
    .form-check-input {
        cursor: pointer;
    }
    /* MENGUBAH WARNA AKSI CHECKBOX AKTIF MENJADI BIRU */
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    /* MENGUBAH WARNA TEKS SISWA TERPILIH MENJADI BIRU */
    .form-check-input:checked + .student-name {
        color: #0d6efd !important;
        font-weight: 600;
    }

    /* Sticky Bottom Bar */
    .sticky-action-bar {
        border-radius: 16px !important;
        border: 1px solid #e9ecef !important;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(8px);
        box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.04) !important;
    }

    /* Premium SaaS Modal Confirmation Stylings */
    .modal-saas {
        border-radius: 20px !important;
        border: none !important;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1) !important;
    }
    .modal-saas-header {
        border-bottom: none !important;
        padding: 24px 24px 8px 24px !important;
    }
    .modal-saas-body {
        padding: 8px 24px 24px 24px !important;
    }
    .modal-saas-footer {
        border-top: none !important;
        padding: 0 24px 28px 24px !important;
        background: transparent !important;
    }
    .btn-saas-secondary {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 10px !important;
        font-size: 0.9rem;
    }
    .btn-saas-secondary:hover {
        background-color: #e2e8f0 !important;
    }
    .btn-saas-primary {
        border-radius: 10px !important;
        font-size: 0.9rem;
    }
</style>

<div class="container-fluid mt-4 text-start">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px;">🚀 Proses Kenaikan Kelas</h3>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Tahun Ajaran Aktif Berjalan: <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1.5 rounded-pill fw-bold font-monospace ms-1">{{ $tahunAktif->nama ?? '-' }}</span>
            </p>
        </div>
        <div>
            {{-- TOMBOL KEMBALI KE HALAMAN TAHUN AJARAN DENGAN STYLE SELARAS --}}
            <a href="{{ route('tahunajaran.index') }}" 
               class="btn btn-light border px-4 py-2 rounded-3 shadow-sm fw-semibold d-inline-flex align-items-center gap-2"
               style="font-size: 0.9rem; background-color: #ffffff; color: #495057;">
                <i class="bi bi-arrow-left-short fs-5 lh-1"></i> Kembali ke Tahun Ajaran
            </a>
        </div>
    </div>

    {{-- Info Card Utama --}}
    <div class="card card-premium shadow-premium bg-white mb-4">
        <div class="card-body p-4">
            <div class="alert alert-warning rounded-4 border-0 d-flex align-items-start p-3 mb-0" style="background-color: #fffbeb; border: 1px solid #fde68a !important; color: #b45309;">
                <i class="bi bi-info-circle-fill me-3 fs-5 mt-0.5"></i>
                <div>
                    <strong class="d-block mb-1" style="color: #92400e;">Petunjuk Operasional Kenaikan:</strong>
                    <ul class="mb-0 ps-3 small text-muted-emphasis">
                        <li>Secara default, seluruh data siswa aktif telah ditandai centang untuk <strong>otomatis naik kelas</strong>.</li>
                        <li>Silakan <strong>hilangkan tanda centang</strong> pada nama siswa tertentu jika siswa tersebut dinyatakan tinggal kelas.</li>
                        <li>Sistem mendeteksi tingkat akhir secara otomatis; siswa yang berada di <strong>Kelas 6</strong> akan langsung dimigrasikan menjadi <strong>Alumni</strong>.</li>
                    </ul>
                </div>
            </div>

            {{-- Input Pencarian Global Selaras --}}
            <div class="search-container mt-4">
                <label class="fw-semibold text-dark small mb-2"><i class="bi bi-search me-1 text-muted"></i> Pindai Pencarian Siswa (Lintas Kelas)</label>
                <div class="position-relative">
                    <i class="bi bi-person position-absolute" style="left:15px; top:50%; transform:translateY(-50%); color:#9ca3af;"></i>
                    <input type="text" id="search-global" class="form-control ps-5 shadow-none" placeholder="Ketik nama lengkap siswa untuk memfilter visualisasi kelas...">
                </div>
            </div>
        </div>
    </div>

    <form id="formKenaikanKelas" action="{{ route('tahunajaran.proses') }}" method="POST">
        @csrf

        {{-- GRID KELAS --}}
        <div class="row g-4">
            @foreach($kelas as $item)
                @if($item->siswa->count() > 0)
                <div class="col-lg-6 class-card-wrapper">
                    <div class="card class-card shadow-none h-100">
                        <!-- Header Card Kelas -->
                        <div class="card-header class-card-header d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                                <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                                Kelas {{ $item->nama_kelas }}
                            </span>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-1.5 rounded-pill fw-semibold font-monospace" style="font-size: 0.75rem;">
                                {{ $item->siswa->count() }} Siswa
                            </span>
                        </div>

                        <!-- Daftar Siswa -->
                        <div class="card-body student-scroll-container">
                            <div class="d-flex flex-column gap-2">
                                @foreach($item->siswa as $siswa)
                                <div class="form-check m-0 d-flex align-items-center student-item">
                                    <input
                                        class="form-check-input border-secondary-subtle m-0 shadow-none me-3"
                                        type="checkbox"
                                        name="siswa[]"
                                        value="{{ $siswa->id }}"
                                        checked
                                        id="siswa{{ $siswa->id }}"
                                        style="width: 1.15rem; height: 1.15rem;">
                                    
                                    <label
                                        class="form-check-label text-dark student-name text-uppercase w-100 py-0.5"
                                        for="siswa{{ $siswa->id }}"
                                        style="font-size: 0.82rem; font-weight: 500; cursor: pointer; letter-spacing: 0.2px;">
                                        {{ $siswa->nama_siswa }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>

        {{-- FLOATING ACTIONS STICKY BOTTOM BAR --}}
        <div class="card border-0 sticky-bottom sticky-action-bar mt-5 mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="text-start">
                        <h5 class="mb-1 fw-bold text-dark" style="font-size: 1.05rem;">Konfirmasi Kelayakan Kenaikan Kelas?</h5>
                        <p class="text-muted mb-0 small">
                            <i class="bi bi-shield-exclamation text-warning me-1"></i> Siswa yang tanda centangnya dilepas akan tetap menetap pada tingkat kelas lamanya di tahun ajaran baru.
                        </p>
                    </div>
                    <!-- MENGUBAH WARNA BUTTON UTAMA MENJADI BIRU (btn-primary) -->
                    <button
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmModal"
                        class="btn btn-primary px-4 py-2.5 rounded-3 fw-semibold shadow-sm d-inline-flex align-items-center gap-2"
                        style="font-size: 0.92rem; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2) !important;">
                        <i class="bi bi-arrow-up-circle-fill fs-5 lh-1"></i>
                        PROSES KENAIKAN KELAS
                    </button>
                </div>
            </div>
        </div>

        {{-- SAAS PREMIUM VALIDATION MODAL --}}
        <div class="modal fade" id="confirmModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                <div class="modal-content modal-saas">
                    <div class="modal-header modal-saas-header justify-content-center position-relative">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                        </div>
                        <button type="button" class="btn-close position-absolute" style="top: 20px; right: 20px;" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-saas-body text-center">
                        <h5 class="fw-bold text-dark mb-2">Eksekusi Kenaikan Kelas?</h5>
                        <p class="text-muted mb-0 small px-2">Tindakan ini akan memperbarui status data akademik siswa ke tahun ajaran baru. Pastikan seluruh verifikasi manual centang siswa sudah benar.</p>
                    </div>
                    <div class="modal-footer modal-saas-footer d-flex gap-2">
                        <button type="button" class="btn btn-saas-secondary flex-grow-1 py-2 fw-semibold" data-bs-dismiss="modal">Tidak, Batal</button>
                        <!-- MENGUBAH WARNA BUTTON MODAL CONFIRM MENJADI BIRU (btn-primary) -->
                        <button type="submit" class="btn btn-primary btn-saas-primary flex-grow-1 py-2 fw-semibold shadow-none">Ya, Proses</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- SCRIPT PENDUKUNG PENCARIAN GLOBAL (DIPERTAHANKAN STRUKTUR PENTINGNYA) --}}
<script>
    document.getElementById('search-global').addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const wrappers = document.querySelectorAll('.class-card-wrapper');

        wrappers.forEach(wrapper => {
            const items = wrapper.querySelectorAll('.student-item');
            let hasVisibleStudent = false;

            items.forEach(item => {
                const name = item.querySelector('.student-name').textContent.toLowerCase();
                if (name.includes(filter)) {
                    item.style.setProperty('display', 'flex', 'important'); // Diubah ke flex agar checkbox align-items berfungsi sempurna
                    hasVisibleStudent = true;
                } else {
                    item.style.setProperty('display', 'none', 'important');
                }
            });

            if (hasVisibleStudent || filter === "") {
                wrapper.style.setProperty('display', 'block', 'important');
            } else {
                wrapper.style.setProperty('display', 'none', 'important');
            }
        });
    });
</script>

@endsection