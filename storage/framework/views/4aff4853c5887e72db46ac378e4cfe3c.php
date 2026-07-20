<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDN Tengah 03</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            margin: 0;
            min-height: 100vh;
        }

        /* Sisi Kiri - Branding Gedung Sekolah */
        .brand-section {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.8), rgba(15, 23, 42, 0.95)), 
                        url("<?php echo e(asset('assets/img/bg-gedung.png')); ?>");
            background-size: cover;
            background-position: center;
            min-height: 100vh;
        }

        /* Sisi Kanan - Area Form */
        .form-section {
            min-height: 100vh;
            background-color: #ffffff;
            box-shadow: -10px 0 40px rgba(15, 23, 42, 0.02);
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            margin: auto;
        }

        .form-header h3 {
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        /* Form Input Premium Style */
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
        }

        .search-merge-group {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
            transition: all 0.2s ease;
            background-color: #ffffff;
        }

        .search-merge-group:focus-within {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
        }

        .search-merge-group .form-control,
        .search-merge-group .input-group-text {
            border: none !important;
            background-color: transparent !important;
            font-size: 0.95rem;
            color: #1e293b;
        }

        .search-merge-group .input-group-text {
            color: #64748b; 
            width: 46px;
            justify-content: center;
            font-size: 1.1rem;
            padding: 0;
        }

        /* Khusus untuk tombol mata agar interaktif */
        .search-merge-group .btn-toggle-password {
            border: none !important;
            background: transparent;
            color: #64748b;
            width: 46px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-size: 1.1rem;
        }

        .search-merge-group .btn-toggle-password:focus {
            outline: none;
            box-shadow: none;
        }

        .form-control {
            padding: 12px 16px 12px 4px;
            height: 48px;
        }

        .form-control:focus {
            box-shadow: none;
        }

        /* Kustomisasi Checkbox Ingat Saya */
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .form-check-label {
            font-size: 0.88rem;
            color: #475569;
            user-select: none;
        }

        /* Tombol Modern */
        .btn-login {
            background: linear-gradient(135deg, #0d6efd, #3b82f6);
            color: #ffffff;
            border: none;
            height: 48px;
            font-size: 0.95rem;
            font-weight: 700;
            border-radius: 10px;
            letter-spacing: .3px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;
        }

        .btn-login:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.25) !important;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            line-height: 0; /* Merapikan alignment vertikal ikon */
        }
    </style>
</head>
<body>

    <div class="container-fluid p-0 text-start">
        <div class="row g-0">
            
            <!-- KOLOM KIRI: BACKGROUND & BRANDING (Hanya muncul di Desktop) -->
            <div class="col-lg-7 d-none d-lg-flex flex-column justify-content-between p-5 text-white brand-section">
                <div>
                    <span class="badge bg-white bg-opacity-25 px-3 py-2 rounded-pill fw-semibold text-uppercase tracking-wider small">
                        Portal Presensi
                    </span>
                </div>
                <div>
                    <h1 class="fw-bold display-5 mb-2" style="letter-spacing: -1px;">Sistem Presensi RFID</h1>
                    <p class="fs-5 text-white-50">SDN Tengah 03 Jakarta Timur</p>
                </div>
                <div>
                    <p class="m-0 text-white-50 small">
                        Lingkungan Pendidikan Cerdas & Digital
                    </p>
                </div>
            </div>

            <!-- KOLOM KANAN: FORM LOGIN -->
            <div class="col-lg-5 d-flex flex-column justify-content-between p-4 p-sm-5 form-section">
                
                <!-- Spacer Atas / Area Logo untuk Responsif HP -->
                <div class="d-lg-none text-center mb-4">
                    <h4 class="fw-bold text-primary m-0" style="letter-spacing: -0.5px;">SDN Tengah 03</h4>
                </div>
                
                <!-- Wadah Logo Instansi untuk tampilan desktop (Silakan isi path logo jika ada) -->
                <div class="d-none d-lg-block mb-3">
                    
                </div>

                <!-- Box Formulir Utama -->
                <div class="login-box my-auto py-4">
                    <div class="form-header mb-4">
                        <h3 class="fw-bold mb-2">Masuk ke Sistem</h3>
                        <p class="text-muted mb-0 small">Silakan masuk menggunakan akun administrator untuk mengakses sistem presensi siswa.</p>
                    </div>

                    
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger border-0 p-3 mb-4 rounded-3 d-flex align-items-center small" style="background:#fef2f2; color:#b91c1c;">
                            <i class="bi bi-exclamation-circle-fill me-2 fs-6"></i>
                            <div class="fw-medium"><?php echo e(session('error')); ?></div>
                        </div>
                    <?php endif; ?>

                    <form action="/proses-login" method="POST" autocomplete="off">
                        <?php echo csrf_field(); ?>
                        
                        
                        <div class="mb-3">
                            <label class="form-label">Alamat Email</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control shadow-none" placeholder="Contoh: admin@gmail.com" required autofocus>
                            </div>
                        </div>
                        
                        
                        <div class="mb-3">
                            <label class="form-label">Kata Sandi</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" id="passwordInput" class="form-control shadow-none" placeholder="••••••••" required>
                                <button type="button" class="btn-toggle-password" id="togglePassword">
                                    <i class="bi bi-eye text-muted" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input shadow-none" type="checkbox" name="remember" id="rememberMe">
                                <label class="form-check-label" href="#rememberMe" for="rememberMe">
                                    Ingat Saya
                                </label>
                            </div>
                        </div>

                        
                        <button type="submit" class="btn btn-login w-100 d-flex align-items-center justify-content-center gap-2">
                            <span>Masuk</span>
                            <i class="bi bi-arrow-right-short fs-5"></i>
                        </button>
                    </form>
                </div>

                <!-- Footer Bawah -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        © <?php echo e(date('Y')); ?> SDN Tengah 03 Jakarta Timur
                    </small>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Script Ringan vanilla JS untuk Show/Hide Password -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#passwordInput');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            // Tukar tipe input
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Tukar ikon mata
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/auth/login.blade.php ENDPATH**/ ?>