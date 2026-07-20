

<?php $__env->startSection('title', 'Tambah User - SDN Tengah 03'); ?>

<?php $__env->startSection('content'); ?>

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
                Tambahkan akun administrator atau guru yang dapat mengakses sistem presensi.
            </p>
        </div>
        <div class="badge bg-light text-primary border px-3 py-2 rounded-pill fs-7">
            <i class="bi bi-info-circle-fill me-1"></i> Pastikan email belum pernah digunakan
        </div>
    </div>

    <!-- 9. Alert Error yang Lebih Profesional -->
    <?php if($errors->any()): ?>
        <div class="alert alert-danger rounded-4 border-0 shadow-sm p-4 mb-4">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-2"></i>
                <h6 class="fw-bold mb-0 text-danger">Terjadi Kesalahan Pengisian Form</h6>
            </div>
            <ul class="mb-0 ps-4 text-danger opacity-90">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- 2. Card Form -->
    <div class="card border-0 custom-card">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold mb-1 text-dark">Form Tambah User</h5>
            <p class="text-muted small mb-0">Lengkapi seluruh data berikut untuk membuat akun baru.</p>
        </div>

        <div class="card-body p-4">
            <form action="<?php echo e(route('users.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

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
                            value="<?php echo e(old('name')); ?>"
                            class="form-control form-control-custom <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Contoh: Budi Santoso, S.Pd.">
                        
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback ps-1">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            value="<?php echo e(old('email')); ?>"
                            class="form-control form-control-custom <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Contoh: guru4a@sdntengah03.sch.id">

                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback ps-1">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            class="form-control form-control-custom <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Minimal 8 karakter">
                        
                        <i class="bi bi-eye password-toggle" id="togglePassword"></i>

                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback ps-1">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                            class="form-select form-select-custom <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                            <option value="" disabled <?php echo e(old('role') == '' ? 'selected' : ''); ?>>
                                -- Pilih Hak Akses Pengguna --
                            </option>

                            <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>
                                Administrator (Akses Penuh Sistem)
                            </option>

                            <option value="guru" <?php echo e(old('role') == 'guru' ? 'selected' : ''); ?>>
                                Guru / Wali Kelas (Akses Data Presensi)
                            </option>

                        </select>

                        <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback ps-1">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Tahap 7 — Tambah Pilihan Wali Kelas -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">Wali Kelas</label>
                    <div class="input-group-custom">
                        <i class="bi bi-door-open input-icon"></i>
                        <select 
                            name="kelas_id" 
                            class="form-select form-select-custom <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            
                            <option value="" <?php echo e(old('kelas_id') == '' ? 'selected' : ''); ?>>-- Tidak Ada --</option>
                            
                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k->id); ?>" <?php echo e(old('kelas_id') == $k->id ? 'selected' : ''); ?>>
                                    <?php echo e($k->nama_kelas); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback ps-1">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- NIP Guru/Wali Kelas -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">NIP Guru <span class="text-muted fw-normal">(opsional, khusus wali kelas)</span></label>
                    <div class="input-group-custom">
                        <i class="bi bi-card-text input-icon"></i>
                        <input
                            type="text"
                            name="nip"
                            value="<?php echo e(old('nip')); ?>"
                            class="form-control form-control-custom <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="Contoh: 198501012010012001">
                        <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback ps-1">
                                <?php echo e($message); ?>

                            </div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <small class="text-muted d-block mt-1">Akan ditampilkan pada blok tanda tangan Rekap Laporan Presensi.</small>
                </div>

                <!-- Tombol Simpan & Batal -->
                <div class="d-flex justify-content-end gap-3 mt-5">
                    <a href="<?php echo e(route('users.index')); ?>"
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/users/create.blade.php ENDPATH**/ ?>