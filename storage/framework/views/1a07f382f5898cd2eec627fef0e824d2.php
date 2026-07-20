



<?php $__env->startSection('title', 'Data Siswa - ' . $kelas->nama_kelas); ?>



<?php $__env->startSection('content'); ?>

<div class="container-fluid py-4 text-start">



    

    <?php if(session('sukses')): ?>

        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center" role="alert" style="background-color: #dcfce7; color: #15803d;">

            <i class="bi bi-check-circle-fill me-2 fs-5"></i>

            <div class="fw-medium"><?php echo e(session('sukses')); ?></div>

            <button class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>

    <?php endif; ?>



    <?php if(session('error')): ?>

        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 p-3 mb-4 d-flex align-items-center" role="alert" style="background-color: #fef2f2; color: #b91c1c;">

            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>

            <div class="fw-medium"><?php echo e(session('error')); ?></div>

            <button class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>

    <?php endif; ?>



    <?php if($errors->any()): ?>

        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 p-3 mb-4" role="alert" style="background-color: #fef2f2; color: #b91c1c;">

            <div class="d-flex align-items-center mb-2">

                <i class="bi bi-x-circle-fill me-2 fs-5"></i>

                <strong class="small fw-bold">Terdapat kesalahan validasi berkas:</strong>

            </div>

            <ul class="mb-0 small ps-4 fw-medium">

                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <li><?php echo e($error); ?></li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </ul>

            <button class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>

    <?php endif; ?>



    

    <div class="row align-items-center g-3 mb-4">

        <div class="col-12 col-lg-7">

            <a href="<?php echo e(route('kelas.index')); ?>" class="btn btn-sm btn-light border rounded-3 px-3 py-1.5 fw-semibold text-secondary d-inline-flex align-items-center gap-2 mb-2 shadow-none">

                <i class="bi bi-arrow-left"></i>

                <span>Kembali</span>

            </a>

            <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Data Siswa Kelas <?php echo e($kelas->nama_kelas); ?></h3>


        </div>



        <div class="col-12 col-lg-5 text-lg-end">

            <div class="d-inline-flex flex-wrap gap-2">

                <a href="<?php echo e(route('siswa.tambah')); ?>?kelas_id=<?php echo e($kelas->id); ?>" class="btn btn-primary btn-sm px-3 py-2 rounded-3 fw-semibold d-inline-flex align-items-center shadow-sm" style="font-size: 0.85rem; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">

                    <i class="bi bi-plus-circle-fill me-1.5"></i>Tambah Siswa

                </a>

               

                <button class="btn btn-success btn-sm px-3 py-2 rounded-3 fw-semibold d-inline-flex align-items-center shadow-sm" style="font-size: 0.85rem; box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15) !important;" data-bs-toggle="modal" data-bs-target="#modalImport">

                    <i class="bi bi-file-earmark-excel-fill me-1.5"></i>Import Excel

                </button>



                <?php if($semua_siswa->count()): ?>

                <button type="button" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-2 rounded-3 fw-semibold shadow-none" style="font-size: 0.85rem;" data-bs-toggle="modal" data-bs-target="#confirmDeleteAllModal">

                    <i class="bi bi-trash3-fill"></i> Hapus Semua

                </button>

                <?php endif; ?>

            </div>

        </div>

    </div>



    

    <div class="row g-3 mb-4">

        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">

                <div class="card-body p-0 d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-muted small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Total Siswa</span>

                        <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -1px;"><?php echo e($semua_siswa->count()); ?></h3>

                    </div>

                    <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 text-primary">

                        <i class="bi bi-people-fill fs-4 lh-1"></i>

                    </div>

                </div>

                <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 3px;"></div>

            </div>

        </div>



        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">

                <div class="card-body p-0 d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-muted small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">RFID Terdaftar</span>

                        <h3 class="fw-bold text-success mb-0 font-monospace fs-2" style="letter-spacing: -1px;"><?php echo e($semua_siswa->whereNotNull('rfid_code')->count()); ?></h3>

                    </div>

                    <div class="bg-success bg-opacity-10 p-2 px-3 rounded-3 text-success">

                        <i class="bi bi-credit-card-2-front-fill fs-4 lh-1"></i>

                    </div>

                </div>

                <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px;"></div>

            </div>

        </div>



        <div class="col-12 col-md-4">

            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">

                <div class="card-body p-0 d-flex align-items-center justify-content-between">

                    <div>

                        <span class="text-muted small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Belum Miliki RFID</span>

                        <h3 class="fw-bold text-warning mb-0 font-monospace fs-2" style="letter-spacing: -1px;"><?php echo e($semua_siswa->whereNull('rfid_code')->count()); ?></h3>

                    </div>

                    <div class="bg-warning bg-opacity-10 p-2 px-3 rounded-3 text-warning">

                        <i class="bi bi-exclamation-octagon-fill fs-4 lh-1"></i>

                    </div>

                </div>

                <div class="position-absolute bottom-0 start-0 w-100 bg-warning" style="height: 3px;"></div>

            </div>

        </div>

    </div>



    

    <div class="card border-0 shadow-premium rounded-4 mb-4 bg-white">

        <div class="card-body p-3">

            <div class="row g-3 align-items-center">

                <div class="col-12 col-md-6">

                    <div class="input-group search-merge-group">

                        <span class="input-group-text bg-light border-end-0 text-muted px-3">

                            <i class="bi bi-search small"></i>

                        </span>

                        <input type="text" id="siswaSearch" class="form-control bg-light border-start-0 ps-0 shadow-none" style="font-size: 0.9rem; padding: 10px 12px 10px 0;" placeholder="Cari nama siswa atau nomor induk NIS/NISN...">

                    </div>

                </div>

                <div class="col-12 col-md-6">

                    <div class="d-flex gap-2 justify-content-md-end scroll-x-mobile">

                        <button type="button" class="btn btn-sm btn-dark px-3 py-2 filter-btn rounded-3 active fw-semibold" data-filter="all">Semua Data</button>

                        <button type="button" class="btn btn-sm btn-light border px-3 py-2 filter-btn rounded-3 text-secondary fw-semibold" data-filter="rfid-active">🟢 RFID Aktif</button>

                        <button type="button" class="btn btn-sm btn-light border px-3 py-2 filter-btn rounded-3 text-secondary fw-semibold" data-filter="rfid-empty">⚪ Belum Terdaftar</button>

                    </div>

                </div>

            </div>

        </div>

    </div>



    

    <div class="card shadow-premium border-0 rounded-4 overflow-hidden bg-white">

        <div class="card-header bg-white py-3 border-bottom px-4 d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center gap-2">

                <h5 class="fw-bold text-dark mb-0" style="font-size: 1rem;">Daftar Siswa Aktif</h5>

                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3 py-1.5 font-monospace fw-bold" style="font-size: 0.75rem;" id="counterRows"><?php echo e($semua_siswa->count()); ?> Data</span>

            </div>

        </div>



        <div class="table-responsive">

            <table class="table align-middle mb-0 table-custom-hover" id="tableSiswa">

                <thead>

                    <tr>

                        <th width="70" class="text-center">No</th>

                        <th width="180">NIS / NISN</th>

                        <th>Nama Lengkap Siswa</th>

                        <th width="200">No. WhatsApp Wali</th>

                        <th width="260">Status Registrasi RFID</th>

                        <th width="140" class="text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                <?php $__empty_1 = true; $__currentLoopData = $semua_siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr class="siswa-row" data-rfid-status="<?php echo e($siswa->rfid_code ? 'active' : 'empty'); ?>">

                        <td class="text-center text-muted fw-medium font-monospace"><?php echo e($index + 1); ?></td>

                        <td>

                            <span class="text-secondary font-monospace d-block small" style="font-size: 0.82rem;"><?php echo e($siswa->nis ?? $siswa->nisn ?? '-'); ?></span>

                        </td>

                        <td>

                            <div class="d-flex align-items-center gap-2 text-truncate">

                                <div class="avatar-circle-sm bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center border flex-shrink-0">

                                    <i class="bi bi-person-fill small"></i>

                                </div>

                                <span class="fw-bold text-dark text-uppercase tracking-wide text-name-target text-truncate" style="font-size: 0.82rem; letter-spacing: 0.2px;"><?php echo e($siswa->nama_siswa); ?></span>

                            </div>

                        </td>

                        <td>

                            <?php if(empty($siswa->no_hp_orang_tua)): ?>

                                <span class="text-muted fst-italic small" style="font-size: 0.82rem;"><i class="bi bi-slash-circle me-1"></i>Belum Diisi</span>

                            <?php else: ?>

                                <span class="font-monospace text-dark d-inline-flex align-items-center gap-1.5 small" style="font-size: 0.82rem;">

                                    <i class="bi bi-whatsapp text-success"></i> <?php echo e($siswa->no_hp_orang_tua); ?>


                                </span>

                            <?php endif; ?>

                        </td>

                       

                        <td>

                            <?php if($siswa->rfid_code): ?>

                                <div class="d-inline-flex align-items-center bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-3 gap-2">

                                    <div class="blob-wrapper">

                                        <span class="blob-dot-success"></span>

                                        <span class="blob-pulse-success"></span>

                                    </div>

                                    <span class="font-monospace fw-bold tracking-wider" style="font-size: 0.8rem;">

                                        <i class="bi bi-credit-card-2-front me-1"></i><?php echo e($siswa->rfid_code); ?>


                                    </span>

                                </div>

                            <?php else: ?>

                                <div class="d-inline-flex align-items-center bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1.5 rounded-3 gap-2">

                                    <span class="blob-dot-secondary"></span>

                                    <span class="fw-semibold text-muted" style="font-size: 0.8rem;">

                                        <i class="bi bi-card-heading me-1 text-secondary opacity-75"></i>Belum Terdaftar

                                    </span>

                                </div>

                            <?php endif; ?>

                        </td>

                       

                        <td class="text-center">

                            <div class="d-flex justify-content-center gap-2">

                                <a href="<?php echo e(route('siswa.edit', $siswa->id)); ?>" class="btn btn-link p-0 text-warning shadow-none" title="Edit Log Data">

                                    <div class="p-2 bg-warning bg-opacity-10 rounded-3 text-warning d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">

                                        <i class="bi bi-pencil-fill small"></i>

                                    </div>

                                </a>

                                <button type="button"

                                        class="btn btn-link p-0 text-danger shadow-none"

                                        data-bs-toggle="modal"

                                        data-bs-target="#confirmDeleteModal"

                                        data-siswa-id="<?php echo e($siswa->id); ?>"

                                        data-siswa-name="<?php echo e($siswa->nama_siswa); ?>"

                                        title="Hapus Permanen">

                                    <div class="p-2 bg-danger bg-opacity-10 rounded-3 text-danger d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">

                                        <i class="bi bi-trash-fill small"></i>

                                    </div>

                                </button>

                            </div>

                        </td>

                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr id="emptyRowInitial">

                        <td colspan="6" class="text-center py-5 text-muted bg-light bg-opacity-25">

                            <div class="py-5 opacity-75">

                                <div class="bg-white border rounded-circle shadow-sm d-inline-flex p-3 text-muted mb-3">

                                    <i class="bi bi-people fs-2 lh-1"></i>

                                </div>

                                <h6 class="fw-bold text-dark mb-1">Belum Ada Data Siswa</h6>

                                <p class="text-muted small max-w-sm mx-auto mb-0">Kelas ini masih kosong. Silakan tambahkan entitas siswa secara manual atau gunakan fitur import template berkas excel.</p>

                            </div>

                        </td>

                    </tr>

                <?php endif; ?>

               

                <tr id="emptyRowSearch" style="display: none;">

                    <td colspan="6" class="text-center py-5 text-muted bg-light bg-opacity-25">

                        <div class="py-5 opacity-75">

                            <div class="p-3 bg-white border rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">

                                <i class="bi bi-search text-secondary fs-4"></i>

                            </div>

                            <h6 class="fw-bold text-dark mb-1">Data Siswa Tidak Ditemukan</h6>

                            <p class="text-muted small mb-0">Kata kunci atau filter pencarian tidak cocok dengan record siswa mana pun di kelas ini.</p>

                        </div>

                    </td>

                </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>





