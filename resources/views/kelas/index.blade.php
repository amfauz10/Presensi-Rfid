@extends('layouts.app')

@section('title', 'Data Siswa Per Kelas - SDN Tengah 03')

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

        /* Modern Class Card SaaS Style */
        .compact-class-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid var(--saas-border);
            border-radius: 12px; 
            padding: 16px 18px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
            height: 100%;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
            text-decoration: none !important;
        }

        .compact-class-link:hover {
            border-color: #cbd5e1;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.05) !important;
        }

        .class-avatar {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .compact-class-link:hover .class-avatar {
            transform: scale(1.04);
        }

        .class-name {
            font-size: 0.925rem;
            font-weight: 700;
            color: var(--saas-text-main);
            margin: 0;
            line-height: 1.3;
        }

        .student-count-text {
            font-size: 0.775rem;
            color: var(--saas-text-muted);
            font-weight: 500;
        }

        .action-text {
            font-size: 0.725rem;
            color: #94a3b8;
            font-weight: 600;
            display: block;
            margin-top: 2px;
            transition: color 0.2s ease;
        }

        .compact-class-link:hover .action-text {
            color: #2563eb;
        }

        .chevron-arrow {
            font-size: 1rem;
            color: #cbd5e1;
            transition: all 0.2s ease;
        }

        .compact-class-link:hover .chevron-arrow {
            color: #2563eb;
            transform: translateX(3px);
        }

        /* Custom Form Controls & Search */
        .input-group-saas {
            border: 1px solid var(--saas-border);
            border-radius: 8px;
            background-color: #ffffff;
            transition: all 0.15s ease-in-out;
            max-width: 320px;
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
            font-size: 0.875rem;
        }

        /* Modern Card Container */
        .saas-card {
            background: var(--saas-card-bg);
            border: 1px solid var(--saas-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
        }
    </style>

    {{-- ALERT BERHASIL --}}
    @if(session('sukses'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0 !important;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="fw-medium small">{{ session('sukses') }}</div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- HEADER HALAMAN --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-3 border-bottom border-light text-start">
        <div>
            <h3 class="fw-bold m-0 tracking-tight" style="font-size: 1.5rem;">Data Siswa</h3>
            <p class="text-muted m-0 mt-1 small" style="max-width: 600px;">
                Kelola data siswa berdasarkan kelas. Pilih salah satu ruang kelas di bawah untuk melakukan penambahan, pembaruan data, serta manajemen kartu RFID siswa.
            </p>
        </div>
        
        {{-- STATISTIK KECIL --}}
        @if($daftar_kelas->isNotEmpty())
            <div class="d-flex gap-3 bg-white p-2 px-3 rounded-3 border border-light shadow-sm">
                <div class="text-center border-end pe-3 text-start">
                    <span class="text-muted d-block" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Total Kelas</span>
                    <strong class="text-dark fs-5 fw-bold font-monospace">{{ $daftar_kelas->count() }}</strong>
                </div>
                <div class="text-center text-start">
                    <span class="text-muted d-block" style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Siswa Aktif</span>
                    <strong class="text-primary fs-5 fw-bold font-monospace">{{ $daftar_kelas->sum('siswa_count') }}</strong>
                </div>
            </div>
        @endif
    </div>

    {{-- BARIS UTILITAS: SEARCH KELAS --}}
    @if($daftar_kelas->isNotEmpty())
        <div class="d-flex justify-content-start mb-4">
            <div class="input-group-saas d-flex align-items-center px-2 py-0.5">
                <span class="input-group-text p-0 me-2"><i class="bi bi-search small"></i></span>
                <input type="text" id="searchKelas" class="form-control bg-transparent border-0 shadow-none" placeholder="Ketik nama ruang kelas...">
            </div>
        </div>
    @endif

    {{-- GRID UTAMA KELAS --}}
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3 text-start" id="kelasGrid">

        @forelse($daftar_kelas as $index => $kls)
            @php
                $iconStyle = match($index % 4) {
                    0 => 'background-color: #eff6ff; color: #2563eb;', // Blue
                    1 => 'background-color: #f0fdf4; color: #16a34a;', // Green
                    2 => 'background-color: #fefce8; color: #d97706;', // Amber
                    default => 'background-color: #f8fafc; color: #475569; border: 1px solid #e2e8f0;' // Slate
                };
            @endphp

            <div class="col class-card-item" data-nama="{{ strtolower($kls->nama_kelas) }}">
                <a href="{{ route('kelas.show', $kls->id) }}" class="compact-class-link">
                    
                    <div class="d-flex align-items-center gap-3 text-truncate">
                        <div class="class-avatar" style="{!! $iconStyle !!}">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        
                        <div class="text-truncate">
                            <h6 class="class-name text-truncate">Kelas {{ $kls->nama_kelas }}</h6>
                            <span class="student-count-text">
                                {{ $kls->siswa_count }} Siswa Terdaftar
                            </span>
                            <small class="action-text">Buka Kelas <i class="bi bi-arrow-right small" style="font-size: 0.7rem;"></i></small>
                        </div>
                    </div>

                    <div class="chevron-arrow">
                        <i class="bi bi-chevron-right"></i>
                    </div>

                </a>
            </div>

        @empty
            {{-- EMPTY STATE UTAMA --}}
            <div class="col-12 w-100" id="emptyStateContainer">
                <div class="saas-card">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="bi bi-folder-x text-slate-300 fs-1 d-block mb-2"></i>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Data Ruang Kelas</h6>
                        <p class="text-muted small m-0">Silakan konfigurasikan struktur data master kelas terlebih dahulu pada modul administrasi utama.</p>
                    </div>
                </div>
            </div>
        @endforelse

        {{-- EMPTY STATE HASIL PENCARIAN KOSONG --}}
        <div class="col-12 w-100 d-none" id="searchEmptyState">
            <div class="saas-card">
                <div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-search text-slate-300 fs-1 d-block mb-2"></i>
                    <h6 class="fw-bold text-dark mb-1">Ruang Kelas Tidak Ditemukan</h6>
                    <p class="text-muted small m-0">Tidak ada data kelas yang cocok dengan kata kunci pencarian Anda.</p>
                </div>
            </div>
        </div>

    </div>

{{-- JAVASCRIPT LIVE SEARCH CLIENT-SIDE --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.getElementById('searchKelas');
        
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const filterValue = this.value.toLowerCase().trim();
                const cards = document.querySelectorAll('.class-card-item');
                const searchEmptyState = document.getElementById('searchEmptyState');
                let hasVisibleCard = false;

                cards.forEach(function(card) {
                    const namaKelas = card.getAttribute('data-nama');
                    if (namaKelas.includes(filterValue)) {
                        card.classList.remove('d-none');
                        hasVisibleCard = true;
                    } else {
                        card.classList.add('d-none');
                    }
                });

                if (!hasVisibleCard && filterValue !== '') {
                    searchEmptyState.classList.remove('d-none');
                } else {
                    searchEmptyState.classList.add('d-none');
                }
            });
        }
    });
</script>

@endsection