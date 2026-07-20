<div class="sidebar">

    {{-- ================= BRAND & LOGO ================= --}}
    <div class="sidebar-brand-aligned">
        <h5>SDN Tengah 03</h5>
        <small class="text-muted">
            Sistem Presensi RFID
        </small>

        @if(Auth::check())
            <div class="login-user">
                @if(Auth::user()->role == 'admin')
                    <span class="badge bg-primary">ADMIN</span>
                @else
                    <span class="badge bg-success">WALI KELAS</span>
                @endif
            </div>
        @endif
    </div>

    {{-- ================= MENU UTAMA (UNTUK SEMUA ROLE) ================= --}}
    <div class="section-title">
        MENU UTAMA
    </div>
    <div class="nav flex-column">
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i>
            Dashboard
        </a>
    </div>

    {{-- ================= SEPARASI STRUKTUR STRATEGIS BERDASARKAN ROLE ================= --}}
    @if(Auth::user()->role == 'admin')
        
        {{-- ----------------- HAK AKSES ROLE: ADMIN ----------------- --}}
        
        <div class="section-title">
            MASTER DATA
        </div>
        <div class="nav flex-column">
            <a href="{{ route('users.index') }}"
               class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                Kelola User
            </a>
            <a href="{{ route('kelas.index') }}"
               class="nav-link {{ request()->routeIs('kelas.*') || request()->routeIs('siswa.*') ? 'active' : '' }}">
                <i class="bi bi-folder-fill"></i>
                Data Siswa
            </a>
            <a href="{{ route('alumni.index') }}"
               class="nav-link {{ request()->routeIs('alumni.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard-fill"></i>
                Data Alumni
            </a>
        </div>

        <div class="section-title">
            PRESENSI
        </div>
        <div class="nav flex-column">
            <a href="{{ route('terminal.index') }}"
               class="nav-link {{ request()->routeIs('terminal.index') ? 'active' : '' }}">
                <i class="bi bi-cpu-fill"></i>
                Terminal RFID
            </a>
            <a href="{{ route('presensi.manual') }}"
               class="nav-link {{ request()->routeIs('presensi.manual') ? 'active' : '' }}">
                <i class="bi bi-calendar-plus-fill"></i>
                Kelola Presensi
            </a>
        </div>

        <div class="section-title">
            AKADEMIK
        </div>
        <div class="nav flex-column">
            <a href="{{ route('tahunajaran.index') }}"
               class="nav-link {{ request()->routeIs('tahunajaran.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                Tahun Ajaran
            </a>
        </div>

        <div class="section-title">
            LAPORAN
        </div>
        <div class="nav flex-column">
            <a href="{{ route('laporan.index') }}"
               class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                Rekap Laporan
            </a>
        </div>

        <div class="section-title">
            NOTIFIKASI
        </div>
        <div class="nav flex-column">
            <a href="{{ route('log.notifikasi') }}"
               class="nav-link {{ request()->routeIs('log.notifikasi') ? 'active' : '' }}">
                <i class="bi bi-whatsapp"></i>
                Log Notifikasi
            </a>
        </div>

        <div class="section-title">
            PENGATURAN
        </div>
        <div class="nav flex-column">
            <a href="{{ route('settings.index') }}"
               class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                Pengaturan Sistem
            </a>
        </div>

    @else
        
        {{-- ----------------- HAK AKSES ROLE: WALI KELAS ----------------- --}}

        <div class="section-title">
            PRESENSI
        </div>
        <div class="nav flex-column">
            <a href="{{ route('presensi.manual') }}"
               class="nav-link {{ request()->routeIs('presensi.manual') ? 'active' : '' }}">
                <i class="bi bi-calendar-plus-fill"></i>
                Kelola Presensi
            </a>
        </div>

        <div class="section-title">
            LAPORAN
        </div>
        <div class="nav flex-column">
            <a href="{{ route('laporan.index') }}"
               class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                Rekap Laporan
            </a>
        </div>

    @endif

    {{-- ================= AKUN (UNTUK SEMUA ROLE) ================= --}}
    <div class="section-title">
        AKUN
    </div>
    <div class="nav flex-column">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn-logout" type="submit">
                <i class="bi bi-box-arrow-right"></i>
                Keluar
            </button>
        </form>
    </div>

</div>