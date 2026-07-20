

<?php $__env->startSection('title', 'Tambah Data Siswa'); ?>

<?php $__env->startSection('content'); ?>
<!-- Style khusus untuk penyelarasan tema premium SaaS modern -->
<style>
    .page-header-box {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
    }
    .info-pill {
        background-color: #f1f5f9;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.87rem;
    }
    .form-section-title { 
        font-size: 0.85rem; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        color: #94a3b8; 
        font-weight: 700; 
        margin-bottom: 1.5rem; 
    }
    .form-label {
        color: #475569;
        font-size: 0.9rem;
    }
    
    /* Search Bar & Form Controls Premium Style */
    .search-merge-group {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
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
        background-color: #ffffff !important;
        font-size: 0.95rem;
        color: #1e293b;
    }
    .search-merge-group .input-group-text {
        background-color: #f8fafc !important;
        border-end: 1px solid #cbd5e1 !important;
        color: #64748b; 
    }
    
    .shadow-premium {
        box-shadow: 0 8px 24px rgba(149, 157, 165, 0.05) !important;
    }

    /* RFID Scanner Area Modern Layout */
    .rfid-scanner-zone { 
        background-color: #f8fafc; 
        border: 1px solid #e2e8f0; 
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.25s ease; 
    }
    .rfid-scanner-zone.active-card { 
        background-color: #f0fdf4; 
        border-color: #bbf7d0; 
    }
    .rfid-display-input {
        max-width: 240px;
        margin: 0 auto;
        font-family: monospace;
        font-weight: 600;
        letter-spacing: 1px;
        text-align: center;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
    }
</style>

