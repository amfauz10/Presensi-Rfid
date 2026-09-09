<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title>Login - SDN Tengah 03</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --saas-bg: #ffffff;
            --input-bg: #edf2fe;
            --input-border: #e2e8f0;
            --primary-blue: #2563eb;
            --primary-hover: #1d4ed8;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--saas-bg);
            color: var(--text-dark);
            margin: 0;
            min-height: 100vh;
        }

        /* Sisi Kiri - Branding Gedung Sekolah */
        .brand-section {
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.85), rgba(15, 23, 42, 0.92)), 
                        url("{{ asset('assets/img/bg-gedung.png') }}");
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            position: relative;
        }

        /* Sisi Kanan - Area Form */
        .form-section {
            min-height: 100vh;
            background-color: #ffffff;
        }

        .login-box {
            width: 100%;
            max-width: 360px;
            margin: auto;
        }

        .form-header h3 {
            color: var(--text-dark);
            letter-spacing: -0.03em;
            font-size: 1.45rem;
            font-weight: 700;
        }

        .form-header p {
            font-size: 0.825rem;
            line-height: 1.5;
            color: #64748b;
        }

        /* Form Input Matching Image Visual */
        .form-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 6px;
        }

        .search-merge-group {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid transparent;
            transition: all 0.15s ease-in-out;
            background-color: var(--input-bg);
        }

        .search-merge-group:focus-within {
            border-color: var(--primary-blue);
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
        }

        .search-merge-group .form-control,
        .search-merge-group .input-group-text {
            border: none !important;
            background-color: transparent !important;
            font-size: 0.875rem;
            color: var(--text-dark);
        }

        .search-merge-group .input-group-text {
            color: #94a3b8; 
            width: 42px;
            justify-content: center;
            font-size: 0.95rem;
            padding: 0;
        }

        /* Tombol Toggle Mata */
        .search-merge-group .btn-toggle-password {
            border: none !important;
            background: transparent;
            color: #94a3b8;
            width: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-size: 0.95rem;
            transition: color 0.15s ease;
        }

        .search-merge-group .btn-toggle-password:hover {
            color: var(--text-dark);
        }

        .search-merge-group .btn-toggle-password:focus {
            outline: none;
            box-shadow: none;
        }

        .form-control {
            padding: 10px 14px 10px 0;
            height: 44px;
            font-weight: 500;
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            box-shadow: none;
        }

        /* Checkbox Ingat Saya */
        .form-check-input {
            cursor: pointer;
            width: 1rem;
            height: 1rem;
            border-color: #cbd5e1;
        }

        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }
        
        .form-check-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            user-select: none;
            cursor: pointer;
            font-weight: 500;
        }

        /* Tombol Primary Blue */
        .btn-login {
            background-color: var(--primary-blue);
            color: #ffffff;
            border: none;
            height: 44px;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-login:hover {
            background-color: var(--primary-hover);
            color: #ffffff;
        }

        .btn-login:active {
            transform: scale(0.99);
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0 text-start">
        <div class="row g-0">
            
            <!-- KOLOM KIRI: BRANDING & BACKGROUND GEDUNG (Desktop) -->
            <div class="col-lg-7 d-none d-lg-flex flex-column justify-content-between p-5 text-white brand-section">
                <div>
                    <span class="badge bg-white bg-opacity-25 px-3 py-2 rounded-pill fw-semibold text-uppercase tracking-wider small" style="font-size: 0.725rem; letter-spacing: 0.05em;">
                        Portal Presensi
                    </span>
                </div>
                <div>
                    <h1 class="fw-bold display-5 mb-2" style="letter-spacing: -0.03em;">Sistem Presensi RFID</h1>
                    <!-- Ditingkatkan ukurannya dari fs-5 ke fs-3 serta ditebalkan -->
                    <p class="fs-3 fw-semibold text-white mb-0" style="letter-spacing: -0.01em;">SDN Tengah 03 Jakarta Timur</p>
                </div>
                <div>
                    <p class="m-0 text-white-50 small" style="font-size: 0.8rem;">
                        Lingkungan Pendidikan Cerdas & Digital
                    </p>
                </div>
            </div>

            <!-- KOLOM KANAN: FORM LOGIN -->
            <div class="col-lg-5 d-flex flex-column justify-content-between p-4 p-sm-5 form-section">
                
                <!-- Responsif Header HP -->
                <div class="d-lg-none text-center mb-4">
                    <!-- Ditingkatkan ukurannya dari h4 ke h3 -->
                    <h3 class="fw-bold text-primary m-0" style="letter-spacing: -0.02em;">SDN Tengah 03</h3>
                </div>
                
                <div class="d-none d-lg-block mb-3"></div>

                <!-- Box Formulir Utama -->
                <div class="login-box my-auto py-4">
                    <div class="form-header mb-4">
                        <h3 class="fw-bold mb-2">Masuk ke Sistem</h3>
                        <p class="text-muted mb-0">Silakan masuk menggunakan akun administrator untuk mengakses sistem presensi siswa.</p>
                    </div>
                    {{-- Alert Error dinamis Laravel --}}
                    @if(session('error'))
                        <div class="alert alert-danger border-0 p-3 mb-4 rounded-3 d-flex align-items-center small" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca !important;">
                            <i class="bi bi-exclamation-circle-fill me-2 fs-6"></i>
                            <div class="fw-medium">{{ session('error') }}</div>
                        </div>
                    @endif

                    <form action="/proses-login" method="POST" autocomplete="off">
                        @csrf
                        
                        {{-- INPUT EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <div class="input-group search-merge-group d-flex align-items-center">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control shadow-none" placeholder="@gmail.com" required autofocus>
                            </div>
                        </div>
                        
                        {{-- INPUT PASSWORD + TOGGLE EYE --}}
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi</label>
                            <div class="input-group search-merge-group d-flex align-items-center">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="passwordInput" class="form-control shadow-none" placeholder="••••••••" required>
                                <button type="button" class="btn-toggle-password" id="togglePassword">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- REMEMBER ME --}}
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check d-flex align-items-center gap-2 m-0">
                                <input class="form-check-input shadow-none m-0" type="checkbox" name="remember" id="rememberMe">
                                <label class="form-check-label m-0" for="rememberMe">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>

                        {{-- SUBMIT BUTTON --}}
                        <button type="submit" class="btn btn-login w-100 d-flex align-items-center justify-content-center gap-2">
                            <span>Masuk</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </button>
                    </form>
                </div>

                <!-- Footer Bawah -->
                <div class="text-center mt-4">
                    <small class="text-muted" style="font-size: 0.775rem;">
                        © {{ date('Y') }} SDN Tengah 03 Jakarta Timur
                    </small>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Vanilla JS untuk Show/Hide Password -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');
        const eyeIcon = document.querySelector('#eyeIcon');

        if (togglePassword && passwordInput && eyeIcon) {
            togglePassword.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                eyeIcon.classList.toggle('bi-eye');
                eyeIcon.classList.toggle('bi-eye-slash');
            });
        }
    </script>
</body>
</html>