@extends('layouts.app')

@section('title', 'Ubah Data Siswa')

@section('content')
<!-- Style khusus untuk penyelarasan tema premium SaaS modern -->
<style>
    .page-header-box {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
    }
    .info-pill {
        background-color: #f1f5f9;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.87rem;
    }
    .form-section-title { 
        font-size: 0.85rem; 
        text-transform: uppercase; 
        letter-spacing: 0.5px; 
        color: #94a3b8; 
        font-weight: 700; 
        margin-bottom: 1.5rem; 
    }
    .form-label {
        color: #475569;
        font-size: 0.9rem;
    }
    
    /* Search Bar & Form Controls Premium Style */
    .search-merge-group {
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #cbd5e1;
        transition: all 0.2s ease;
    }
    .search-merge-group:focus-within {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1) !important;
    }
    .search-merge-group .form-control,
    .search-merge-group .form-select,
    .search-merge-group .input-group-text {
        border: none !important;
        background-color: #ffffff !important;
        font-size: 0.95rem;
        color: #1e293b;
    }
    .search-merge-group .input-group-text {
        background-color: #f8fafc !important;
        border-end: 1px solid #cbd5e1 !important;
        color: #64748b; 
    }
    
    .shadow-premium {
        box-shadow: 0 8px 24px rgba(149, 157, 165, 0.05) !important;
    }

    /* RFID Scanner Area Modern Layout */
    .rfid-scanner-zone { 
        background-color: #f8fafc; 
        border: 1px solid #e2e8f0; 
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.25s ease; 
    }
    .rfid-scanner-zone.active-card { 
        background-color: #f0fdf4; 
        border-color: #bbf7d0; 
    }
    .rfid-display-input {
        max-width: 240px;
        margin: 0 auto;
        font-family: monospace;
        font-weight: 600;
        letter-spacing: 1px;
        text-align: center;
        border: 1px solid #cbd5e1 !important;
        border-radius: 8px !important;
    }
</style>

