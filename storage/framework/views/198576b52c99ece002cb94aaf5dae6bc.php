<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title','Sistem Presensi RFID'); ?></title>

    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
    <?php echo $__env->make('layouts.styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>


<button type="button" class="sidebar-toggle-btn" id="sidebarToggleBtn" aria-label="Buka menu">
    <i class="bi bi-list"></i>
</button>


<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<div class="main-content">
    <?php echo $__env->yieldContent('content'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


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

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/layouts/app.blade.php ENDPATH**/ ?>