<div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">

    <div class="modal-dialog modal-dialog-centered">

        <form action="<?php echo e(route('siswa.import')); ?>" method="POST" enctype="multipart/form-data">

            <?php echo csrf_field(); ?>

            <input type="hidden" name="kelas_id" value="<?php echo e($kelas->id); ?>">



            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="modal-header border-bottom py-3 px-4">

                    <div class="d-flex align-items-center gap-2">

                        <div class="bg-success bg-opacity-10 text-success p-2 rounded-3">

                            <i class="bi bi-file-earmark-arrow-up-fill fs-5 lh-1"></i>

                        </div>

                        <h5 class="modal-title fw-bold text-dark" style="font-size: 1.05rem;">Import Spreadsheet Siswa</h5>

                    </div>

                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>



                <div class="modal-body p-4 text-start">

                    <div class="mb-4">

                        <label class="form-label fw-semibold text-muted small text-uppercase tracking-wide mb-2">Unggah Dokumen Berkas</label>

                        <div class="dropzone-area-wrapper position-relative border border-2 border-dashed rounded-4 p-4 text-center bg-light transition-all">

                            <input type="file" name="file_excel" id="fileExcelInput" class="position-absolute top-0 start-0 w-100 h-100 opacity-0 cursor-pointer" accept=".xlsx,.xls,.csv" required>

                            <div class="py-2">

                                <i class="bi bi-cloud-arrow-up-fill text-success display-5 lh-1 mb-2 d-block opacity-75"></i>

                                <span class="fw-bold text-dark d-block mb-1" id="fileNamePlaceholder" style="font-size: 0.9rem;">Pilih berkas excel atau drop disini</span>

                                <span class="text-muted small">Mendukung ekstensi berkas (.xlsx, .xls, .csv)</span>

                            </div>

                        </div>

                    </div>



                    <div>

                        <span class="fw-semibold text-muted small text-uppercase tracking-wide d-block mb-2">

                            <i class="bi bi-info-circle-fill text-info me-1"></i> Aturan Kolom Spreadsheet:

                        </span>

                        <div class="table-responsive rounded-3 border border-light">

                            <table class="table table-sm table-bordered mb-0 font-monospace text-center align-middle bg-white" style="font-size: 0.78rem;">

                                <thead class="table-light fw-bold text-secondary">

                                    <tr>

                                        <th width="25%">Kolom A</th>

                                        <th width="35%">Kolom B</th>

                                        <th width="40%">Kolom C</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr class="fw-semibold text-dark">

                                        <td>No Urut</td>

                                        <td><code class="text-danger fw-bold">nisn</code> / NIS</td>

                                        <td><code class="text-danger fw-bold">nama</code> Siswa</td>

                                    </tr>

                                    <tr class="text-muted">

                                        <td>1</td>

                                        <td>0045261882</td>

                                        <td>ACHMAD JANUAR P.</td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                        <span class="text-muted d-block mt-2 lh-sm" style="font-size: 0.72rem;">*Pastikan baris pertama pada file excel Anda langsung berupa data siswa (Bukan baris judul/header kolom).</span>

                    </div>

                </div>



                <div class="modal-footer border-top bg-light bg-opacity-50 py-3 px-4">

                    

                    <button type="button" class="btn btn-light border px-4 rounded-3 fw-semibold" style="font-size: 0.9rem;" data-bs-dismiss="modal">Batal</button>

                    <button type="submit" class="btn btn-success px-4 rounded-3 fw-semibold d-inline-flex align-items-center gap-1.5" style="font-size: 0.9rem; box-shadow: 0 4px 12px rgba(25, 135, 84, 0.15) !important;">

                        <i class="bi bi-cloud-check-fill"></i> Mulai Sinkronisasi

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>





