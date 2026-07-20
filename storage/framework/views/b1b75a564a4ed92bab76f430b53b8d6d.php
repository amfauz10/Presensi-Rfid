<div class="sidebar">

    
    <div class="sidebar-brand-aligned">
        <h5>SDN Tengah 03</h5>
        <small class="text-muted">
            Sistem Presensi RFID
        </small>

        <?php if(Auth::check()): ?>
            <div class="login-user">
                <?php if(Auth::user()->role == 'admin'): ?>
                    <span class="badge bg-primary">ADMIN</span>
                <?php else: ?>
                    <span class="badge bg-success">WALI KELAS</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="section-title">
        MENU UTAMA
    </div>
    <div class="nav flex-column">
        <a href="<?php echo e(route('dashboard')); ?>"
           class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-grid"></i>
            Dashboard
        </a>
    </div>

    
    <?php if(Auth::user()->role == 'admin'): ?>
        
        
        
        <div class="section-title">
            MASTER DATA
        </div>
        <div class="nav flex-column">
            <a href="<?php echo e(route('users.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                <i class="bi bi-people-fill"></i>
                Kelola User
            </a>
            <a href="<?php echo e(route('kelas.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('kelas.*') || request()->routeIs('siswa.*') ? 'active' : ''); ?>">
                <i class="bi bi-folder-fill"></i>
                Data Siswa
            </a>
            <a href="<?php echo e(route('alumni.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('alumni.*') ? 'active' : ''); ?>">
                <i class="bi bi-mortarboard-fill"></i>
                Data Alumni
            </a>
        </div>

        <div class="section-title">
            PRESENSI
        </div>
        <div class="nav flex-column">
            <a href="<?php echo e(route('terminal.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('terminal.index') ? 'active' : ''); ?>">
                <i class="bi bi-cpu-fill"></i>
                Terminal RFID
            </a>
            <a href="<?php echo e(route('presensi.manual')); ?>"
               class="nav-link <?php echo e(request()->routeIs('presensi.manual') ? 'active' : ''); ?>">
                <i class="bi bi-calendar-plus-fill"></i>
                Kelola Presensi
            </a>
        </div>

        <div class="section-title">
            AKADEMIK
        </div>
        <div class="nav flex-column">
            <a href="<?php echo e(route('tahunajaran.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('tahunajaran.*') ? 'active' : ''); ?>">
                <i class="bi bi-calendar3"></i>
                Tahun Ajaran
            </a>
        </div>

        <div class="section-title">
            LAPORAN
        </div>
        <div class="nav flex-column">
            <a href="<?php echo e(route('laporan.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('laporan.index') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-text-fill"></i>
                Rekap Laporan
            </a>
        </div>

        <div class="section-title">
            NOTIFIKASI
        </div>
        <div class="nav flex-column">
            <a href="<?php echo e(route('log.notifikasi')); ?>"
               class="nav-link <?php echo e(request()->routeIs('log.notifikasi') ? 'active' : ''); ?>">
                <i class="bi bi-whatsapp"></i>
                Log Notifikasi
            </a>
        </div>

        <div class="section-title">
            PENGATURAN
        </div>
        <div class="nav flex-column">
            <a href="<?php echo e(route('settings.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                <i class="bi bi-gear-fill"></i>
                Pengaturan Sistem
            </a>
        </div>

    <?php else: ?>
        
        

        <div class="section-title">
            PRESENSI
        </div>
        <div class="nav flex-column">
            <a href="<?php echo e(route('presensi.manual')); ?>"
               class="nav-link <?php echo e(request()->routeIs('presensi.manual') ? 'active' : ''); ?>">
                <i class="bi bi-calendar-plus-fill"></i>
                Kelola Presensi
            </a>
        </div>

        <div class="section-title">
            LAPORAN
        </div>
        <div class="nav flex-column">
            <a href="<?php echo e(route('laporan.index')); ?>"
               class="nav-link <?php echo e(request()->routeIs('laporan.index') ? 'active' : ''); ?>">
                <i class="bi bi-file-earmark-text-fill"></i>
                Rekap Laporan
            </a>
        </div>

    <?php endif; ?>

    
    <div class="section-title">
        AKUN
    </div>
    <div class="nav flex-column">
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button class="btn-logout" type="submit">
                <i class="bi bi-box-arrow-right"></i>
                Keluar
            </button>
        </form>
    </div>

</div><?php /**PATH C:\xampp\htdocs\sistem-presensi\resources\views/layouts/sidebar.blade.php ENDPATH**/ ?>