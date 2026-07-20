@extends('layouts.app')

@section('content')

{{-- =========================
     WIDGET JAM & TANGGAL LIVE
     Tampil di dashboard Admin maupun Guru/Wali Kelas
========================== --}}
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 bg-white rounded-4 border shadow-premium px-4 py-3 mb-4">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
            <i class="bi bi-calendar3 fs-5"></i>
        </div>
        <div>
            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.3px;">Hari Ini</small>
            <span id="live-tanggal" class="fw-bold text-dark" style="font-size: 1.05rem;">Memuat...</span>
        </div>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success">
            <i class="bi bi-clock-fill fs-5"></i>
        </div>
        <div>
            <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.3px;">Waktu Sekarang (WIB)</small>
            <span id="live-jam" class="fw-bold text-dark font-monospace" style="font-size: 1.05rem;">--:--:--</span>
        </div>
    </div>
</div>

<script>
    (function() {
        const namaHari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        function dua(n) { return n.toString().padStart(2, '0'); }

        function updateJamTanggal() {
            // Selalu mengikuti zona waktu WIB (Asia/Jakarta) agar konsisten dengan server
            const now = new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));

            const tanggalEl = document.getElementById('live-tanggal');
            const jamEl = document.getElementById('live-jam');
            if (tanggalEl) {
                tanggalEl.textContent = namaHari[now.getDay()] + ', ' + now.getDate() + ' ' + namaBulan[now.getMonth()] + ' ' + now.getFullYear();
            }
            if (jamEl) {
                jamEl.textContent = dua(now.getHours()) + ':' + dua(now.getMinutes()) + ':' + dua(now.getSeconds());
            }
        }

        updateJamTanggal();
        setInterval(updateJamTanggal, 1000);
    })();
</script>

@if(Auth::user()->role == 'admin')

    @include('dashboard.admin')

@else

    @include('dashboard.wali')

@endif

@endsection