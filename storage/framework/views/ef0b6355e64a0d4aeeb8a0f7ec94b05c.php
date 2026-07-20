<?php $__env->startSection('title', 'Kelola Presensi Siswa (Manual) - SDN Tengah 03'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        /* Penyelarasan Tabel & Layout Premium */
        .table {
            table-layout: fixed;
            width: 100%;
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
            padding: 12px 16px !important;
            border-bottom: 1px solid #f1f3f5 !important;
            font-size: 0.88rem;
        }
        .table tbody tr {
            height: 68px;
            transition: background-color 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #fcfdfe !important;
        }
        .table th, .table td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        /* SEGMENTED CONTROL BARU UNTUK PILIHAN STATUS KEHADIRAN */
        .segmented-control {
            display: inline-flex;
            background-color: #f1f5f9;
            padding: 4px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }
        .segmented-item {
            position: relative;
        }
        .segmented-item .btn-check {
            display: none;
        }
        .segmented-item .btn-segment {
            font-size: 0.78rem;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 7px;
            border: none;
            color: #64748b;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-block;
        }
        .segmented-item .btn-segment:hover {
            color: #1e293b;
        }
        /* Efek Aktif Segmented Control dengan Warna Solid Premium */
        .btn-check:checked + .btn-segment.seg-hadir {
            background-color: #15803d !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(21, 128, 61, 0.2);
        }
        .btn-check:checked + .btn-segment.seg-sakit {
            background-color: #0284c7 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2);
        }
        .btn-check:checked + .btn-segment.seg-izin {
            background-color: #d97706 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(217, 119, 6, 0.2);
        }
        .btn-check:checked + .btn-segment.seg-alpa {
            background-color: #dc2626 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.2);
        }

        /* Pulsing Blob Dot untuk RFID */
        .blob-wrapper {
            position: relative;
            width: 8px;
            height: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .blob-dot-success {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #15803d;
            border-radius: 50%;
            z-index: 2;
        }
        .blob-pulse-success {
            position: absolute;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #15803d;
            animation: pulsingBlob 2s infinite ease-in-out;
            z-index: 1;
            opacity: 0.6;
        }
        @keyframes pulsingBlob {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(2.8); opacity: 0; }
        }

        /* Form Controls Styling */
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

        .shadow-premium {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
        }

        /* Grid 5 Kolom Statistik Modern */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 1rem;
        }
        @media (max-width: 1200px) {
            .stat-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 768px) {
            .stat-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); }
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

        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-3 border-bottom border-light text-start">
            <div>
                <h3 class="fw-bold mb-1" style="letter-spacing: -0.5px; color: #1e293b;">Kelola Presensi Siswa</h3>
                <p class="text-muted small mb-0">Kelola master log kehadiran berkala siswa secara manual berdasarkan data kelas dan tanggal aktif.</p>
            </div>
            <div>
                <?php if(($kelas_id || auth()->user()->role == 'guru') && isset($semua_siswa) && $semua_siswa->count() > 0): ?>
                    <!-- DATA TARGET MODAL DITAMBAHKAN DI SINI -->
                    <button type="button" data-bs-toggle="modal" data-bs-target="#confirmPresensiModal" class="btn btn-primary px-3 py-2 rounded-3 fw-semibold d-inline-flex align-items-center gap-2 shadow-sm" style="font-size: 0.85rem; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">
                        <i class="bi bi-check-circle-fill"></i> Simpan Presensi
                    </button>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if(session('sukses')): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4 d-flex align-items-center text-start" role="alert" style="background-color: #dcfce7; color: #15803d;">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div class="fw-medium"><?php echo e(session('sukses')); ?></div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 p-3 mb-4 d-flex align-items-center text-start" role="alert" style="background-color: #fef2f2; color: #b91c1c;">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div class="fw-medium"><?php echo e(session('error')); ?></div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        
        <div class="card border-0 shadow-premium rounded-4 mb-4 text-start bg-white">
            <div class="card-body p-3">
                <form action="/presensi/manual" method="GET">
                    <div class="row g-2 align-items-center">
                        
                        <?php if(auth()->user()->role == 'admin'): ?>
                            <div class="col-md-5">
                                <select name="kelas_id" class="form-select rounded-3 p-2 shadow-none border" style="font-size: 0.9rem;" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    <?php $__currentLoopData = $daftar_kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($k->id); ?>" <?php echo e(request('kelas_id') == $k->id ? 'selected' : ''); ?>>
                                            Kelas <?php echo e($k->nama_kelas); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="date" name="tanggal" class="form-control rounded-3 p-2 shadow-none border" style="font-size: 0.9rem;" value="<?php echo e(request('tanggal', date('Y-m-d'))); ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100 p-2 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="font-size: 0.9rem; background-color: #0d6efd; border: none;">
                                    <i class="bi bi-arrow-clockwise"></i> Tampilkan Siswa
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="col-md-9">
                                <input type="date" name="tanggal" class="form-control rounded-3 p-2 shadow-none border" style="font-size: 0.9rem;" value="<?php echo e(request('tanggal', date('Y-m-d'))); ?>">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100 p-2 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="font-size: 0.9rem; background-color: #0d6efd; border: none;">
                                    <i class="bi bi-arrow-clockwise"></i> Perbarui Tanggal
                                </button>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                </form>
            </div>
        </div>

        
        <?php if($kelas_id || auth()->user()->role == 'guru'): ?>
        
        <?php
            $jumlahHadir = $semua_siswa->filter(function($s) {
                return $s->status_hari_ini == 'Hadir' || $s->status_hari_ini == 'Terlambat';
            })->count();
            
            $jumlahSakit = $semua_siswa->where('status_hari_ini', 'Sakit')->count();
            $jumlahIzin  = $semua_siswa->where('status_hari_ini', 'Izin')->count();
            
            $jumlahAlpa  = $semua_siswa->filter(function($s) {
                return $s->status_hari_ini == 'Alpa' || $s->status_hari_ini == 'Belum Absen' || !$s->status_hari_ini;
            })->count();
        ?>
        
        
        <div class="stat-grid mb-4 text-start">
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Total Siswa</span>
                        <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -1px;"><?php echo e($semua_siswa->count()); ?></h3>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-2 px-3 rounded-3 text-secondary">
                        <i class="bi bi-people-fill fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-secondary opacity-50" style="height: 3px;"></div>
            </div>
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-success small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Hadir</span>
                        <h3 class="fw-bold text-success mb-0 font-monospace fs-2" style="letter-spacing: -1px;"><?php echo e($jumlahHadir); ?></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 px-3 rounded-3 text-success">
                        <i class="bi bi-check-circle fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px;"></div>
            </div>
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-primary small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Sakit</span>
                        <h3 class="fw-bold text-primary mb-0 font-monospace fs-2" style="letter-spacing: -1px;"><?php echo e($jumlahSakit); ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 text-primary">
                        <i class="bi bi-heart-fill fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 3px;"></div>
            </div>
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-warning small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Izin</span>
                        <h3 class="fw-bold text-warning mb-0 font-monospace fs-2" style="letter-spacing: -1px;"><?php echo e($jumlahIzin); ?></h3>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-2 px-3 rounded-3 text-warning">
                        <i class="bi bi-file-earmark-text fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-warning" style="height: 3px;"></div>
            </div>
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-danger small d-block mb-1 fw-semibold text-uppercase tracking-wider" style="font-size: 0.7rem;">Alpa</span>
                        <h3 class="fw-bold text-danger mb-0 font-monospace fs-2" style="letter-spacing: -1px;"><?php echo e($jumlahAlpa); ?></h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-2 px-3 rounded-3 text-danger">
                        <i class="bi bi-x-lg fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-danger" style="height: 3px;"></div>
            </div>
        </div>

        
        <div class="card border-0 shadow-premium rounded-4 mb-4 bg-white">
            <div class="card-body p-3">
                <div class="row align-items-center g-3">
                    <div class="col-md-6 text-start">
                        <div class="input-group search-merge-group">
                            <span class="input-group-text bg-light border-end-0 text-muted px-3">
                                <i class="bi bi-search small"></i>
                            </span>
                            <input type="text" id="searchSiswa" class="form-control bg-light border-start-0 ps-0 shadow-none" style="font-size: 0.9rem; padding: 10px 12px 10px 0;" placeholder="Cari nama siswa atau NISN...">
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end text-start">
                        <div class="d-inline-flex gap-2 flex-wrap">
                            <button type="button" class="btn btn-sm btn-dark rounded-3 px-3 py-2 filter-status-btn active fw-semibold" data-filter="Semua">Semua</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-3 py-2 filter-status-btn text-secondary fw-semibold" data-filter="Hadir">Hadir</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-3 py-2 filter-status-btn text-secondary fw-semibold" data-filter="Sakit">Sakit</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-3 py-2 filter-status-btn text-secondary fw-semibold" data-filter="Izin">Izin</button>
                            <button type="button" class="btn btn-sm btn-light border rounded-3 px-3 py-2 filter-status-btn text-secondary fw-semibold" data-filter="Alpa">Alpa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card p-0 overflow-hidden text-start rounded-4 border-0 shadow-premium bg-white">
            <form action="<?php echo e(url('/presensi/manual/simpan')); ?>" method="POST" id="formPresensi">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="kelas_id" value="<?php echo e($kelas_id); ?>">
                <input type="hidden" name="tanggal" value="<?php echo e(request('tanggal', date('Y-m-d'))); ?>">

                <div class="table-responsive">
                    <table class="table align-middle m-0 table-custom-hover">
                        <thead>
                            <tr>
                                <th style="width: 7%;" class="text-center">No</th>
                                <th style="width: 28%;">Nama Siswa</th>
                                <th style="width: 18%;">RFID Code</th>
                                <th style="width: 27%;">Status Kehadiran</th>
                                <th style="width: 20%;">Keterangan Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($semua_siswa) && $semua_siswa->count() > 0): ?>
                                <?php $__currentLoopData = $semua_siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $statusAwal = 'Alpa';
                                    if($siswa->status_hari_ini == 'Hadir' || $siswa->status_hari_ini == 'Terlambat') $statusAwal = 'Hadir';
                                    elseif($siswa->status_hari_ini == 'Sakit') $statusAwal = 'Sakit';
                                    elseif($siswa->status_hari_ini == 'Izin') $statusAwal = 'Izin';
                                ?>
                                <tr class="baris-siswa" data-status-aktif="<?php echo e($statusAwal); ?>">
                                    <td class="text-center text-muted fw-medium font-monospace"><?php echo e($index + 1); ?></td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center gap-2.5 text-truncate">
                                            <?php if($siswa->foto): ?>
                                                <img src="<?php echo e(asset('storage/'.$siswa->foto)); ?>" style="width:36px; height:36px; border-radius:50%; object-fit:cover;" class="border" alt="Foto">
                                            <?php else: ?>
                                                <img src="<?php echo e(asset('assets/img/default-user.png')); ?>" style="width:36px; height:36px; border-radius:50%; object-fit:cover;" class="border" alt="Default">
                                            <?php endif; ?>
                                            <div class="overflow-hidden">
                                                <div class="fw-bold text-dark text-uppercase text-truncate mb-0.5" style="font-size: 0.84rem; letter-spacing: 0.2px;" data-search="<?php echo e(strtolower($siswa->nama_siswa)); ?> <?php echo e($siswa->nisn); ?>"><?php echo e($siswa->nama_siswa); ?></div>
                                                <div class="text-muted font-monospace" style="font-size:0.75rem;">NISN: <?php echo e($siswa->nisn); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <?php if($siswa->rfid_code): ?>
                                            <div class="d-inline-flex align-items-center bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2.5 py-1.5 rounded-3 gap-2">
                                                <div class="blob-wrapper">
                                                    <span class="blob-dot-success"></span>
                                                    <span class="blob-pulse-success"></span>
                                                </div>
                                                <span class="font-monospace fw-bold tracking-wider" style="font-size: 0.78rem;">
                                                    <?php echo e($siswa->rfid_code); ?>

                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-2 py-1.5 px-2 fw-medium" style="font-size: 0.75rem;">
                                                <i class="bi bi-keyboard me-1"></i>Manual Mode
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td>
                                        <div class="segmented-control">
                                            <div class="segmented-item">
                                                <input type="radio" class="btn-check input-status-radio" name="presensi[<?php echo e($siswa->id); ?>][status]" id="status_h_<?php echo e($siswa->id); ?>" value="Hadir" data-siswa-id="<?php echo e($siswa->id); ?>" <?php echo e($statusAwal == 'Hadir' ? 'checked' : ''); ?>>
                                                <label class="btn-segment seg-hadir" for="status_h_<?php echo e($siswa->id); ?>">Hadir</label>
                                            </div>
                                            <div class="segmented-item">
                                                <input type="radio" class="btn-check input-status-radio" name="presensi[<?php echo e($siswa->id); ?>][status]" id="status_s_<?php echo e($siswa->id); ?>" value="Sakit" data-siswa-id="<?php echo e($siswa->id); ?>" <?php echo e($statusAwal == 'Sakit' ? 'checked' : ''); ?>>
                                                <label class="btn-segment seg-sakit" for="status_s_<?php echo e($siswa->id); ?>">Sakit</label>
                                            </div>
                                            <div class="segmented-item">
                                                <input type="radio" class="btn-check input-status-radio" name="presensi[<?php echo e($siswa->id); ?>][status]" id="status_i_<?php echo e($siswa->id); ?>" value="Izin" data-siswa-id="<?php echo e($siswa->id); ?>" <?php echo e($statusAwal == 'Izin' ? 'checked' : ''); ?>>
                                                <label class="btn-segment seg-izin" for="status_i_<?php echo e($siswa->id); ?>">Izin</label>
                                            </div>
                                            <div class="segmented-item">
                                                <input type="radio" class="btn-check input-status-radio" name="presensi[<?php echo e($siswa->id); ?>][status]" id="status_a_<?php echo e($siswa->id); ?>" value="Alpa" data-siswa-id="<?php echo e($siswa->id); ?>" <?php echo e($statusAwal == 'Alpa' ? 'checked' : ''); ?>>
                                                <label class="btn-segment seg-alpa" for="status_a_<?php echo e($siswa->id); ?>">Alpa</label>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <input type="text" name="presensi[<?php echo e($siswa->id); ?>][keterangan]" class="form-control form-control-sm rounded-3 shadow-none border" value="<?php echo e($siswa->keterangan_hari_ini); ?>" placeholder="Contoh: Demam, Izin Keluarga" style="width:100%; max-width:240px; font-size: 0.82rem; padding: 6px 10px;">
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5 bg-light bg-opacity-25">
                                        <div class="py-4 opacity-75">
                                            <i class="bi bi-person-x fs-1 d-block mb-2 text-secondary"></i> Belum ada data entitas siswa yang terdaftar di ruang kelas ini.
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if(isset($semua_siswa) && $semua_siswa->count() > 0): ?>
                <div class="card-footer bg-white p-3 px-4 d-flex justify-content-between align-items-center border-top">
                    <div class="text-muted small fw-semibold">
                        Menampilkan <span id="count-terfilter"><?php echo e($semua_siswa->count()); ?></span> dari <span><?php echo e($semua_siswa->count()); ?></span> total keseluruhan siswa kelas
                    </div>
                </div>
                <?php endif; ?>
            </form>
        </div>

        
        <div class="modal fade" id="confirmPresensiModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                <div class="modal-content modal-saas">
                    <div class="modal-header modal-saas-header justify-content-center position-relative">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="bi bi-question-circle-fill fs-3"></i>
                        </div>
                        <button type="button" class="btn-close position-absolute" style="top: 20px; right: 20px;" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-saas-body text-center">
                        <h5 class="fw-bold text-dark mb-2">Simpan Data Presensi?</h5>
                        <p class="text-muted mb-0 small px-2">Seluruh perubahan log kehadiran berkala siswa akan langsung disimpan dan diperbarui ke dalam sistem data master.</p>
                    </div>
                    <div class="modal-footer modal-saas-footer d-flex gap-2">
                        <button type="button" class="btn btn-saas-secondary flex-grow-1 py-2 fw-semibold" data-bs-dismiss="modal">Tidak, Batal</button>
                        <!-- TOMBOL SUBMIT DENGAN ATRIBUT FORM TERKONEKSI KE FORM UTAMA -->
                        <button type="submit" form="formPresensi" class="btn btn-primary btn-saas-primary flex-grow-1 py-2 fw-semibold shadow-none">Ya, Simpan</button>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        
        
        <div class="card border-0 shadow-premium rounded-4 bg-white text-center py-5 px-4 d-flex align-items-center justify-content-center" style="min-height: 400px;">
            <div class="py-4" style="max-width: 480px;">
                <div class="mb-4 d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 86px; height: 86px;">
                    <i class="bi bi-journal-check text-primary" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2" style="font-size: 1.2rem; letter-spacing: -0.3px;">Formulir Lembar Presensi Belum Dimuat</h5>
                <p class="text-muted small lh-base mb-4">Untuk memulai melakukan rekap data presensi atau mengubah status log kehadiran, silakan pilih opsi **Ruang Kelas** dan tentukan **Tanggal Presensi** melalui panel kontrol filter atas.</p>
                <div class="d-flex justify-content-center gap-3 text-muted small border-top pt-4">
                    <div class="d-flex align-items-center gap-1.5" style="font-size: 0.78rem;">
                        <i class="bi bi-shield-check text-success"></i> Aman & Sinkron
                    </div>
                    <div class="d-flex align-items-center gap-1.5" style="font-size: 0.78rem;">
                        <i class="bi bi-lightning-charge-fill text-warning"></i> Real-time Database
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>


    <script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentStatusFilter = 'Semua';

        function applyFilterAndSearch() {
            let searchKeyword = document.getElementById('searchSiswa') ? document.getElementById('searchSiswa').value.toLowerCase() : '';
            let visibleCount = 0;

            document.querySelectorAll('.baris-siswa').forEach(function(row) {
                let searchTarget = row.querySelector('[data-search]').dataset.search;
                let statusAktif = row.getAttribute('data-status-aktif');

                let matchSearch = searchTarget.includes(searchKeyword);
                let matchStatus = (currentStatusFilter === 'Semua' || statusAktif === currentStatusFilter);

                if (matchSearch && matchStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            let labelCount = document.getElementById('count-terfilter');
            if(labelCount) labelCount.innerText = visibleCount;
        }

        if(document.getElementById('searchSiswa')) {
            document.getElementById('searchSiswa').addEventListener('keyup', applyFilterAndSearch);
        }

        document.querySelectorAll('.filter-status-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                document.querySelectorAll('.filter-status-btn').forEach(btn => {
                    btn.classList.remove('active', 'btn-dark');
                    btn.classList.add('btn-light', 'border', 'text-secondary');
                });
                
                this.classList.remove('btn-light', 'border', 'text-secondary');
                this.classList.add('active', 'btn-dark');

                currentStatusFilter = this.dataset.filter;
                applyFilterAndSearch();
            });
        });

        document.querySelectorAll('.input-status-radio').forEach(function(radio) {
            radio.addEventListener('change', function() {
                let parentRow = this.closest('.baris-siswa');
                parentRow.setAttribute('data-status-aktif', this.value);
                applyFilterAndSearch();
            });
        });
    });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/presensi/manual.blade.php ENDPATH**/ ?>