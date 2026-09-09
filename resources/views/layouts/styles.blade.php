<style>
:root{
    --bg:#f8fafc;
    --white:#ffffff;

    --primary:#2563eb;
    --primary-light:#eff6ff;

    --success:#16a34a;
    --danger:#dc2626;
    --warning:#f59e0b;

    --text:#0f172a;
    --muted:#64748b;

    --border:#e5e7eb;

    --shadow:0 10px 30px rgba(15,23,42,.05);

    --radius:16px;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

html, body{
    width:100%;
    max-width:100%;
    overflow-x:hidden;
}

body{
    background:var(--bg);
    color:var(--text);
    /* LAYOUT UTAMA: FLEXBOX agar sidebar & konten TIDAK PERNAH tumpang tindih,
       terlepas dari lebar konten di dalam halaman manapun. */
    display:flex;
    align-items:stretch;
    min-height:100vh;
}

/* ===================================================
SIDEBAR
=================================================== */

.sidebar{
    position:fixed;
    top:0;
    left:0;
    flex:0 0 290px;
    width:290px;
    max-width:290px;
    height:100vh;
    background:#fff;
    border-right:1px solid var(--border);
    overflow-y:auto;
    overflow-x:hidden;
    padding:24px 20px;
    z-index:1030;
    transition:transform .25s ease;
}

.sidebar::-webkit-scrollbar{
    width:5px;
}

.sidebar::-webkit-scrollbar-thumb{
    background:#d1d5db;
    border-radius:20px;
}

/* Tombol buka/tutup sidebar khusus mobile/tablet (disembunyikan di desktop) */
.sidebar-toggle-btn{
    display:none;
    position:fixed;
    top:16px;
    left:16px;
    z-index:1050;
    width:44px;
    height:44px;
    align-items:center;
    justify-content:center;
    background:#fff;
    border:1px solid var(--border);
    border-radius:12px;
    box-shadow:var(--shadow);
    color:var(--text);
    font-size:20px;
    cursor:pointer;
}

.sidebar-backdrop{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(15,23,42,.45);
    z-index:1020;
}

/* ===================================================
HEADER
=================================================== */

.sidebar-brand-aligned{
    padding-bottom:18px;
    margin-bottom:18px;
    border-bottom:1px solid var(--border);
}

.sidebar-brand-aligned h5{
    margin:0;
    font-size:24px;
    font-weight:700;
    color:var(--text);
    line-height:1.25;
    letter-spacing:-0.5px;
}

.sidebar-brand-aligned small{
    display:block;
    margin-top:6px;
    font-size:14px;
    color:var(--muted);
    line-height:1.4;
}

/* ===================================================
ROLE
=================================================== */

.login-user{
    margin-top:16px;
}

.login-user .badge{
    display:inline-block;
    padding:8px 20px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    letter-spacing:.4px;
}

/* ===================================================
SECTION TITLE
=================================================== */

.section-title{
    margin:16px 0 8px;
    padding-left:4px;
    color:#94a3b8;
    font-size:11px;
    font-weight:700;
    letter-spacing:1px;
    text-transform:uppercase;
}

/* ===================================================
MENU
=================================================== */

.sidebar .nav{
    display:flex;
    flex-direction:column;
    gap:4px;
}

.sidebar .nav-link{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 14px;
    border-radius:12px;
    color:#475569;
    text-decoration:none;
    font-size:14px;
    font-weight:500;
    transition:.2s ease;
}

.sidebar .nav-link i{
    width:20px;
    text-align:center;
    font-size:18px;
}

.sidebar .nav-link:hover{
    background:var(--primary-light);
    color:var(--primary);
}

.sidebar .nav-link.active{
    background:#e8f0ff;
    color:var(--primary);
    font-weight:600;
}

/* ===================================================
LOGOUT
=================================================== */

.btn-logout{
    width:100%;
    border:none;
    background:none;
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 14px;
    border-radius:12px;
    color:#dc2626;
    font-weight:600;
    transition:.2s;
    cursor:pointer;
}

.btn-logout:hover{
    background:#fee2e2;
}

/* ===================================================
CONTENT
=================================================== */

.main-content{
    flex:1 1 auto;
    min-width:0;      /* penting: cegah konten lebar (tabel, dsb) mendorong keluar & menutupi sidebar */
    margin-left: 290px;
    width:calc(100% - 290px);
    max-width:100%;
    overflow-x:hidden;
    padding:32px;
    animation:pageFadeIn .45s ease-in-out;
}

.main-content img,
.main-content table,
.main-content .table-responsive{
    max-width:100%;
}

@keyframes pageFadeIn{
    from{
        opacity:0;
        transform:translateY(10px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* ===================================================
CARD
=================================================== */

.card{
    border:none;
    border-radius:18px;
    box-shadow:var(--shadow);
}

.card:hover{
    box-shadow:0 15px 35px rgba(15,23,42,.08);
}

/* ===================================================
STAT CARD
=================================================== */

.stat-card{
    padding:24px;
}

.stat-card h2{
    font-size:40px;
    font-weight:700;
}

.stat-icon{
    width:60px;
    height:60px;
    border-radius:16px;

    display:flex;
    align-items:center;
    justify-content:center;
}

/* ===================================================
TABLE
=================================================== */

.table{
    margin-bottom:0;
}

.table thead th{
    background:#f8fafc;
    color:#64748b;
    border-bottom:1px solid var(--border);

    font-size:12px;
    font-weight:700;

    text-transform:uppercase;
}

.table td{
    padding:16px;
    vertical-align:middle;
}

.table-hover tbody tr{
    transition:.2s;
}

.table-hover tbody tr:hover{
    background:#f8fafc;
}

/* ===================================================
BUTTON
=================================================== */

.btn{
    border-radius:12px;
    font-weight:600;
}

.btn-primary{
    background:var(--primary);
    border:none;
}

.btn-primary:hover{
    background:#1d4ed8;
}

.btn-sm{
    display:inline-flex;
    align-items:center;
    justify-content:center;
}

/* ===================================================
BADGE
=================================================== */

.badge{
    border-radius:999px;
    padding:6px 14px;
    font-weight:600;
}

/* ===================================================
FORM
=================================================== */

.form-control{
    border-radius:12px;
    border:1px solid var(--border);
    box-shadow:none !important;
}

.form-control:focus{
    border-color:var(--primary);
}

/* ===================================================
DATA SISWA
=================================================== */

.folder-card{
    display:flex;
    align-items:center;
    gap:18px;

    padding:22px;

    background:#fff;

    border:1px solid var(--border);
    border-radius:18px;

    text-decoration:none;
    color:var(--text);

    transition:.25s;

    box-shadow:var(--shadow);
}

.folder-card:hover{
    transform:translateY(-4px);
    border-color:var(--primary);
    background:var(--primary-light);
    color:var(--primary);
}

.folder-icon{
    width:56px;
    height:56px;

    border-radius:16px;

    background:var(--primary-light);
    color:var(--primary);

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:26px;

    transition:.25s;
}

.folder-card:hover .folder-icon{
    background:var(--primary);
    color:#fff;
}

.card-table{
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:var(--shadow);
}

.badge-rfid{
    display:inline-block;
    padding:6px 12px;
    border-radius:8px;

    background:#f1f5f9;
    border:1px solid #cbd5e1;

    /* Pengecualian font untuk kode RFID tetap monospace agar rapi */
    font-family:monospace !important; 
    font-size:13px;

    color:#334155;
}

/* ===================================================
PERMANENT DESKTOP LAYOUT (WITH ENLARGED TOUCH TARGETS)
=================================================== */

.sidebar {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 240px !important;
    max-width: 240px !important;
    height: 100vh !important;
    z-index: 1030 !important;
    transform: none !important;
    background: #ffffff;
    border-right: 1px solid var(--border);
}

.main-content {
    margin-left: 240px !important;
    width: calc(100% - 240px) !important;
    max-width: calc(100% - 240px) !important;
    padding: 24px 28px !important;
}

/* Touch Target & Readability Boosts */
.btn {
    min-height: 40px;
    font-size: 0.875rem;
    font-weight: 600;
}

.form-control, .form-select, .saas-form-control, .saas-form-select {
    min-height: 42px;
    font-size: 0.875rem;
}

.table-responsive {
    border-radius: 12px;
}

</style>