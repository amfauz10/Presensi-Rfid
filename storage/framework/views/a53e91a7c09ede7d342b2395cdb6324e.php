
    <style>
        /* Polishing UI & Premium Styling */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        .main-content {
            padding: 32px;
        }
        .card-custom {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 24px;
        }
        .card-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(148, 163, 184, 0.08) !important;
        }
        .shadow-premium {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02) !important;
        }
        .progress {
            background-color: #f1f5f9;
            border-radius: 8px;
            height: 8px;
        }
        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #64748b;
            padding: 14px 20px !important;
            background-color: #f8fafc !important;
            border-bottom: 2px solid #edeff1 !important;
        }
        .table-custom td {
            padding: 14px 20px !important;
            border-color: #f1f5f9;
        }
        .bg-primary-light { background-color: rgba(37, 99, 243, 0.08); color: #2563eb; }
        .bg-success-light { background-color: rgba(22, 163, 74, 0.08); color: #16a34a; }
        .bg-danger-light { background-color: rgba(220, 38, 38, 0.08); color: #dc2626; }
        .bg-secondary-light { background-color: rgba(100, 116, 139, 0.08); color: #64748b; }
    </style>


    
    <?php if(session('sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 mb-4 p-3 d-flex align-items-center"
             style="background:#dcfce7; color:#15803d;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="fw-medium"><?php echo e(session('sukses')); ?></div>
            <button class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-4 mb-4 p-3 d-flex align-items-center"
             style="background:#fef2f2; color:#b91c1c;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div class="fw-medium"><?php echo e(session('error')); ?></div>
            <button class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    
    <div class="mb-4 d-flex justify-content-between align-items-center border-bottom border-light pb-3">
        <div>
            <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -0.5px;">
                Selamat Datang, <?php echo e(auth()->user()->name); ?>

            </h3>
            <p class="text-muted m-0 small mt-1">
                Wali Kelas <?php echo e(auth()->user()->kelas->nama_kelas); ?>

            </p>
        </div>
        <span class="badge bg-success px-3 py-2 rounded-pill fw-semibold small" style="box-shadow: 0 4px 12px rgba(22, 163, 74, 0.15) !important;">
            <i class="bi bi-mortarboard me-1"></i> Kelas <?php echo e(auth()->user()->kelas->nama_kelas); ?>

        </span>
    </div>

    
    <div class="row g-3 mb-4 text-start">
        <!-- TOTAL SISWA -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small d-block mb-1 fw-bold text-uppercase tracking-wider" style="font-size: 0.7rem;">Total Siswa</span>
                        <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -0.5px;"><?php echo e($totalSiswa); ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-2 px-3 rounded-3 text-primary">
                        <i class="bi bi-people-fill fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-primary" style="height: 3px;"></div>
            </div>
        </div>

        <!-- HADIR HARI INI -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-success small d-block mb-1 fw-bold text-uppercase tracking-wider" style="font-size: 0.7rem;">Hadir Hari Ini</span>
                        <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -0.5px;"><?php echo e($siswaHadirHariIni); ?></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-2 px-3 rounded-3 text-success">
                        <i class="bi bi-check-circle-fill fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-success" style="height: 3px;"></div>
            </div>
        </div>

        <!-- TERLAMBAT -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-danger small d-block mb-1 fw-bold text-uppercase tracking-wider" style="font-size: 0.7rem;">Terlambat</span>
                        <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -0.5px;"><?php echo e($siswaTerlambat); ?></h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-2 px-3 rounded-3 text-danger">
                        <i class="bi bi-clock-fill fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-danger" style="height: 3px;"></div>
            </div>
        </div>

        <!-- BELUM HADIR -->
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-premium rounded-4 bg-white p-3 h-100 position-relative overflow-hidden">
                <div class="card-body p-0 d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small d-block mb-1 fw-bold text-uppercase tracking-wider" style="font-size: 0.7rem;">Belum Hadir</span>
                        <h3 class="fw-bold text-dark mb-0 font-monospace fs-2" style="letter-spacing: -0.5px;"><?php echo e($siswaBelumHadir); ?></h3>
                    </div>
                    <div class="bg-secondary bg-opacity-10 p-2 px-3 rounded-3 text-secondary">
                        <i class="bi bi-person-x-fill fs-4 lh-1"></i>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100 bg-secondary opacity-50" style="height: 3px;"></div>
            </div>
        </div>
    </div>

    
    <div class="card-custom mb-4 text-start position-relative overflow-hidden rounded-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold m-0 text-dark" style="font-size: 1rem; letter-spacing: -0.3px;">Visualisasi Tren Kehadiran</h5>
                <small class="text-muted">Perbandingan grafik rekapitulasi masuk siswa</small>
            </div>
            <div style="width: 150px;">
                <select id="filterGrafik" class="form-select form-select-sm rounded-3 p-2 fw-semibold shadow-none border-light-subtle" style="cursor: pointer; font-size: 0.85rem;">
                    <option value="mingguan" selected>📅 Tren Mingguan</option>
                    <option value="bulanan">📊 Tren Bulanan</option>
                </select>
            </div>
        </div>
        <div style="position: relative; height:200px; width:100%">
            <canvas id="grafikMingguan"></canvas>
        </div>
    </div>

    <div class="row g-4 text-start mb-4">
        
        <div class="col-lg-5">
            <div class="card-custom h-100 p-0 overflow-hidden rounded-4">
                <div class="p-4 border-bottom bg-white">
                    <h5 class="fw-bold m-0" style="font-size: 1rem; letter-spacing: -0.3px;">Rincian Status Hari Ini</h5>
                </div>
                <div class="p-4">
                    <?php
                        $rincianStatus = [
                            ['label' => 'Hadir',       'jumlah' => $siswaHadirHariIni, 'warna' => '#16a34a'],
                            ['label' => 'Terlambat',   'jumlah' => $siswaTerlambat,    'warna' => '#f59e0b'],
                            ['label' => 'Sakit',       'jumlah' => $siswaSakit,        'warna' => '#2563eb'],
                            ['label' => 'Izin',        'jumlah' => $siswaIzin,         'warna' => '#64748b'],
                            ['label' => 'Belum Hadir', 'jumlah' => $siswaBelumHadir,   'warna' => '#dc2626'],
                        ];
                    ?>
                    <?php $__currentLoopData = $rincianStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="d-flex justify-content-between align-items-center <?php echo e(!$loop->last ? 'mb-3' : ''); ?>">
                            <span class="text-dark" style="font-size: 0.88rem;">
                                <span class="d-inline-block rounded-circle me-2" style="width:8px; height:8px; background-color:<?php echo e($r['warna']); ?>;"></span>
                                <?php echo e($r['label']); ?>

                            </span>
                            <span class="fw-bold font-monospace" style="font-size: 0.9rem;"><?php echo e($r['jumlah']); ?></span>
                        </div>
                        <div class="progress mb-3" style="height:6px; background:#f1f5f9; border-radius:20px;">
                            <div class="progress-bar" style="width:<?php echo e($totalSiswa ? round(($r['jumlah'] / $totalSiswa) * 100) : 0); ?>%; background-color:<?php echo e($r['warna']); ?>; border-radius:20px;"></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        
        <div class="col-lg-7">
            <div class="card-custom h-100 p-0 overflow-hidden rounded-4">
                <div class="p-4 border-bottom bg-white">
                    <h5 class="fw-bold m-0" style="font-size: 1rem; letter-spacing: -0.3px;">Aktivitas Hari Ini</h5>
                </div>
                <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-hover table-custom align-middle m-0">
                        <tbody>
                            <?php if(isset($riwayat_presensi) && $riwayat_presensi->count() > 0): ?>
                                <?php $__currentLoopData = $riwayat_presensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-muted font-monospace small" style="width: 90px;">
                                        <i class="bi bi-clock me-1 text-secondary"></i><?php echo e(\Carbon\Carbon::parse($log->waktu_masuk)->format('H:i:s')); ?>

                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark d-block mb-0" style="font-size: 0.88rem;"><?php echo e($log->siswa->nama_siswa ?? 'Siswa'); ?></span>
                                        <small class="text-muted" style="font-size: 0.75rem;">Kelas <?php echo e($log->siswa->kelas->nama_kelas ?? ''); ?></small>
                                    </td>
                                    <td class="text-end" style="width: 120px;">
                                        <span class="badge <?php echo e($log->status == 'Hadir' ? 'bg-success-light text-success' : 'bg-danger-light text-danger'); ?> px-2.5 py-1.5 rounded-3 fw-bold small">
                                            <?php echo e($log->status); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td class="text-center text-muted py-5">
                                        <div class="py-4 opacity-75">
                                            <i class="bi bi-card-text text-secondary display-6 mb-3 d-block"></i>
                                            <p class="fw-bold text-dark m-0 mb-1" style="font-size: 0.95rem;">Belum Ada Aktivitas Kartu</p>
                                            <small class="text-muted">Silakan lakukan tap kartu RFID pada *reader* untuk mencatat presensi hari ini.</small>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<form id="form-rfid" action="<?php echo e(route('presensi.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="text" id="input-rfid" name="rfid_code" autofocus autocomplete="off" style="position: absolute; opacity: 0; pointer-events: none;">
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // --- PROSES CONFIG RENDERING DATA CHART ---
        const canvasMingguan = document.getElementById('grafikMingguan');
        if (canvasMingguan) {
            const ctx = canvasMingguan.getContext('2d');
            
            const dataMingguan = <?php echo e(\Illuminate\Support\Js::from($data_grafik_mingguan ?? [0,0,0,0,0])); ?>;
            const labelsMingguan = <?php echo e(\Illuminate\Support\Js::from($label_grafik_mingguan ?? [])); ?>;
            
            const dataBulanan = <?php echo e(\Illuminate\Support\Js::from($data_grafik_bulanan ?? [])); ?>;
            const labelsBulanan = <?php echo e(\Illuminate\Support\Js::from($labels_bulanan ?? [])); ?>;

            // Inisialisasi awal Grafik
            const myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labelsMingguan,
                    datasets: [{
                        label: 'Siswa Hadir',
                        data: dataMingguan,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.04)',
                        borderWidth: 3,
                        pointBackgroundColor: '#2563eb',
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { 
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true,
                            min: 0,
                            grid: {
                                color: 'rgba(241, 245, 249, 1)'
                            },
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    }
                }
            });

            // LOGIKA INTERAKTIF SWITCHING DROPDOWN
            const dropdownFilter = document.getElementById('filterGrafik');
            if (dropdownFilter) {
                dropdownFilter.addEventListener('change', function() {
                    if (this.value === 'bulanan') {
                        myChart.data.labels = labelsBulanan;
                        myChart.data.datasets[0].data = dataBulanan;
                    } else {
                        myChart.data.labels = labelsMingguan;
                        myChart.data.datasets[0].data = dataMingguan;
                    }
                    myChart.update();
                });
            }
        }

        // --- AUTO FOCUS INPUT RFID BACKGROUND ---
        const rfidInput = document.getElementById('input-rfid');
        function focusTanpaScroll() {
            if (rfidInput && !document.querySelector('.modal.show')) {
                var x = window.scrollX, y = window.scrollY;
                rfidInput.focus({ preventScroll: true });
                window.scrollTo(x, y);
            }
        }
        focusTanpaScroll();
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.modal') && !['INPUT', 'SELECT', 'TEXTAREA', 'BUTTON', 'A'].includes(e.target.tagName)) {
                focusTanpaScroll();
            }
        });
        if (rfidInput) {
            rfidInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('form-rfid').submit();
                }
            });
        }
    });
</script><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/dashboard/wali.blade.php ENDPATH**/ ?>