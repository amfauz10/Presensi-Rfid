<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Presensi - SDN Tengah 03</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --saas-bg: #0f172a;
            --saas-card-bg: #ffffff;
            --saas-border: #e2e8f0;
            --saas-text-main: #0f172a;
            --saas-text-muted: #64748b;
        }

        html, body {
            height: 100%;
            overflow: hidden; /* MENGUNCI DARI SCROLLBAR */
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
        }

        .card-terminal { 
            background: var(--saas-card-bg); 
            color: var(--saas-text-main); 
            border-radius: 18px; 
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .hidden-input { 
            position: absolute; 
            opacity: 0; 
            pointer-events: none; 
        }

        /* FOTO PAS PROPORSI */
        .student-avatar {
            width: 210px;      
            height: 210px;      
            object-fit: cover;
            border: 4px solid #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 16px -2px rgba(15, 23, 42, 0.15);
        }

        .pulse-icon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.85; }
            50% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); opacity: 0.85; }
        }

        .badge-terminal-status {
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            padding: 0.6rem 1.4rem !important;
            border-radius: 10px;
        }

        .bg-success-light {
            background-color: #f0fdf4 !important;
            color: #14532d !important;
            border: 1.5px solid #86efac !important;
        }

        .bg-warning-light {
            background-color: #fffbeb !important;
            color: #92400e !important;
            border: 1.5px solid #fde68a !important;
        }

        .bg-danger-light {
            background-color: #fef2f2 !important;
            color: #991b1b !important;
            border: 1.5px solid #fecaca !important;
        }

        .text-header-sub {
            color: #94a3b8 !important;
            font-weight: 500;
        }

        .btn-kembali {
            color: #475569 !important;
            font-weight: 600;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-9 col-md-11 text-center">
            
            <!-- HEADER INFO SEKOLAH -->
            <div class="mb-3">
                <h3 class="fw-bold text-white mb-0" style="font-size: 1.5rem;">Gerbang Presensi</h3>
                <div class="text-header-sub small mb-1">SD Negeri Tengah 03 Jakarta Timur</div>
                
                <!-- JAM & TANGGAL DIGITAL -->
                <div id="live-clock" class="fw-bold font-monospace" style="font-size: 2.85rem; color: #38bdf8; text-shadow: 0 4px 12px rgba(0,0,0,0.4); line-height: 1.1;">00:00:00</div>
                <div id="live-date" class="text-header-sub small fw-bold mt-1" style="letter-spacing: 0.05em; font-size: 0.8rem;">-</div>
            </div>

            <!-- MAIN TERMINAL SCREEN -->
            <div class="card card-terminal border-0">
                <div class="card-body p-4">
                    
                    <!-- DISPLAY ZONE -->
                    <div id="interactive-display-zone">
                        <div id="standby-zone" class="text-center py-2">
                            <div class="text-primary pulse-icon mb-2">
                                <i class="bi bi-card-image" style="font-size: 4.5rem; line-height: 1; color: #3b82f6;"></i>
                            </div>
                            <h5 class="fw-bold mb-1" style="font-size: 1.2rem; color: #0f172a;">Silakan Tempelkan Kartu RFID</h5>
                            <p class="mx-auto mb-3 small" style="max-width: 400px; color: #475569;">
                                Dekatkan kartu identitas murid atau atribut chip tap pintar pada perangkat pembaca (<em>card reader</em>).
                            </p>
                            <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 bg-light border rounded-pill font-monospace" style="font-size: 0.8rem; color: #334155; font-weight: 600;">
                                <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                                Menunggu Sensor RFID...
                            </div>
                        </div>
                    </div>

                    <!-- HIDDEN CAPTURE INTERFACE -->
                    <form action="{{ route('proses.tap') }}" method="POST" id="form-rfid">
                        @csrf
                        <input type="text" name="rfid_code" id="rfid_input" class="hidden-input" autofocus autocomplete="off">
                    </form>

                    <div class="mt-3 pt-2 border-top">
                        <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none btn-sm p-0 btn-kembali">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard Utama
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfidInput = document.getElementById('rfid_input');
    const displayZone = document.getElementById('interactive-display-zone');
    const formRfid = document.getElementById('form-rfid');
    
    function keepFocus() {
        rfidInput.focus();
    }
    keepFocus();
    document.addEventListener('click', keepFocus);
    window.addEventListener('focus', keepFocus);

    let isScanningLock = false;
    let timeoutId = null;

    function returnToStandby() {
        displayZone.style.opacity = '0';
        displayZone.style.transition = 'opacity 0.2s ease';
        
        setTimeout(function() {
            displayZone.innerHTML = `
                <div id="standby-zone" class="text-center py-2">
                    <div class="text-primary pulse-icon mb-2">
                        <i class="bi bi-card-image" style="font-size: 4.5rem; line-height: 1; color: #3b82f6;"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="font-size: 1.2rem; color: #0f172a;">Silakan Tempelkan Kartu RFID</h5>
                    <p class="mx-auto mb-3 small" style="max-width: 400px; color: #475569;">
                        Dekatkan kartu identitas murid atau atribut chip tap pintar pada perangkat pembaca (<em>card reader</em>).
                    </p>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 bg-light border rounded-pill font-monospace" style="font-size: 0.8rem; color: #334155; font-weight: 600;">
                        <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                        Menunggu Sensor RFID...
                    </div>
                </div>
            `;
            displayZone.style.opacity = '1';
            rfidInput.value = '';
            isScanningLock = false;
            keepFocus();
        }, 200);
    }

    rfidInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            if (isScanningLock) return;
            
            const kode = this.value.trim();
            if (kode.length < 4) return;
            
            isScanningLock = true;
            
            if (timeoutId) clearTimeout(timeoutId);

            const formData = new FormData(formRfid);
            formData.set('rfid_code', kode);

            fetch(formRfid.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let avatarHtml = data.foto 
                        ? `<img src="/storage/${data.foto}" class="student-avatar mx-auto d-block" alt="Foto ${data.nama}">`
                        : `<div class="student-avatar bg-light d-flex align-items-center justify-content-center mx-auto border"><i class="bi bi-person-fill text-secondary" style="font-size: 6rem;"></i></div>`;
                    
                    let alertClass = data.status === 'Terlambat' ? 'bg-warning-light' : 'bg-success-light';
                    let alertIcon = data.status === 'Terlambat' ? 'bi-clock-history' : 'bi-check-circle-fill';
                    let alertTitle = data.status === 'Terlambat' ? 'Presensi Diterima (Terlambat)' : 'Presensi Berhasil';

                    displayZone.innerHTML = `
                        <div class="text-center py-1">
                            ${avatarHtml}
                            <h4 class="mt-2 fw-bold mb-0" style="font-size: 1.45rem; color: #0f172a !important;">${data.nama}</h4>
                            <span class="fw-bold d-block mb-2" style="font-size: 1rem; color: #334155 !important;">Kelas ${data.kelas}</span>
                            
                            <div class="badge-terminal-status ${alertClass} d-inline-flex align-items-center justify-content-center gap-2 mx-auto mb-2" style="max-width: 400px;">
                                <i class="bi ${alertIcon} fs-5"></i> ${alertTitle}
                            </div>
                            
                            <div class="fw-bold font-monospace" style="font-size: 0.9rem; color: #1e293b !important;">
                                Tercatat pada pukul <span style="color: #0284c7; font-weight: 800;">${data.jam} WIB</span>
                            </div>
                        </div>
                    `;
                } else {
                    displayZone.innerHTML = `
                        <div class="text-center py-2">
                            <div class="mx-auto text-danger bg-danger-light rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 70px; height: 70px;">
                                <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-1" style="font-size: 1.2rem;">Akses Ditolak</h5>
                            <div class="badge-terminal-status bg-danger-light d-inline-block m-0">
                                ${data.message}
                            </div>
                        </div>
                    `;
                }
                
                timeoutId = setTimeout(returnToStandby, 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                isScanningLock = false;
                rfidInput.value = '';
            });
        }
    });

    function updateClock() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        
        document.getElementById('live-clock').innerText = now.toLocaleTimeString('id-ID');
        document.getElementById('live-date').innerText = now.toLocaleDateString('id-ID', options).toUpperCase();
    }
    setInterval(updateClock, 1000);
    updateClock();
});
</script>
</body>
</html>