<div class="modal fade" id="confirmDeleteAllModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">

        <div class="modal-content modal-saas">

            <div class="modal-header modal-saas-header justify-content-center position-relative">

                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">

                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>

                </div>

                <button type="button" class="btn-close position-absolute" style="top: 20px; right: 20px;" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body modal-saas-body text-center">

                <h5 class="fw-bold text-dark mb-2">Kosongkan Seluruh Kelas?</h5>

                <p class="text-muted mb-0 small px-2">Tindakan destruktif ini akan menghapus seluruh entitas data master siswa yang terdaftar di kelas ini secara permanen dari basis data.</p>

            </div>

            <div class="modal-footer modal-saas-footer d-flex gap-2">

                <button type="button" class="btn btn-saas-secondary flex-grow-1 py-2 fw-semibold" data-bs-dismiss="modal">Tidak, Batal</button>

                <form action="<?php echo e(route('siswa.hapus_semua')); ?>" method="POST" class="flex-grow-1 m-0">

                    <?php echo csrf_field(); ?>

                    <input type="hidden" name="kelas_id" value="<?php echo e($kelas->id); ?>">

                    <button type="submit" class="btn btn-danger btn-saas-primary w-100 py-2 fw-semibold shadow-none">Ya, Hapus Semua</button>

                </form>

            </div>

        </div>

    </div>

