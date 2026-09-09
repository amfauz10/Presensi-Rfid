@extends('layouts.app')

@section('content')

{{-- =========================
     WIDGET JAM & TANGGAL LIVE
     Tampil di dashboard Admin maupun Wali Kelas
========================== --}}
<div class="d-flex justify-content-end mb-2">
    <div class="d-inline-flex align-items-center gap-3 px-3 py-1.5 bg-light-subtle rounded-pill border border-light-subtle" style="font-size: 0.82rem; color: #64748b; background-color: rgba(248, 250, 252, 0.8);">
        <span class="d-flex align-items-center gap-2">
            <i class="bi bi-calendar3 text-secondary"></i>
            <span id="live-tanggal" class="fw-semibold">Memuat...</span>
        </span>
        <span class="text-muted opacity-50">|</span>
        <span class="d-flex align-items-center gap-2 text-dark">
            <i class="bi bi-clock text-primary"></i>
            <span id="live-jam" class="fw-bold font-monospace">--:--:--</span>
            <span class="badge bg-secondary bg-opacity-10 text-secondary border-0" style="font-size: 0.65rem; padding: 2px 4px;">WIB</span>
        </span>
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