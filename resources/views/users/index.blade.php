@extends('layouts.app')

@section('title', 'Kelola User')

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
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.725rem;
        font-weight: 600;
    }

    .badge-admin { background: #eff6ff; color: #1d4ed8; }
    .badge-guru { background: #f0fdf4; color: #15803d; }

    /* Modal Clean */
    .modal-clean-content {
        border: 1px solid var(--saas-border);
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
    }
</style>

<div class="container-fluid text-start p-0">

    {{-- HEADER & TOMBOL TAMBAH --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3 border-bottom border-light pb-3">
        <div>
            <h3 class="fw-bold m-0 tracking-tight" style="font-size: 1.5rem;">Kelola User</h3>
            <p class="text-muted m-0 mt-1 small">
                Manajemen akun Admin dan Wali Kelas SDN Tengah 03 Jakarta Timur.
            </p>
        </div>

        <a href="{{ route('users.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-3 fw-medium" style="border-radius: 8px; padding: 0.55rem 1rem;">
            <i class="bi bi-plus-lg small"></i>
            Tambah User
        </a>
    </div>

    {{-- ALERT BERHASIL --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0 !important;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="fw-medium small">{{ session('success') }}</div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ALERT GAGAL --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca !important;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div class="fw-medium small">{{ session('error') }}</div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- SECTION STATISTIK --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total User</div>
                        <div class="stat-value">{{ $users->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #1d4ed8;">Admin</div>
                        <div class="stat-value">{{ $users->where('role', 'admin')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #15803d;">Wali Kelas</div>
                        <div class="stat-value">{{ $users->where('role', 'guru')->count() }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CARD UTAMA --}}
    <div class="saas-card overflow-hidden mb-5">
        
        {{-- Header & Filter Section --}}
        <div class="p-3 px-4 border-bottom bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">Daftar Kontrol Pengguna</h6>
            <span class="badge bg-light text-secondary border fw-normal" style="font-size: 0.775rem;">
                {{ $users->count() }} akun terdaftar
            </span>
        </div>

        {{-- Filter Inputs --}}
        <div class="p-3 bg-white border-bottom">
            <div class="row g-2">
                <div class="col-12 col-md-8">
                    <div class="input-group-saas d-flex align-items-center px-2">
                        <span class="input-group-text p-0 me-2"><i class="bi bi-search small"></i></span>
                        <input type="text" id="searchInput" class="form-control saas-form-control border-0 ps-0 shadow-none" placeholder="Cari nama atau email user...">
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <select id="roleFilter" class="form-select saas-form-select shadow-none">
                        <option value="all">Semua Jenis Role</option>
                        <option value="admin">Admin</option>
                        <option value="guru">Wali Kelas</option>
                    </select>
                </div>
            </div>
        </div>
        {{-- Table Data --}}
        <div class="table-responsive">
            <table class="table-saas">
                <thead>
                    <tr>
                        <th style="width: 5%" class="text-center">No</th>
                        <th style="width: 35%">Nama Lengkap</th>
                        <th style="width: 30%">Alamat Email</th>
                        <th style="width: 18%" class="text-center">Hak Akses</th>
                        <th style="width: 12%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    @forelse($users as $index => $user)
                    <tr class="user-row" data-name="{{ strtolower($user->name) }}" data-email="{{ strtolower($user->email) }}" data-role="{{ $user->role }}">
                        
                        {{-- Nomor --}}
                        <td class="text-center text-muted fw-medium small">
                            {{ $index + 1 }}
                        </td>

                        {{-- Nama User --}}
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-secondary" style="width: 34px; height: 34px; background-color: #f1f5f9; flex-shrink: 0; font-size: 0.9rem;">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                    <span class="text-muted small" style="font-size: 0.75rem;">
                                        {{ $user->role == 'admin' ? 'Administrator Sistem' : 'Wali Kelas' }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="text-secondary small">
                            <div class="d-flex align-items-center gap-1.5">
                                <i class="bi bi-envelope text-muted" style="font-size: 0.8rem;"></i>
                                <span>{{ $user->email }}</span>
                            </div>
                        </td>

                        {{-- Role Badge --}}
                        <td class="text-center">
                            @if($user->role == 'admin')
                                <span class="badge-saas badge-admin">
                                    <i class="bi bi-shield-lock-fill"></i> ADMIN
                                </span>
                            @else
                                <span class="badge-saas badge-guru">
                                    <i class="bi bi-mortarboard-fill"></i> WALI KELAS
                                </span>
                            @endif
                        </td>

                        {{-- Tombol Aksi --}}
                        <td class="text-center">
                            <div class="d-flex justify-content-center align-items-center gap-1.5">
                                {{-- Edit --}}
                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="action-btn-saas edit text-secondary"
                                   title="Ubah Data">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                {{-- Hapus hanya Wali Kelas --}}
                                @if($user->role != 'admin')
                                    <button type="button" 
                                            class="action-btn-saas delete text-secondary"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmDeleteModal"
                                            data-user-id="{{ $user->id }}"
                                            data-user-name="{{ $user->name }}"
                                            title="Hapus Data">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="5" class="text-center text-muted py-5">
                            <div class="py-3">
                                <i class="bi bi-people text-slate-300 fs-1 d-block mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">Belum Ada Pengguna</h6>
                                <p class="text-muted small mb-0 px-3">Sistem tidak mendeteksi data user. Silakan klik tombol <strong>"Tambah User"</strong> untuk meregistrasikan akun baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                    {{-- Empty State untuk Pencarian Kosong --}}
                    <tr id="searchEmptyRow" style="display: none;">
                        <td colspan="5" class="text-center text-muted py-5">
                            <div class="py-3">
                                <i class="bi bi-search text-slate-300 fs-1 d-block mb-2"></i>
                                <h6 class="fw-bold text-dark mb-1">Data Tidak Ditemukan</h6>
                                <p class="text-muted small mb-0">Kata kunci atau filter role yang Anda masukkan tidak cocok dengan data manapun.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if(method_exists($users, 'links') && $users->hasPages())
            <div class="px-4 py-3 bg-white border-top d-flex justify-content-center">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 380px;">
        <div class="modal-content modal-clean-content p-4 text-center">
            <h5 class="fw-bold text-dark mb-2">Hapus Akun Pengguna?</h5>
            <p class="text-muted small mb-4">
                Tindakan ini akan mencabut hak akses masuk 
                <strong id="deleteModalTargetName" class="text-dark"></strong> 
                secara permanen dari sistem.
            </p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light border w-50 rounded-2 fw-medium" data-bs-dismiss="modal">
                    Batal
                </button>
                <form id="deleteModalForm" method="POST" class="w-50">
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

{{-- JAVASCRIPT FILTER & MODAL DINAMIS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const rows = document.querySelectorAll('.user-row');
        const searchEmptyRow = document.getElementById('searchEmptyRow');
        const staticEmptyRow = document.getElementById('emptyRow');

        // Logic Modal Dinamis
        const confirmDeleteModal = document.getElementById('confirmDeleteModal');
        if (confirmDeleteModal) {
            confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');
                
                const modalForm = confirmDeleteModal.querySelector('#deleteModalForm');
                const modalTargetName = confirmDeleteModal.querySelector('#deleteModalTargetName');
                
                modalForm.action = `/users/${userId}`;
                modalTargetName.textContent = userName;
            });
        }

        function filterTable() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const filterValue = roleFilter.value;
            let visibleCount = 0;

            if (staticEmptyRow) return;

            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                const role = row.getAttribute('data-role');

                const matchesSearch = name.includes(searchValue) || email.includes(searchValue);
                const matchesRole = (filterValue === 'all') || (role === filterValue);

                if (matchesSearch && matchesRole) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                searchEmptyRow.style.display = '';
            } else {
                searchEmptyRow.style.display = 'none';
            }
        }

        if (searchInput && roleFilter) {
            searchInput.addEventListener('input', filterTable);
            roleFilter.addEventListener('change', filterTable);
        }
    });
</script>

@endsection