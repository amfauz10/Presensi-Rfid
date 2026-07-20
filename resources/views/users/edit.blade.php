@extends('layouts.app')

@section('title', 'Edit Pengguna - SDN Tengah 03')

@section('content')

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .shadow-premium {
            box-shadow: 0 8px 24px rgba(149, 157, 165, 0.05);
        }
        .form-control-custom, .form-select-custom {
            border-radius: 12px;
            padding: 0.6rem 1rem;
            border: 1px solid #dee2e6;
            transition: all 0.2s ease-in-out;
        }
        .form-control-custom:focus, .form-select-custom:focus {
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
            border-color: #0d6efd;
        }
        .btn-custom {
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .btn-custom:hover {
            transform: translateY(-1px);
        }
        .input-group-text-custom {
            border-radius: 0 12px 12px 0;
            background-color: transparent;
            border-left: none;
            cursor: pointer;
        }
        .password-field {
            border-radius: 12px 0 0 12px;
            border-right: none;
        }
        .divider-custom {
            border-top: 1px solid #eef2f7;
            opacity: 1;
        }
    </style>

    <!-- HEADER HALAMAN KONSISTEN -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-3 border-bottom border-light text-start">
        <div>
            <h3 class="fw-bold m-0" style="letter-spacing: -0.5px; color: #1e293b;">Edit Akun Pengguna</h3>
            <p class="text-muted m-0 mt-1 small">
                Pastikan data yang diperbarui sudah benar sebelum disimpan ke dalam sistem presensi.
            </p>
        </div>
    </div>

    <!-- 10. ALERT ERROR (Polished) -->
    @if ($errors->any())
    <div class="alert alert-danger rounded-4 border-0 shadow-sm p-4 mb-4" style="background-color: #fef2f2; color: #b91c1c;">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
            <strong class="fs-5">Gagal Menyimpan Perubahan</strong>
        </div>
        <p class="small mb-2 ms-4" style="opacity: 0.85;">Silakan periksa kembali beberapa data berikut:</p>
        <ul class="mb-0 ms-4 custom-error-list">
            @foreach($errors->all() as $error)
                <li class="small">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- MAIN FORM CARD -->
    <div class="card border-0 rounded-4 shadow-premium bg-white">
        <!-- 2. CARD HEADER (Polished) -->
        <div class="card-header bg-white border-0 px-4 pt-4 pb-2">
            <h5 class="fw-bold mb-1" style="color: #1e293b;">Informasi Pengguna</h5>
            <p class="text-muted small mb-0">
                Silakan ubah data yang diperlukan. <span class="text-primary fw-medium">Password lama akan tetap digunakan apabila kolom password dikosongkan.</span>
            </p>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- 9. LAYOUT DUA KOLOM -->
                <div class="row g-4">

                    <!-- Field: Nama Lengkap -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            placeholder="Contoh: Budi Santoso, S.Pd."
                            class="form-control form-control-custom @error('name') is-invalid @enderror">
                        
                        @error('name')
                        <div class="invalid-feedback mt-2">
                            <i class="bi bi-x-circle me-1"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Field: Alamat Email -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            Alamat Email <span class="text-danger">*</span>
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            placeholder="contoh@email.com"
                            class="form-control form-control-custom @error('email') is-invalid @enderror">
                        
                        @error('email')
                        <div class="invalid-feedback mt-2">
                            <i class="bi bi-x-circle me-1"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Field: Password Baru dengan Fitur Show/Hide -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            Password Baru
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="password"
                                id="passwordInput"
                                placeholder="Isi hanya jika ingin mengganti password"
                                class="form-control form-control-custom password-field @error('password') is-invalid @enderror">
                            <span class="input-group-text input-group-text-custom border-1" id="togglePassword">
                                <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                            </span>
                        </div>
                        <div class="form-text text-muted mt-2" style="font-size: 0.775rem;">
                            <i class="bi bi-info-circle me-1"></i> Biarkan kosong jika tidak ingin mengubah password lama.
                        </div>
                        
                        @error('password')
                        <div class="invalid-feedback d-block mt-2">
                            <i class="bi bi-x-circle me-1"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Field: Hak Akses (Role) -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            Hak Akses <span class="text-danger">*</span>
                        </label>
                        <select
                            name="role"
                            class="form-select form-select-custom @error('role') is-invalid @enderror">
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                 Administrator
                            </option>
                            <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>
                                 Guru / Staf
                            </option>
                        </select>
                        
                        @error('role')
                        <div class="invalid-feedback mt-2">
                            <i class="bi bi-x-circle me-1"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- Tahap 8 — Field Pilihan Wali Kelas -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            Wali Kelas
                        </label>
                        <select 
                            name="kelas_id" 
                            class="form-select form-select-custom @error('kelas_id') is-invalid @enderror">
                            
                            <option value="">-- Tidak Ada --</option>
                            
                            @foreach($kelas as $k)
                                <option 
                                    value="{{ $k->id }}" 
                                    {{ old('kelas_id', $user->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>

                        @error('kelas_id')
                        <div class="invalid-feedback mt-2">
                            <i class="bi bi-x-circle me-1"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <!-- NIP Guru/Wali Kelas -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">
                            NIP Guru <span class="text-muted fw-normal">(opsional)</span>
                        </label>
                        <input
                            type="text"
                            name="nip"
                            value="{{ old('nip', $user->nip) }}"
                            class="form-control form-control-custom @error('nip') is-invalid @enderror"
                            placeholder="Contoh: 198501012010012001">
                        @error('nip')
                        <div class="invalid-feedback mt-2">
                            <i class="bi bi-x-circle me-1"></i> {{ $message }}
                        </div>
                        @enderror
                        <small class="text-muted d-block mt-1">Ditampilkan pada blok tanda tangan Rekap Laporan Presensi.</small>
                    </div>

                    <!-- 8. GARIS PEMBATAS MODERN -->
                    <div class="col-12">
                        <hr class="divider-custom my-3">
                    </div>

                    <!-- 7. TOMBOL AKSI -->
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}"
                               class="btn btn-light border rounded-3 px-4 fw-semibold text-secondary shadow-none">
                                <i class="bi bi-arrow-left me-1"></i> Batal
                            </a>
                            <button
                                type="submit"
                                class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm" style="box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">
                                <i class="bi bi-check-circle-fill me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

<!-- 5. JAVASCRIPT FITUR SHOW/HIDE PASSWORD -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('passwordInput');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('bi-eye');
            eyeIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('bi-eye-slash');
            eyeIcon.classList.add('bi-eye');
        }
    });
</script>

@endsection