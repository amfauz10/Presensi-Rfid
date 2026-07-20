<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Sistem Presensi RFID')</title>

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- CSS Project --}}
    @include('layouts.styles')

    {{-- FLATPICKR CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

{{-- Tombol buka sidebar (tampil otomatis di layar tablet/mobile lewat CSS) --}}
<button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Buka menu">
    <i class="bi bi-list"></i>
</button>

{{-- Overlay gelap saat sidebar terbuka di layar sempit --}}
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

@include('layouts.sidebar')

<div class="main-content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{-- FLATPICKR JS & SCRIPTS STACK --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // Toggle sidebar khusus tampilan tablet/mobile agar tidak pernah menutupi konten
    (function () {
        const sidebar   = document.querySelector('.sidebar');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const backdrop  = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            sidebar.classList.add('show');
            backdrop.classList.add('show');
        }
        function closeSidebar() {
            sidebar.classList.remove('show');
            backdrop.classList.remove('show');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
            });
        }
        if (backdrop) {
            backdrop.addEventListener('click', closeSidebar);
        }
        // Tutup otomatis saat memilih menu (khusus layar sempit)
        sidebar.querySelectorAll('.nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 992) closeSidebar();
            });
        });
        // Reset state saat resize kembali ke layar besar
        window.addEventListener('resize', function () {
            if (window.innerWidth > 992) closeSidebar();
        });
    })();
</script>

@stack('scripts')

</body>
</html>