</div>





<div class="modal fade" id="confirmDeleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">

        <div class="modal-content modal-saas">

            <div class="modal-header modal-saas-header justify-content-center position-relative">

                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">

                    <i class="bi bi-exclamation-triangle-fill fs-3"></i>

                </div>

                <button type="button" class="btn-close position-absolute" style="top: 20px; right: 20px;" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body modal-saas-body text-center">

                <h5 class="fw-bold text-dark mb-2">Hapus Data Siswa?</h5>

                <p class="text-muted mb-0 small px-2">Tindakan ini akan menghapus log data master siswa atas nama <strong id="deleteModalTargetName" class="text-dark"></strong> secara permanen dari sistem.</p>

            </div>

            <div class="modal-footer modal-saas-footer d-flex gap-2">

                <button type="button" class="btn btn-saas-secondary flex-grow-1 py-2 fw-semibold" data-bs-dismiss="modal">Tidak, Batal</button>

                <form id="deleteModalForm" method="POST" class="flex-grow-1 m-0">

                    <?php echo csrf_field(); ?>

                    <?php echo method_field('DELETE'); ?>

                    <button type="submit" class="btn btn-danger btn-saas-primary w-100 py-2 fw-semibold shadow-none">Ya, Hapus</button>

                </form>

            </div>

        </div>

    </div>

