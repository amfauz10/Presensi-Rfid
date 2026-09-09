<style>
    /* Sidebar Base Styling - SaaS Minimalist Modern */
    .sidebar {
        width: 260px;
        min-height: 100vh;
        background-color: #ffffff;
        border-right: 1px solid #e2e8f0;
        padding: 20px 16px;
        display: flex;
        flex-direction: column;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Brand Header Alignment */
    .sidebar-brand-aligned {
        padding: 8px 12px 20px 12px;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 16px;
    }

    .sidebar-brand-aligned h5 {
        font-weight: 700;
        color: #0f172a;
        font-size: 1.1rem;
        margin: 0;
        letter-spacing: -0.3px;
    }

    .sidebar-brand-aligned small {
        font-size: 0.75rem;
        color: #64748b;
        display: block;
        margin-top: 2px;
    }

    .login-user {
        margin-top: 10px;
    }

    .login-user .badge {
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 4px 8px;
        border-radius: 6px;
    }

    /* Section Category Titles */
    .section-title {
        font-size: 0.675rem;
        font-weight: 700;
        color: #94a3b8;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 14px 12px 6px 12px;
    }

    /* Navigation Links */
    .sidebar .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        color: #475569;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.15s ease-in-out;
        margin-bottom: 2px;
        text-decoration: none;
    }

    .sidebar .nav-link i {
        font-size: 1rem;
        color: #64748b;
        transition: color 0.15s ease-in-out;
        width: 20px;
        text-align: center;
    }

    .sidebar .nav-link:hover {
        color: #0f172a;
        background-color: #f8fafc;
    }

    .sidebar .nav-link:hover i {
        color: #2563eb;
    }

    .sidebar .nav-link.active {
        color: #2563eb;
        background-color: #eff6ff;
        font-weight: 600;
    }

    .sidebar .nav-link.active i {
        color: #2563eb;
    }

    /* Logout Button Styling */
    .btn-logout {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 8px 12px;
        color: #dc2626;
        background: transparent;
        border: none;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s ease-in-out;
        text-align: left;
    }

    .btn-logout i {
        font-size: 1rem;
        color: #dc2626;
        width: 20px;
        text-align: center;
    }

    .btn-logout:hover {
        background-color: #fef2f2;
        color: #b91c1c;
    }
</style>

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
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">ADMIN</span>
                @else
                    <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">WALI KELAS</span>
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
            <span>Dashboard</span>
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
                <span>Kelola User</span>
            </a>
            <a href="{{ route('kelas.index') }}"
               class="nav-link {{ request()->routeIs('kelas.*') || request()->routeIs('siswa.*') ? 'active' : '' }}">
                <i class="bi bi-folder-fill"></i>
                <span>Data Siswa</span>
            </a>
            <a href="{{ route('alumni.index') }}"
               class="nav-link {{ request()->routeIs('alumni.*') ? 'active' : '' }}">
                <i class="bi bi-archive-fill"></i>
                <span>Riwayat Siswa</span>
            </a>
        </div>

        <div class="section-title">
            PRESENSI
        </div>
        <div class="nav flex-column">
            <a href="{{ route('terminal.index') }}"
               class="nav-link {{ request()->routeIs('terminal.index') ? 'active' : '' }}">
                <i class="bi bi-cpu-fill"></i>
                <span>Terminal RFID</span>
            </a>
            <a href="{{ route('presensi.manual') }}"
               class="nav-link {{ request()->routeIs('presensi.manual') ? 'active' : '' }}">
                <i class="bi bi-calendar-plus-fill"></i>
                <span>Kelola Presensi</span>
            </a>
        </div>

        <div class="section-title">
            AKADEMIK
        </div>
        <div class="nav flex-column">
            <a href="{{ route('tahunajaran.index') }}"
               class="nav-link {{ request()->routeIs('tahunajaran.*') ? 'active' : '' }}">
                <i class="bi bi-calendar3"></i>
                <span>Tahun Ajaran</span>
            </a>
        </div>

        <div class="section-title">
            LAPORAN
        </div>
        <div class="nav flex-column">
            <a href="{{ route('laporan.index') }}"
               class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Rekap Laporan</span>
            </a>
        </div>

        <div class="section-title">
            NOTIFIKASI
        </div>
        <div class="nav flex-column">
            <a href="{{ route('log.notifikasi') }}"
               class="nav-link {{ request()->routeIs('log.notifikasi') ? 'active' : '' }}">
                <i class="bi bi-whatsapp"></i>
                <span>Log Notifikasi</span>
            </a>
        </div>

        <div class="section-title">
            PENGATURAN
        </div>
        <div class="nav flex-column">
            <a href="{{ route('settings.index') }}"
               class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                <span>Pengaturan Sistem</span>
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
                <span>Kelola Presensi</span>
            </a>
        </div>

        <div class="section-title">
            LAPORAN
        </div>
        <div class="nav flex-column">
            <a href="{{ route('laporan.index') }}"
               class="nav-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Rekap Laporan</span>
            </a>
        </div>

    @endif

    {{-- ================= AKUN (UNTUK SEMUA ROLE) ================= --}}
    <div class="section-title">
        AKUN
    </div>
    <div class="nav flex-column mb-3">
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button class="btn-logout" type="submit">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>

</div>