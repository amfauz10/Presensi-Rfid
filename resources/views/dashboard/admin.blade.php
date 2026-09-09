{{-- =========================
         DASHBOARD ADMIN
    ========================== --}}
    <style>
        /* Base SaaS Utility & Variables */
        :root {
            --saas-bg: #f8fafc;
            --saas-card-bg: #ffffff;
            --saas-border: #e2e8f0;
            --saas-text-main: #0f172a;
            --saas-text-muted: #64748b;
        }

        body {
            background-color: var(--saas-bg);
            color: var(--saas-text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .main-content {
            padding: 24px;
        }

        /* Modern Card SaaS Style */
        .card-custom {
            background: var(--saas-card-bg);
            border: 1px solid var(--saas-border);
            border-radius: 12px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.02), 0 1px 2px -1px rgba(0, 0, 0, 0.02);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            padding: 20px;
        }
        
        .card-custom:hover {
            border-color: #cbd5e1;
        }

        /* Stat Cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid var(--saas-border);
            border-radius: 12px;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s ease;
        }

        .stat-card:hover {
            border-color: #cbd5e1;
        }

        .stat-card .stat-label {
            font-size: 0.725rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--saas-text-muted);
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--saas-text-main);
            line-height: 1.2;
        }

        .stat-card .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Custom Form Controls */
        .saas-form-select {
            border: 1px solid var(--saas-border);
            border-radius: 8px;
            padding: 0.45rem 0.75rem;
            font-size: 0.85rem;
            color: var(--saas-text-main);
            background-color: #ffffff;
            transition: all 0.15s ease-in-out;
        }

        .saas-form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
            outline: none;
        }

        /* Progress Bar Modern */
        .progress {
            background-color: #f1f5f9;
            border-radius: 20px;
            height: 8px;
            overflow: hidden;
        }

        /* Table SaaS Style */
        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.725rem;
            letter-spacing: 0.05em;
            color: #475569;
            padding: 0.85rem 1rem !important;
            background-color: #f8fafc !important;
            border-bottom: 1px solid var(--saas-border) !important;
        }

        .table-custom td {
            padding: 0.85rem 1rem !important;
            border-bottom: 1px solid #f1f5f9 !important;
            vertical-align: middle;
            font-size: 0.875rem;
        }

        /* SaaS Badges */
        .bg-primary-light { background-color: #eff6ff; color: #2563eb; }
        .bg-success-light { background-color: #f0fdf4; color: #166534; }
        .bg-danger-light { background-color: #fef2f2; color: #991b1b; }
        .bg-secondary-light { background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
    </style>

    {{-- ALERT BERHASIL --}}
    @if(session('sukses'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0 !important;">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="fw-medium small">{{ session('sukses') }}</div>
            <button class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ALERT GAGAL --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 border-0 text-start d-flex align-items-center p-3 shadow-sm" role="alert" style="background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca !important;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div class="fw-medium small">{{ session('error') }}</div>
            <button class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 1. HEADER DASHBOARD --}}
    <div class="mb-4 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 border-bottom border-light pb-3 text-start">
        <div>
            <h3 class="fw-bold m-0 text-dark tracking-tight" style="font-size: 1.5rem;">
                @if(auth()->user()->role == 'admin')
                    Selamat Datang Kembali, Admin
                @else
                    Selamat Datang Kembali, Wali Kelas
                @endif
            </h3>
            <p class="text-muted m-0 small mt-1">Pantau aktivitas presensi siswa SDN Tengah 03.</p>
        </div>
        <span class="badge bg-white text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold font-monospace small d-inline-flex align-items-center shadow-sm">
            <span class="d-inline-block rounded-circle bg-primary me-2" style="width: 6px; height: 6px;"></span> RFID System Active
        </span>
    </div>

    {{-- 2. CARD STATISTIK --}}
    <div class="row g-3 mb-4 text-start">
        <!-- TOTAL SISWA -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Total Siswa</div>
                        <div class="stat-value">{{ $totalSiswa }}</div>
                    </div>
                    <div class="stat-icon" style="background: #eff6ff; color: #2563eb;">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- HADIR HARI INI -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #166534;">Hadir Hari Ini</div>
                        <div class="stat-value">{{ $siswaHadirHariIni }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- TERLAMBAT -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label" style="color: #991b1b;">Terlambat</div>
                        <div class="stat-value">{{ $siswaTerlambat }}</div>
                    </div>
                    <div class="stat-icon" style="background: #fef2f2; color: #dc2626;">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- BELUM HADIR -->
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-label">Belum Hadir</div>
                        <div class="stat-value">{{ $siswaBelumHadir }}</div>
                    </div>
                    <div class="stat-icon" style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. PANEL GRAFIK --}}
    <div class="row g-4 mb-4">
        {{-- GRAFIK TREN KEHADIRAN --}}
        <div class="col-12">
            <div class="card-custom text-start position-relative overflow-hidden">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                    <div>
                        <h5 class="fw-bold m-0 text-dark" style="font-size: 0.95rem;">Tren Kehadiran</h5>
                        <small class="text-muted">Rasio hadir &amp; terlambat siswa per hari</small>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <div class="d-flex align-items-center gap-3 me-2">
                            <span class="d-flex align-items-center gap-1.5" style="font-size:0.75rem; color:#64748b; font-weight:600;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#3b82f6;display:inline-block;"></span> Hadir
                            </span>
                            <span class="d-flex align-items-center gap-1.5" style="font-size:0.75rem; color:#64748b; font-weight:600;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#f59e0b;display:inline-block;"></span> Terlambat
                            </span>
                        </div>
                        <select id="filterGrafik" class="saas-form-select fw-medium shadow-none" style="cursor:pointer; width:135px;">
                            <option value="mingguan" selected>📅 Mingguan</option>
                            <option value="bulanan">📊 Bulanan</option>
                        </select>
                    </div>
                </div>
                <div style="position:relative;height:260px;width:100%">
                    <canvas id="grafikMingguan"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 text-start mb-4">
        {{-- 4. KEHADIRAN PER KELAS --}}
        <div class="col-lg-5">
            <div class="card-custom h-100 p-0 overflow-hidden">
                <div class="p-3 px-4 border-bottom bg-white">
                    <h5 class="fw-bold m-0 text-dark" style="font-size: 0.95rem;">Kehadiran per Kelas</h5>
                </div>
                <div class="p-4" style="max-height:380px; overflow-y:auto;">
                    @forelse($daftar_kelas as $kelasStats)
                        @php
                            $hadir = $kelasStats->hadir_count ?? 0;
                            $total = $kelasStats->siswa_count ?? 0;
                            $persen = $total > 0 ? round(($hadir / $total) * 100) : 0;
                        @endphp
                        <div class="mb-3.5">
                            <div class="d-flex justify-content-between align-items-center mb-1.5">
                                <span class="fw-semibold text-dark" style="font-size: 0.875rem;">Kelas {{ $kelasStats->nama_kelas }}</span>
                                <span class="badge text-primary bg-primary-light px-2.5 py-1 rounded-pill font-monospace" style="font-weight:600; font-size:11px;">
                                    {{ $hadir }} / {{ $total }} Murid
                                </span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $persen }}%;" aria-valuenow="{{ $persen }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-grid-3x3-gap fs-2 mb-2 d-block opacity-50"></i>
                            <p class="mb-0 small fw-medium">Belum ada data statistik kelas.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- 5. AKTIVITAS TAP HARI INI --}}
        <div class="col-lg-7">
            <div class="card-custom h-100 p-0 overflow-hidden">
                <div class="p-3 px-4 border-bottom bg-white">
                    <h5 class="fw-bold m-0 text-dark" style="font-size: 0.95rem;">Aktivitas Hari Ini</h5>
                </div>
                <div class="table-responsive" style="max-height: 380px; overflow-y: auto;">
                    <table class="table table-hover table-custom align-middle m-0">
                        <tbody>
                            @if(isset($riwayat_presensi) && $riwayat_presensi->count() > 0)
                                @foreach($riwayat_presensi as $log)
                                <tr>
                                    <td class="text-muted font-monospace small" style="width: 100px;">
                                        <i class="bi bi-clock me-1 text-secondary"></i>{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark d-block mb-0" style="font-size: 0.875rem;">{{ $log->siswa->nama_siswa ?? 'Siswa' }}</span>
                                        <small class="text-muted" style="font-size: 0.75rem;">Kelas {{ $log->siswa->kelas->nama_kelas ?? '' }}</small>
                                    </td>
                                    <td class="text-end" style="width: 120px;">
                                        <span class="badge {{ $log->status == 'Hadir' ? 'bg-success-light' : 'bg-danger-light' }} px-2.5 py-1 rounded-2 fw-semibold small">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center text-muted py-5">
                                        <div class="py-3">
                                            <i class="bi bi-card-text text-slate-300 fs-1 mb-2 d-block"></i>
                                            <p class="fw-bold text-dark m-0 mb-1" style="font-size: 0.9rem;">Belum Ada Aktivitas</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

{{-- JAVASCRIPT LIBRARIES & RFID BACKGROUND LISTENER --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

<form id="form-rfid" action="{{ route('presensi.store') }}" method="POST">
    @csrf
    <input type="text" id="input-rfid" name="rfid_code" autofocus autocomplete="off" style="position: absolute; opacity: 0; pointer-events: none;">
</form>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // ====================================================
        // GRAFIK 1: TREN KEHADIRAN (Stacked Bar Chart — v1.1)
        // ====================================================
        const canvasMingguan = document.getElementById('grafikMingguan');
        if (canvasMingguan && typeof Chart === 'undefined') {
            console.error('Chart.js gagal dimuat (cek koneksi internet) — grafik tren tidak bisa ditampilkan.');
        }
        if (canvasMingguan && typeof Chart !== 'undefined') {
            const ctx = canvasMingguan.getContext('2d');

            const dataMingguan          = {{ \Illuminate\Support\Js::from($data_grafik_mingguan ?? [0,0,0,0,0]) }};
            const labelsMingguan        = {{ \Illuminate\Support\Js::from($label_grafik_mingguan ?? []) }};
            const dataBulanan           = {{ \Illuminate\Support\Js::from($data_grafik_bulanan ?? []) }};
            const labelsBulanan         = {{ \Illuminate\Support\Js::from($labels_bulanan ?? []) }};
            const dataTerlambatMingguan = {{ \Illuminate\Support\Js::from($data_terlambat_mingguan ?? array_fill(0, count($label_grafik_mingguan ?? []), 0)) }};
            const dataTerlambatBulanan  = {{ \Illuminate\Support\Js::from($data_terlambat_bulanan ?? array_fill(0, count($labels_bulanan ?? []), 0)) }};

            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsMingguan,
                    datasets: [
                        {
                            label: 'Hadir',
                            data: dataMingguan,
                            backgroundColor: 'rgba(59,130,246,0.85)',
                            hoverBackgroundColor: 'rgba(37,99,235,1)',
                            borderRadius: 4,
                            borderSkipped: 'bottom'
                        },
                        {
                            label: 'Terlambat',
                            data: dataTerlambatMingguan,
                            backgroundColor: 'rgba(245,158,11,0.85)',
                            hoverBackgroundColor: 'rgba(217,119,6,1)',
                            borderRadius: 4,
                            borderSkipped: 'bottom'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    animation: { duration: 800, easing: 'easeInOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleColor: '#94a3b8',
                            bodyColor: '#f1f5f9',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 11, weight: '600', family: "'Plus Jakarta Sans', sans-serif" },
                            bodyFont: { size: 12, weight: '700', family: "'Plus Jakarta Sans', sans-serif" },
                            displayColors: true,
                            boxPadding: 4,
                            callbacks: {
                                label: function(ctx) {
                                    return ` ${ctx.dataset.label}: ${ctx.parsed.y} siswa`;
                                },
                                footer: function(items) {
                                    const total = items.reduce((s, i) => s + i.parsed.y, 0);
                                    return `Total masuk: ${total} siswa`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                            grid: { display: false },
                            border: { display: false },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 11, family: "'Plus Jakarta Sans', sans-serif", weight: '500' }
                            }
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true,
                            border: { display: false, dash: [4,4] },
                            grid: { color: 'rgba(226,232,240,0.7)' },
                            ticks: {
                                color: '#94a3b8',
                                font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" },
                                precision: 0,
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // SWITCHING DROPDOWN
            const dropdownFilter = document.getElementById('filterGrafik');
            if (dropdownFilter) {
                dropdownFilter.addEventListener('change', function() {
                    if (this.value === 'bulanan') {
                        myChart.data.labels = labelsBulanan;
                        myChart.data.datasets[0].data = dataBulanan;
                        myChart.data.datasets[1].data = dataTerlambatBulanan;
                    } else {
                        myChart.data.labels = labelsMingguan;
                        myChart.data.datasets[0].data = dataMingguan;
                        myChart.data.datasets[1].data = dataTerlambatMingguan;
                    }
                    myChart.update('active');
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
</script>