<div class="container-fluid py-4 text-start">

    <!-- HEADER & CARD INFORMASI RINGKAS -->
    <div class="card page-header-box border-0 shadow-premium rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1" style="font-size: 1.5rem; letter-spacing: -0.5px; color: #1e293b;">Tambah Data Siswa Baru</h3>
                <p class="text-muted mb-0 small">Masukkan informasi identitas murid beserta setelan kartu RFID presensi.</p>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <div class="info-pill text-secondary fw-medium">
                    Target: <span class="fw-bold text-dark">
                        <?php $__currentLoopData = $daftar_kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(old('kelas_id', $kelasTerpilih) == $kls->id): ?>
                                Kelas <?php echo e($kls->nama_kelas); ?>

                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </span>
                </div>
                <div class="info-pill text-secondary fw-medium">
                    RFID: <span class="fw-bold text-warning">Siap Pindai</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ERROR LIST HANDLING -->
    <?php if($errors->any()): ?>
    <div class="alert alert-danger rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-start gap-3" style="background-color: #fef2f2; color: #b91c1c;">
        <i class="bi bi-exclamation-circle-fill text-danger fs-5 mt-0.5"></i>
        <div>
            <strong class="d-block mb-1 fw-bold">Penyimpanan Gagal</strong>
            <ul class="mb-0 small text-danger ps-3 fw-medium">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <!-- MAIN FORM CARD -->
    <div class="card border-0 shadow-premium rounded-4 bg-white">
        <div class="card-body p-4">
            <form action="<?php echo e(route('siswa.simpan')); ?>" method="POST" class="needs-validation" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <!-- KOLOM KIRI: IDENTITAS DASAR -->
                    <div class="col-md-7 mb-4 mb-md-0 border-end pe-md-4">
                        <div class="form-section-title">Identitas Utama Murid</div>
                        
                        <!-- Nama Siswa -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Nama Lengkap</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="nama_siswa" 
                                    value="<?php echo e(old('nama_siswa')); ?>"
                                    placeholder="Contoh: ACHMAD JANUAR PAMUNGKAS"
                                    class="form-control shadow-none <?php $__errorArgs = ['nama_siswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="padding: 10px 12px;">
                                <?php $__errorArgs = ['nama_siswa'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback px-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- NISN -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Nomor Induk Siswa Nasional (NISN)</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-card-text text-muted"></i></span>
                                <input type="text" name="nisn" 
                                    value="<?php echo e(old('nisn')); ?>"
                                    placeholder="Contoh: 3166469971"
                                    class="form-control shadow-none <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="padding: 10px 12px;">
                                <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback px-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Penempatan Kelas -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Penempatan Kelas</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-layers text-muted"></i></span>
                                <select name="kelas_id" class="form-select shadow-none" style="padding: 10px 12px;">
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php $__currentLoopData = $daftar_kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($kls->id); ?>" <?php echo e(old('kelas_id', $kelasTerpilih)==$kls->id ? 'selected' : ''); ?>>
                                            Kelas <?php echo e($kls->nama_kelas); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: INTEGRATION -->
                    <div class="col-md-5 ps-md-4">
                        <div class="form-section-title">Konfigurasi Sistem</div>

                        <!-- RFID Scanner Area Modern Minimalis -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-flex justify-content-between align-items-center mb-2">
                                <span>Pendaftaran Tag RFID</span>
                                <span class="badge bg-light text-secondary border rounded-pill fw-medium px-2.5 py-1" style="font-size: 0.72rem;">Device Online</span>
                            </label>
                            
                            <div class="rfid-scanner-zone <?php echo e(old('rfid_code') ? 'active-card' : ''); ?> text-center">
                                <span class="text-muted small d-block mb-2">Dekatkan kartu fisik ke mesin pembaca</span>
                                
                                <input type="text" name="rfid_code" 
                                    value="<?php echo e(old('rfid_code')); ?>"
                                    placeholder="Menunggu sensor..."
                                    class="form-control form-control-sm rfid-display-input shadow-none <?php $__errorArgs = ['rfid_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="padding: 8px 10px;">
                                
                                <?php $__errorArgs = ['rfid_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block mt-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="d-flex justify-content-between mt-1.5 px-1">
                                <span class="text-muted xsmall" style="font-size: 0.78rem;">Status Capture:</span>
                                <span class="fw-semibold xsmall text-dark" id="rfid-status" style="font-size: 0.78rem;">
                                    <?php echo e(old('rfid_code') ? 'Terbaca: '.old('rfid_code') : 'Kosong'); ?>

                                </span>
                            </div>
                        </div>

                        <!-- WhatsApp Gateway Module -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Nomor WhatsApp Orang Tua</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-whatsapp text-muted"></i></span>
                                <input type="text" name="no_hp_orang_tua" 
                                    value="<?php echo e(old('no_hp_orang_tua')); ?>"
                                    placeholder="Contoh: 6281234567890"
                                    class="form-control shadow-none <?php $__errorArgs = ['no_hp_orang_tua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="padding: 10px 12px;">
                                <?php $__errorArgs = ['no_hp_orang_tua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback px-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <span class="text-muted d-block mt-2" style="font-size: 0.75rem; line-height: 1.4;">
                                Gunakan awalan kode negara <strong>628</strong> (tanpa karakter + atau spasi) untuk sinkronisasi pengiriman notifikasi kirim pesan otomatis.
                            </span>
                        </div>

                        <!-- Bagian Pembungkus Pratinjau Foto Murid (Sinkron dengan edit.blade.php) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Foto Murid</label>
                            
                            <div class="d-block mb-2.5">
                                <div class="p-1 bg-white border rounded-3 d-inline-block shadow-sm">
                                    <div class="d-flex align-items-center justify-content-center bg-light rounded-2 text-secondary" style="width: 110px; height: 130px;">
                                        <i class="bi bi-person-bounding-box fs-2 opacity-50"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-image text-muted"></i></span>
                                <input type="file" name="foto" 
                                    class="form-control shadow-none <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    style="padding: 10px 12px;"
                                    accept="image/*">
                                <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback px-2"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <span class="text-muted d-block mt-2" style="font-size: 0.75rem; line-height: 1.4;">
                                Format dokumen yang didukung: <strong>JPG, JPEG, PNG</strong> dengan ukuran berkas maksimum <strong>2 MB</strong>.
                            </span>
                        </div>
                    </div>

                    <!-- SUBMIT ACTIONS -->
                    <div class="col-12">
                        <hr class="my-4" style="border-color: #f1f3f5;">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e($kelasTerpilih ? route('kelas.show', $kelasTerpilih) : route('kelas.index')); ?>"
                               class="btn btn-light border rounded-3 px-4 fw-semibold text-secondary shadow-none">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm" style="box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">
                                Simpan Data Siswa
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil Disimpan',
        text: "<?php echo e(session('success')); ?>",
        showConfirmButton: false,
        timer: 1800
    });
</script>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfidInput = document.querySelector('input[name="rfid_code"]');
    const rfidBox = document.querySelector('.rfid-scanner-zone');
    const rfidStatus = document.getElementById('rfid-status');
    
    if (rfidInput) {
        rfidInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                rfidBox.classList.add('active-card');
                rfidStatus.innerText = 'Terbaca: ' + rfidInput.value;
                rfidStatus.className = "fw-semibold text-success";
            }
        });
        
        rfidInput.addEventListener('input', function() {
            if(this.value.trim() === "") {
                rfidBox.classList.remove('active-card');
                rfidStatus.innerText = 'Kosong';
                rfidStatus.className = "text-dark";
            }
        });
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/siswa/tambah.blade.php ENDPATH**/ ?>