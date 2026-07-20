<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terminal Presensi - SDN Tengah 03</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
            color: white;
            min-height: 100vh;
            padding: 20px 0; 
        }
        .card-terminal { 
            background: #ffffff; 
            color: #334155; 
            border-radius: 1.25rem; 
        }
        .hidden-input { 
            position: absolute; 
            opacity: 0; 
            pointer-events: none; 
        }
        .student-avatar {
            width: 160px;
            height: 160px;
            object-fit: cover;
            border: 4px solid #f1f5f9;
        }
        .pulse-icon {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.9; }
            50% { transform: scale(1.06); opacity: 1; }
            100% { transform: scale(1); opacity: 0.9; }
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9 text-center">
            
            <!-- HEADER INFO SEKOLAH -->
            <div class="mb-4">
                <h1 class="fw-bold text-white mb-1" style="letter-spacing: 0.5px;">Gerbang Presensi</h1>
                <h5 class="text-white-50 fw-normal">SD Negeri Tengah 03 Jakarta Timur</h5>
                
                <!-- JAM & TANGGAL DIGITAL -->
                <div id="live-clock" class="fw-bold my-2" style="font-size: 3.5rem; text-shadow: 0 4px 12px rgba(0,0,0,0.3); color: #60a5fa;">00:00:00</div>
                <div id="live-date" class="text-white-50 small fw-medium" style="letter-spacing: 1px;">-</div>
            </div>

            <!-- MAIN TERMINAL SCREEN -->
            <div class="card card-terminal shadow-2xl border-0">
                <div class="card-body p-5">
                    
                    <!-- DISPLAY ZONE (Diubah dinamis penuh via AJAX JS) -->
                    <div id="interactive-display-zone">
                        <div id="standby-zone" class="text-center py-4">
                            <div class="text-primary pulse-icon mb-4">
                                <i class="bi bi-card-image" style="font-size: 6rem; line-height: 1;"></i>
                            </div>
                            <h3 class="fw-bold text-slate-800 mb-2">Silakan Tempelkan Kartu RFID</h3>
                            <p class="text-muted mx-auto mb-4" style="max-width: 380px;">
                                Dekatkan kartu identitas murid atau atribut chip tap pintar pada perangkat pembaca (*card reader*).
                            </p>
                            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 bg-light border rounded-pill font-monospace text-secondary shadow-inner" style="font-size: 0.9rem;">
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

                    <div class="mt-4 pt-3 border-top">
                        <a href="{{ route('dashboard') }}" class="btn btn-link text-decoration-none text-secondary btn-sm fw-medium">
                            <i class="bi bi-arrow-left-short fs-5 align-middle"></i> Kembali ke Dashboard Utama
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
    
    // OPTIMASI 4: Hilangkan polling interval, ganti ke event listener window focus & document click yang ringan
    function keepFocus() {
        rfidInput.focus();
    }
    keepFocus();
    document.addEventListener('click', keepFocus);
    window.addEventListener('focus', keepFocus);

    // OPTIMASI 3: Tambahkan Anti Double Scan & System Lock
    let isScanningLock = false;
    let timeoutId = null;

    function returnToStandby() {
        displayZone.style.opacity = '0';
        displayZone.style.transition = 'opacity 0.2s ease';
        
        setTimeout(function() {
            displayZone.innerHTML = `
                <div id="standby-zone" class="text-center py-4">
                    <div class="text-primary pulse-icon mb-4">
                        <i class="bi bi-card-image" style="font-size: 6rem; line-height: 1;"></i>
                    </div>
                    <h3 class="fw-bold text-slate-800 mb-2">Silakan Tempelkan Kartu RFID</h3>
                    <p class="text-muted mx-auto mb-4" style="max-width: 380px;">
                        Dekatkan kartu identitas murid atau atribut chip tap pintar pada perangkat pembaca (*card reader*).
                    </p>
                    <div class="d-inline-flex align-items-center gap-2 px-4 py-2 bg-light border rounded-pill font-monospace text-secondary shadow-inner" style="font-size: 0.9rem;">
                        <span class="spinner-grow spinner-grow-sm text-success" role="status"></span>
                        Menunggu Sensor RFID...
                    </div>
                </div>
            `;
            displayZone.style.opacity = '1';
            rfidInput.value = '';
            isScanningLock = false; // Buka kunci scanner setelah kembali standby
            keepFocus();
        }, 200);
    }

    // OPTIMASI 2 & 7: Gunakan keydown Enter + AJAX Fetch API agar eksekusi < 200ms tanpa reload halaman
    rfidInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            
            if (isScanningLock) return;
            
            const kode = this.value.trim();
            if (kode.length < 4) return; // Penyesuaian panjang karakter minimum kode kartu RFID
            
            isScanningLock = true; // Kunci sistem scanner agar tidak terjadi double-read data
            
            // Bersihkan timeout standby jika user melakukan tap beruntun sebelum 3 detik
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
                    // Tampilkan modul layar sukses presensi secara real-time
                    let avatarHtml = data.foto 
                        ? `<img src="/storage/${data.foto}" class="rounded-circle student-avatar shadow" alt="Foto ${data.nama}">`
                        : `<div class="rounded-circle student-avatar shadow bg-light d-flex align-items-center justify-content-center mx-auto border"><i class="bi bi-person-fill text-secondary" style="font-size: 5rem;"></i></div>`;
                    
                    let alertClass = data.status === 'Terlambat' ? 'alert-warning' : 'alert-success';
                    let alertIcon = data.status === 'Terlambat' ? 'bi-clock-history' : 'bi-check-circle-fill';
                    let alertTitle = data.status === 'Terlambat' ? 'Presensi Diterima (Terlambat)' : 'Presensi Berhasil';

                    displayZone.innerHTML = `
                        <div class="text-center">
                            ${avatarHtml}
                            <h2 class="mt-4 fw-bold text-dark mb-1">${data.nama}</h2>
                            <h5 class="text-muted mb-4">Kelas ${data.kelas}</h5>
                            <div class="alert ${alertClass} border-0 rounded-3 shadow-sm py-3 px-4 mx-auto" style="max-width: 400px;">
                                <h5 class="fw-bold m-0 d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi ${alertIcon} fs-4"></i> ${alertTitle}
                                </h5>
                            </div>
                            <div class="text-secondary small mt-2">Tercatat masuk pada pukul <strong>${data.jam} WIB</strong></div>
                        </div>
                    `;
                } else {
                    // Tampilkan modul layar error/akses ditolak
                    displayZone.innerHTML = `
                        <div class="text-center py-4">
                            <div class="mx-auto bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 100px; height: 100px;">
                                <i class="bi bi-x-circle-fill" style="font-size: 4.5rem;"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-3">Akses Ditolak</h3>
                            <div class="alert alert-danger border-0 d-inline-block rounded-3 px-4 shadow-sm m-0">
                                ${data.message}
                            </div>
                        </div>
                    `;
                }
                
                // Memicu otomatisasi cleanup kembali ke layar standby setelah 3 detik
                timeoutId = setTimeout(returnToStandby, 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                isScanningLock = false;
                rfidInput.value = '';
            });
        }
    });

    // JAM & TANGGAL ENGINE REAL-TIME
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