<?php $__env->startSection('title', 'Data Siswa Per Kelas - SDN Tengah 03'); ?>

<?php $__env->startSection('content'); ?>

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* --- POLISHING: UPGRADE HOVER & TRANSITION PREMIUM SELARAS --- */
        .compact-class-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px; 
            padding: 18px 20px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        /* Hover efek naik 4px dengan tema warna biru/indigo premium */
        .compact-class-link:hover {
            border-color: #0d6efd;
            background-color: #ffffff;
            transform: translateY(-4px);
            box-shadow: 0 12px 25px -8px rgba(13, 110, 253, 0.12) !important;
        }

        .class-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }

        .compact-class-link:hover .class-avatar {
            transform: scale(1.05);
        }

        .class-name {
            font-size: 0.98rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .student-count-text {
            font-size: 0.82rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Keterangan interaktif di bawah jumlah siswa */
        .action-text {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
            display: block;
            margin-top: 4px;
            transition: color 0.2s ease;
        }

        .compact-class-link:hover .action-text {
            color: #0d6efd;
        }

        /* Chevron bergerak lebih smooth saat hover */
        .chevron-arrow {
            font-size: 1.1rem;
            color: #94a3b8;
            transition: all 0.25s ease;
        }

        .compact-class-link:hover .chevron-arrow {
            color: #0d6efd;
            transform: translateX(4px);
        }

        /* Search Bar & Stats Styling */
        .search-input-group {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 4px 8px;
            transition: all 0.2s ease;
            max-width: 340px;
        }

        .search-input-group:focus-within {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
        }

        .shadow-premium {
            box-shadow: 0 8px 24px rgba(149, 157, 165, 0.05);
        }
    </style>

    
    <?php if(session('sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 p-3 mb-4 d-flex align-items-center text-start shadow-sm" role="alert" style="background:#dcfce7; color:#15803d;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="small fw-medium"><?php echo e(session('sukses')); ?></div>
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-3 border-bottom border-light text-start">
        <div>
            <h3 class="fw-bold m-0" style="letter-spacing: -0.5px; color: #1e293b;">Data Siswa</h3>
            <p class="text-muted m-0 mt-1 small" style="max-width: 600px;">
                Kelola data siswa berdasarkan kelas. Pilih salah satu ruang kelas di bawah untuk melakukan penambahan, pembaruan data, serta manajemen kartu RFID siswa.
            </p>
        </div>
        
        
        <?php if($daftar_kelas->isNotEmpty()): ?>
            <div class="d-flex gap-3 bg-white p-2 px-3 rounded-4 border border-light shadow-premium">
                <div class="text-center border-end pe-3 text-start">
                    <span class="text-muted d-block" style="font-size: 0.72rem; font-weight: 600; text-uppercase; letter-spacing: 0.3px;">Total Kelas</span>
                    <strong class="text-dark fs-5 fw-bold font-monospace"><?php echo e($daftar_kelas->count()); ?></strong>
                </div>
                <div class="text-center text-start">
                    <span class="text-muted d-block" style="font-size: 0.72rem; font-weight: 600; text-uppercase; letter-spacing: 0.3px;">Siswa Aktif</span>
                    <strong class="text-primary fs-5 fw-bold font-monospace"><?php echo e($daftar_kelas->sum('siswa_count')); ?></strong>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if($daftar_kelas->isNotEmpty()): ?>
        <div class="d-flex justify-content-start mb-4">
            <div class="input-group search-input-group shadow-none">
                <span class="input-group-text bg-transparent border-0 text-muted px-2">
                    <i class="bi bi-search small text-muted"></i>
                </span>
                <input type="text" id="searchKelas" class="form-control bg-transparent border-0 form-control-sm shadow-none ps-1" style="font-size: 0.9rem;" placeholder="Ketik nama ruang kelas...">
            </div>
        </div>
    <?php endif; ?>

    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-3 text-start" id="kelasGrid">

        <?php $__empty_1 = true; $__currentLoopData = $daftar_kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                // Mengubah warna pastel agar selaras dengan skema warna sistem presensi
                $iconStyle = match($index % 4) {
                    0 => 'background-color: rgba(13, 110, 253, 0.1); color: #0d6efd;', // Blue
                    1 => 'background-color: rgba(25, 135, 84, 0.1); color: #198754;',  // Green
                    2 => 'background-color: rgba(255, 193, 7, 0.1); color: #b45309;', // Warning/Orange
                    default => 'background-color: rgba(108, 117, 125, 0.1); color: #495057;' // Muted Dark
                };
            ?>

            <div class="col class-card-item" data-nama="<?php echo e(strtolower($kls->nama_kelas)); ?>">
                <a href="<?php echo e(route('kelas.show', $kls->id)); ?>" class="compact-class-link text-decoration-none">
                    
                    <div class="d-flex align-items-center gap-3 text-truncate">
                        <div class="class-avatar" style="<?php echo $iconStyle; ?>">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        
                        <div class="text-truncate">
                            <h6 class="class-name text-truncate">Kelas <?php echo e($kls->nama_kelas); ?></h6>
                            <span class="student-count-text">
                                <?php echo e($kls->siswa_count); ?> Siswa Terdaftar
                            </span>
                            <small class="action-text">Buka Kelas <i class="bi bi-arrow-right small" style="font-size: 0.7rem;"></i></small>
                        </div>
                    </div>

                    <div class="chevron-arrow">
                        <i class="bi bi-chevron-right"></i>
                    </div>

                </a>
            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
            <div class="col-12 w-100" id="emptyStateContainer">
                <div class="card border-0 rounded-4 bg-white shadow-premium border border-light">
                    <div class="card-body text-center py-5 text-muted">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                            <i class="bi bi-folder-x text-secondary fs-3"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Belum Ada Data Ruang Kelas</h6>
                        <p class="text-muted small m-0">Silakan konfigurasikan struktur data master kelas terlebih dahulu pada modul administrasi utama.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="col-12 w-100 d-none" id="searchEmptyState">
            <div class="card border-0 rounded-4 bg-white shadow-premium">
                <div class="card-body text-center py-5 text-muted">
                    <div class="p-3 bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-search text-secondary fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Ruang Kelas Tidak Ditemukan</h6>
                    <p class="text-muted small m-0">Tidak ada data kelas yang cocok dengan kata kunci pencarian Anda.</p>
                </div>
            </div>
        </div>

    </div>


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
                        card.classList.add('d-none'); // FIX: Mengubah dari card.addClassList.add menjadi card.classList.add
                    }
                });

                // Tampilkan pesan kosong jika hasil pencarian tidak ada
                if (!hasVisibleCard && filterValue !== '') {
                    searchEmptyState.classList.remove('d-none');
                } else {
                    searchEmptyState.classList.add('d-none');
                }
            });
        }
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/kelas/index.blade.php ENDPATH**/ ?>