</div>





<style>

    .shadow-premium {

        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;

    }

   

    .table th {

        font-weight: 600;

        text-transform: uppercase;

        font-size: 0.75rem;

        letter-spacing: 0.5px;

        color: #64748b;

        padding: 14px 16px !important;

        background-color: #f8fafc !important;

        border-bottom: 2px solid #edeff1 !important;

    }

    .table td {

        padding: 14px 16px !important;

        border-bottom: 1px solid #f1f3f5 !important;

        font-size: 0.9rem;

    }

    .table-custom-hover tbody tr.siswa-row {

        transition: background-color 0.2s ease;

    }

    .table-custom-hover tbody tr.siswa-row:hover {

        background-color: #fcfdfe !important;

    }

   

    .avatar-circle-sm {

        width: 32px;

        height: 32px;

    }

   

    .blob-wrapper {

        position: relative;

        width: 8px;

        height: 8px;

        display: flex;

        align-items: center;

        justify-content: center;

        flex-shrink: 0;

    }

    .blob-dot-success, .blob-dot-secondary {

        width: 8px;

        height: 8px;

        border-radius: 50%;

        display: inline-block;

        z-index: 2;

    }

    .blob-dot-success { background-color: #198754; }

    .blob-dot-secondary { background-color: #6c757d; }



    .blob-pulse-success {

        position: absolute;

        width: 8px;

        height: 8px;

        border-radius: 50%;

        background-color: #198754;

        animation: pulsingBlob 2s infinite ease-in-out;

        z-index: 1;

        opacity: 0.6;

    }



    @keyframes pulsingBlob {

        0% {

            transform: scale(1);

            opacity: 0.6;

        }

        100% {

            transform: scale(2.8);

            opacity: 0;

        }

    }

   

    .search-merge-group {

        border-radius: 10px;

        overflow: hidden;

        border: 1px solid #dee2e6;

        transition: all 0.2s ease;

    }

    .search-merge-group:focus-within {

        border-color: #0d6efd;

        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;

    }

    .search-merge-group .form-control {

        border: none !important;

        background-color: #f8f9fa !important;

    }

    .search-merge-group .input-group-text {

        border: none !important;

        background-color: #f8f9fa !important;

    }

   

    .scroll-x-mobile { overflow-x: auto; white-space: nowrap; max-width: 100%; }

   

    .dropzone-area-wrapper {

        border-color: #cbd5e1 !important;

    }

    .dropzone-area-wrapper:hover {

        background-color: #ffffff !important;

        border-color: #198754 !important;

    }

    .modal-content {

        border-radius: 16px !important;

        border: none !important;

        box-shadow: 0 20px 50px rgba(0,0,0,0.15) !important;

    }



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



    @media (max-width: 991.98px) {

        .text-lg-end { text-align: left !important; }

    }

    @media (max-width: 767.98px) {

        .scroll-x-mobile { padding-bottom: 4px; }

    }

</style>





<script>

document.addEventListener("DOMContentLoaded", function() {

    const searchInput = document.getElementById('siswaSearch');

    const filterButtons = document.querySelectorAll('.filter-btn');

    const tableRows = document.querySelectorAll('.siswa-row');

    const emptyRowInitial = document.getElementById('emptyRowInitial');

    const emptyRowSearch = document.getElementById('emptyRowSearch');

    const counterRows = document.getElementById('counterRows');

    const fileInput = document.getElementById('fileExcelInput');

    const fileNamePlaceholder = document.getElementById('fileNamePlaceholder');



    const confirmDeleteModal = document.getElementById('confirmDeleteModal');

    if (confirmDeleteModal) {

        confirmDeleteModal.addEventListener('show.bs.modal', function(event) {

            const button = event.relatedTarget;

            const siswaId = button.getAttribute('data-siswa-id');

            const siswaName = button.getAttribute('data-siswa-name');

           

            const modalForm = confirmDeleteModal.querySelector('#deleteModalForm');

            const modalTargetName = confirmDeleteModal.querySelector('#deleteModalTargetName');

           

            modalForm.action = `/siswa/hapus/${siswaId}`;

            modalTargetName.textContent = siswaName;

        });

    }



    let currentFilter = 'all';

    let currentSearchTerm = '';



    if (fileInput) {

        fileInput.addEventListener('change', function(e) {

            if (e.target.files.length > 0) {

                fileNamePlaceholder.textContent = e.target.files[0].name;

                fileNamePlaceholder.classList.remove('text-dark');

                fileNamePlaceholder.classList.add('text-success');

            }

        });

    }



    function filterTable() {

        let visibleCount = 0;

        let totalExist = tableRows.length;



        if (totalExist === 0) return;



        tableRows.forEach(row => {

            const rfidStatus = row.getAttribute('data-rfid-status');

            const studentName = row.querySelector('.text-name-target').textContent.toLowerCase();

            const studentInduk = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

           

            const matchFilter = (currentFilter === 'all') ||

                                (currentFilter === 'rfid-active' && rfidStatus === 'active') ||

                                (currentFilter === 'rfid-empty' && rfidStatus === 'empty');

                               

            const matchSearch = studentName.includes(currentSearchTerm) || studentInduk.includes(currentSearchTerm);



            if (matchFilter && matchSearch) {

                row.style.display = '';

                visibleCount++;

            } else {

                row.style.display = 'none';

            }

        });



        counterRows.textContent = visibleCount + " Data";



        if (visibleCount === 0) {

            emptyRowSearch.style.style.display = '';

        } else {

            emptyRowSearch.style.display = 'none';

        }

    }



    if (searchInput) {

        searchInput.addEventListener('input', function(e) {

            currentSearchTerm = e.target.value.toLowerCase().trim();

            filterTable();

        });

    }



    filterButtons.forEach(btn => {

        btn.addEventListener('click', function() {

            filterButtons.forEach(b => {

                b.classList.remove('btn-dark', 'active');

                b.classList.add('btn-light', 'border');

            });

           

            this.classList.remove('btn-light', 'border');

            this.classList.add('btn-dark', 'active');



            currentFilter = this.getAttribute('data-filter');

            filterTable();

        });

    });

});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/kelas/show.blade.php ENDPATH**/ ?>