<div class="container-fluid py-4 text-start">

    <!-- HEADER & INFORMASI RINGKAS -->
    <div class="card page-header-box border-0 shadow-premium rounded-4 p-4 mb-4 bg-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h3 class="fw-bold mb-1" style="font-size: 1.5rem; letter-spacing: -0.5px; color: #1e293b;">Ubah Data Siswa</h3>
                <p class="text-muted mb-0 small">Perbarui informasi siswa yang telah terdaftar pada sistem.</p>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <div class="info-pill text-secondary fw-medium">
                    Kelas Saat Ini: <span class="fw-bold text-dark">Kelas {{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                </div>
                <div class="info-pill text-secondary fw-medium">
                    RFID: 
                    @if($siswa->rfid_code)
                        <span class="fw-bold text-success">Terdaftar</span>
                    @else
                        <span class="fw-bold text-danger">Belum Terdaftar</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ERROR LIST HANDLING -->
    @if ($errors->any())
    <div class="alert alert-danger rounded-4 border-0 shadow-sm p-3 mb-4 d-flex align-items-start gap-3" style="background-color: #fef2f2; color: #b91c1c;">
        <i class="bi bi-exclamation-circle-fill text-danger fs-5 mt-0.5"></i>
        <div>
            <strong class="d-block mb-1 fw-bold">Perubahan Gagal Disimpan</strong>
            <ul class="mb-0 small text-danger ps-3 fw-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- MAIN FORM CARD -->
    <div class="card border-0 shadow-premium rounded-4 bg-white">
        <div class="card-body p-4">
            <form action="{{ route('siswa.update', $siswa->id) }}" method="POST" class="needs-validation" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- KOLOM KIRI: IDENTITAS SISWA -->
                    <div class="col-md-7 mb-4 mb-md-0 border-end pe-md-4">
                        <div class="form-section-title">Identitas Utama Murid</div>
                        
                        <!-- Nama Siswa -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Nama Lengkap</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="nama_siswa" 
                                    value="{{ old('nama_siswa', $siswa->nama_siswa) }}"
                                    placeholder="Contoh: ACHMAD JANUAR PAMUNGKAS"
                                    class="form-control shadow-none @error('nama_siswa') is-invalid @enderror" style="padding: 10px 12px;">
                                @error('nama_siswa')
                                    <div class="invalid-feedback px-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- NISN -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Nomor Induk Siswa Nasional (NISN)</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-card-text text-muted"></i></span>
                                <input type="text" name="nisn" 
                                    value="{{ old('nisn', $siswa->nisn) }}"
                                    placeholder="Contoh: 3166469971"
                                    class="form-control shadow-none @error('nisn') is-invalid @enderror" style="padding: 10px 12px;">
                                @error('nisn')
                                    <div class="invalid-feedback px-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pilihan Kelas -->
                        <!-- REVISI DOSEN (Poin 3): Rombel yang tampil otomatis dibatasi
                             hanya seangkatan dengan kelas siswa saat ini (mis. siswa di
                             3A hanya bisa dipindah ke 3A/3B), agar admin tidak salah
                             klik pindah tingkat. Difilter langsung di PHP (SiswaController),
                             tanpa JS, tanpa kolom/tabel baru. -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Rombel / Penempatan Kelas</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-layers text-muted"></i></span>
                                <select name="kelas_id" class="form-select shadow-none" style="padding: 10px 12px;">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($daftar_kelas as $kelas)
                                        <option value="{{ $kelas->id }}" {{ $siswa->kelas_id == $kelas->id ? 'selected' : '' }}>
                                            Kelas {{ $kelas->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-muted" style="font-size: 0.78rem;">
                                Hanya menampilkan rombel seangkatan dengan kelas siswa saat ini.
                            </span>
                        </div>

                        <!-- REVISI DOSEN (Poin 1 & 2 - disederhanakan): Status Keaktifan Siswa -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Status Siswa</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-person-check text-muted"></i></span>
                                <select name="status" id="status-siswa" class="form-select shadow-none" style="padding: 10px 12px;">
                                    <option value="Aktif" {{ $siswa->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Tidak Aktif" {{ $siswa->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    @if($siswa->status == 'Alumni')
                                        <option value="Alumni" selected>Alumni (Lulus)</option>
                                    @endif
                                </select>
                            </div>
                            <span class="text-muted" style="font-size: 0.78rem;">
                                Status "Tidak Aktif" akan mengeluarkan siswa dari daftar aktif kelas, namun data & riwayat presensinya tetap tersimpan.
                            </span>
                        </div>

                        <!-- REVISI: Alasan Tidak Aktif -- hanya tampil kalau status "Tidak Aktif" dipilih -->
                        <div class="mb-3" id="wrapper-alasan-nonaktif" style="{{ $siswa->status == 'Tidak Aktif' ? '' : 'display:none;' }}">
                            <label class="form-label fw-semibold mb-2">Alasan Tidak Aktif</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-chat-left-text text-muted"></i></span>
                                <select name="alasan_nonaktif" class="form-select shadow-none" style="padding: 10px 12px;">
                                    <option value="">-- Pilih Alasan --</option>
                                    @foreach(['Pindah Sekolah', 'Putus Sekolah', 'Meninggal Dunia'] as $alasan)
                                        <option value="{{ $alasan }}" {{ $siswa->alasan_nonaktif == $alasan ? 'selected' : '' }}>{{ $alasan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-muted" style="font-size: 0.78rem;">
                                Alasan ini hanya untuk catatan administrasi internal, tidak ditampilkan mencolok di daftar siswa.
                            </span>
                        </div>

                        @push('scripts')
                        <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const statusSelect = document.getElementById('status-siswa');
                            const wrapperAlasan = document.getElementById('wrapper-alasan-nonaktif');
                            if (statusSelect && wrapperAlasan) {
                                statusSelect.addEventListener('change', function () {
                                    wrapperAlasan.style.display = (this.value === 'Tidak Aktif') ? '' : 'none';
                                });
                            }
                        });
                        </script>
                        @endpush
                    </div>

                    <!-- KOLOM KANAN: CONFIG IOT & INTEGRATION -->
                    <div class="col-md-5 ps-md-4">
                        <div class="form-section-title">Konfigurasi Sistem & IoT</div>

                        <!-- RFID Scanner Area -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold d-flex justify-content-between align-items-center mb-2">
                                <span>Pendaftaran Tag RFID</span>
                                <span class="badge bg-light text-secondary border rounded-pill fw-medium px-2.5 py-1" style="font-size: 0.72rem;">Device Online</span>
                            </label>
                            
                            <div class="rfid-scanner-zone {{ $siswa->rfid_code ? 'active-card' : '' }} text-center">
                                <span class="text-muted small d-block mb-2">Dekatkan kartu fisik ke mesin pembaca</span>
                                
                                <input type="text" name="rfid_code" 
                                    value="{{ old('rfid_code', $siswa->rfid_code) }}"
                                    placeholder="Menunggu sensor..."
                                    class="form-control form-control-sm rfid-display-input shadow-none @error('rfid_code') is-invalid @enderror" style="padding: 8px 10px;">
                                
                                @error('rfid_code')
                                    <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between mt-1.5 px-1">
                                <span class="text-muted xsmall" style="font-size: 0.78rem;">Status Capture:</span>
                                <span class="fw-semibold xsmall text-dark" id="rfid-status" style="font-size: 0.78rem;">
                                    {{ $siswa->rfid_code ? 'Terbaca: '.$siswa->rfid_code : 'Kosong' }}
                                </span>
                            </div>
                        </div>

                        <!-- WhatsApp Gateway Module -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold mb-2">Nomor WhatsApp Orang Tua</label>
                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-whatsapp text-muted"></i></span>
                                <input type="text" name="no_hp_orang_tua" 
                                    value="{{ old('no_hp_orang_tua', $siswa->no_hp_orang_tua) }}"
                                    placeholder="Contoh: 081234567890"
                                    class="form-control shadow-none @error('no_hp_orang_tua') is-invalid @enderror" style="padding: 10px 12px;">
                                @error('no_hp_orang_tua')
                                    <div class="invalid-feedback px-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bagian Edit dan Preview Foto Siswa -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-2">Foto Murid</label>
                            
                            <div class="d-block mb-2.5">
                                <div class="p-1 bg-white border rounded-3 d-inline-block shadow-sm">
                                    @if($siswa->foto && file_exists(public_path('storage/'.$siswa->foto)))
                                        <img src="{{ asset('storage/'.$siswa->foto) }}" alt="Foto {{ $siswa->nama_siswa }}" width="110" class="rounded-2 object-fit-cover" style="height: 130px;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light rounded-2 text-secondary" style="width: 110px; height: 130px;">
                                            <i class="bi bi-person-bounding-box fs-2 opacity-50"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="input-group search-merge-group">
                                <span class="input-group-text"><i class="bi bi-image text-muted"></i></span>
                                <input type="file" name="foto" 
                                    class="form-control shadow-none @error('foto') is-invalid @enderror" 
                                    style="padding: 10px 12px;"
                                    accept="image/*">
                                @error('foto')
                                    <div class="invalid-feedback px-2">{{ $message }}</div>
                                @enderror
                            </div>
                            <span class="text-muted d-block mt-2" style="font-size: 0.75rem; line-height: 1.4;">
                                Format: <strong>JPG, JPEG, PNG</strong> (Maks 2 MB). Biarkan kosong jika tidak ingin mengubah foto.
                            </span>
                        </div>
                    </div>

                    <!-- SUBMIT ACTIONS -->
                    <div class="col-12">
                        <hr class="my-4" style="border-color: #f1f3f5;">
                        <div class="d-flex justify-content-end gap-2">
                            {{-- LOGIC: Alumni/Tidak Aktif diarahkan ke halaman Riwayat Siswa Nonaktif, siswa reguler kembali ke kelas asalnya --}}
                            @if(in_array($siswa->status, ['Alumni', 'Tidak Aktif']))
                                <a href="{{ route('alumni.index', ['status' => $siswa->status]) }}"
                                   class="btn btn-light border rounded-3 px-4 fw-semibold text-secondary shadow-none">
                                    Batal
                                </a>
                            @else
                                <a href="/kelas/{{ $siswa->kelas_id }}"
                                   class="btn btn-light border rounded-3 px-4 fw-semibold text-secondary shadow-none">
                                    Batal
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary rounded-3 px-4 fw-semibold shadow-sm" style="box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15) !important;">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil Diperbarui',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1800
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfidInput = document.querySelector('input[name="rfid_code"]');
    const rfidBox = document.querySelector('.rfid-scanner-zone');
    const rfidStatus = document.getElementById('rfid-status');
    
    if (rfidInput) {
        rfidInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                rfidBox.classList.add('active-card');
                rfidStatus.innerText = 'Terbaca: ' + rfidInput.value;
                rfidStatus.className = "fw-semibold text-success";
            }
        });
        
        rfidInput.addEventListener('input', function() {
            if(this.value.trim() === "") {
                rfidBox.classList.remove('active-card');
                rfidStatus.innerText = 'Kosong';
                rfidStatus.className = "text-dark";
            }
        });
    }
});
</script>
@endsection