@extends('layouts.app')

@section('title', 'Tambah User - SDN Tengah 03')

@section('content')

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        
        /* Design System Konsisten */
        .custom-card {
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        }

        .form-label {
            color: #334155;
            font-size: 0.9rem;
        }

        /* Input & Select Height Customization */
        .form-control-custom, .form-select-custom {
            height: 48px;
            border-radius: 12px !important;
            border: 1px solid #e2e8f0;
            padding-left: 45px; /* Beri ruang untuk ikon di kiri */
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control-custom:focus, .form-select-custom:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        /* Wrapper untuk posisi Ikon Input */
        .input-group-custom {
            position: relative;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.1rem;
            z-index: 4;
        }

        /* Khusus Select Box agar text tidak menimpa ikon */
        .form-select-custom {
            padding-left: 45px;
        }

        /* Show/Hide Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            z-index: 4;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #64748b;
        }

        /* Penyesuaian padding khusus input invalid agar icon bootstrap tidak bentrok */
        .form-control-custom.is-invalid {
            padding-right: 45px;
        }

        /* Batalkan & Simpan Button */
        .btn-custom {
            height: 48px;
            padding: 0 24px;
            border-radius: 12px !important;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-custom-light {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            color: #64748b;
        }

        .btn-custom-light:hover {
            background-color: #f8fafc;
            color: #334155;
            border-color: #cbd5e1;
        }
    </style>

    <!-- 1. Header yang Lebih Informatif -->
    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mt-3 mb-1 text-dark">Tambah User Baru</h2>
            <p class="text-muted mb-0">
                Tambahkan akun administrator atau wali kelas yang dapat mengakses sistem presensi.
            </p>
        </div>
        <div class="badge bg-light text-primary border px-3 py-2 rounded-pill fs-7">
            <i class="bi bi-info-circle-fill me-1"></i> Pastikan email belum pernah digunakan
        </div>
    </div>

    <!-- 9. Alert Error yang Lebih Profesional -->
    @if ($errors->any())
        <div class="alert alert-danger rounded-4 border-0 shadow-sm p-4 mb-4">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-2"></i>
                <h6 class="fw-bold mb-0 text-danger">Terjadi Kesalahan Pengisian Form</h6>
            </div>
            <ul class="mb-0 ps-4 text-danger opacity-90">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 2. Card Form -->
    <div class="card border-0 custom-card">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold mb-1 text-dark">Form Tambah User</h5>
            <p class="text-muted small mb-0">Lengkapi seluruh data berikut untuk membuat akun baru.</p>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <!-- Nama Lengkap -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <i class="bi bi-person input-icon"></i>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control form-control-custom @error('name') is-invalid @enderror"
                            placeholder="Contoh: Budi Santoso, S.Pd.">
                        
                        @error('name')
                            <div class="invalid-feedback ps-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Email <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <i class="bi bi-envelope input-icon"></i>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control form-control-custom @error('email') is-invalid @enderror"
                            placeholder="Contoh: walikelas4a@sdntengah03.sch.id">

                        @error('email')
                            <div class="invalid-feedback ps-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Password <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <i class="bi bi-lock input-icon"></i>
                        <input
                            type="password"
                            id="passwordInput"
                            name="password"
                            class="form-control form-control-custom @error('password') is-invalid @enderror"
                            placeholder="Minimal 8 karakter">
                        
                        <i class="bi bi-eye password-toggle" id="togglePassword"></i>

                        @error('password')
                            <div class="invalid-feedback ps-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Role Hak Akses -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        Role Hak Akses <span class="text-danger">*</span>
                    </label>
                    <div class="input-group-custom">
                        <i class="bi bi-shield-check input-icon"></i>
                        <select
                            name="role"
                            class="form-select form-select-custom @error('role') is-invalid @enderror">

                            <option value="" disabled {{ old('role') == '' ? 'selected' : '' }}>
                                -- Pilih Hak Akses Pengguna --
                            </option>

                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                Administrator (Akses Penuh Sistem)
                            </option>

                            <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>
                                Wali Kelas (Akses Data Presensi)
                            </option>

                        </select>

                        @error('role')
                            <div class="invalid-feedback ps-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- Tahap 7 — Tambah Pilihan Wali Kelas -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Wali Kelas</label>
                    <div class="input-group-custom">
                        <i class="bi bi-door-open input-icon"></i>
                        <select 
                            name="kelas_id" 
                            class="form-select form-select-custom @error('kelas_id') is-invalid @enderror">
                            
                            <option value="" {{ old('kelas_id') == '' ? 'selected' : '' }}>-- Tidak Ada --</option>
                            
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>

                        @error('kelas_id')
                            <div class="invalid-feedback ps-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <!-- NIP Guru/Wali Kelas -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">NIP Wali Kelas <span class="text-muted fw-normal">(opsional)</span></label>
                    <div class="input-group-custom">
                        <i class="bi bi-card-text input-icon"></i>
                        <input
                            type="text"
                            name="nip"
                            value="{{ old('nip') }}"
                            class="form-control form-control-custom @error('nip') is-invalid @enderror"
                            placeholder="Contoh: 198501012010012001">
                        @error('nip')
                            <div class="invalid-feedback ps-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <small class="text-muted d-block mt-1">Akan ditampilkan pada blok tanda tangan Rekap Laporan Presensi.</small>
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="{{ route('users.index') }}"
                       class="btn btn-custom btn-custom-light px-4">
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn btn-custom btn-primary px-4 shadow-sm">
                        <i class="bi bi-check-circle-fill"></i>
                        Simpan User
                    </button>
                </div>

            </form>
        </div>
    </div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const passwordInput = document.getElementById('passwordInput');
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

@endsection