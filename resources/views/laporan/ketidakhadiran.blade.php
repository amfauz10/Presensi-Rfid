@extends('layouts.app')

@section('title', 'Rekap Ketidakhadiran')

@section('content')

{{-- PANEL ATAS --}}
<div class="d-flex justify-content-between align-items-center mb-4 text-start">
    <div>
        <h3 class="fw-bold m-0">Rekap Ketidakhadiran</h3>
        <p class="text-muted m-0 mt-1" style="font-size: 0.9rem;">Total sakit, izin, dan alpa siswa per bulan.</p>
    </div>
    <div class="d-flex gap-2">
        @if(request()->has('kelas_id') || auth()->user()->role == 'guru')
            <a href="{{ route('laporan.ketidakhadiran.export', request()->query()) }}" class="btn btn-success rounded-3 px-3 fw-semibold shadow-none">
                <i class="bi bi-file-earmark-excel me-2"></i> Export ke Excel
            </a>
        @endif
    </div>
</div>

{{-- FILTER BAR --}}
<div class="card p-4 mb-4 text-start rounded-4 border-0 shadow-sm bg-white">
    <form action="{{ route('laporan.ketidakhadiran') }}" method="GET">
        <div class="row align-items-end g-3">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Pilih Bulan</label>
                <select name="bulan" class="form-select rounded-3 p-2 shadow-none">
                    @for($m=1; $m<=12; $m++)
                        <option value="{{ sprintf('%02d', $m) }}" {{ (int)$bulan === $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted">Pilih Tahun</label>
                <select name="tahun" class="form-select rounded-3 p-2 shadow-none">
                    @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}" {{ (int)$tahun === $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            @if(auth()->user()->role == 'admin')
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Filter Kelas</label>
                    <select name="kelas_id" class="form-select rounded-3 p-2 shadow-none" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($daftar_kelas as $k)
                            <option value="{{ $k->id }}" {{ $kelas_id == $k->id ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 p-2 rounded-3 fw-semibold" style="height: 41px;">
                        <i class="bi bi-search me-1"></i> Buka Rekap
                    </button>
                </div>
            @else
                <div class="col-md-6">
                    <button type="submit" class="btn btn-dark w-100 p-2 rounded-3 fw-semibold" style="height: 41px;">
                        <i class="bi bi-arrow-clockwise me-1"></i> Perbarui Rekap Bulan
                    </button>
                </div>
            @endif
        </div>
    </form>
</div>

{{-- TABEL REKAP --}}
@if(isset($kelas_terpilih) && $kelas_terpilih)
    <div class="card p-4 rounded-4 border-0 shadow-sm bg-white text-start">
    <div class="table-responsive">
    <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama Siswa</th>
                    <th style="width: 15%;">Sakit</th>
                    <th style="width: 15%;">Izin</th>
                    <th style="width: 15%;">Alpa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekap as $i => $siswa)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td>{{ $siswa->nama_siswa }}</td>
                        <td class="text-center">{{ $siswa->sakit }}</td>
                        <td class="text-center">{{ $siswa->izin }}</td>
                        <td class="text-center">{{ $siswa->alpa }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada data siswa aktif di kelas ini.</td>
                    </tr>
                @endforelse
          </tbody>
        </table>
        </div>
    </div>
@else
    <div class="text-center text-muted py-5">Silakan pilih kelas terlebih dahulu untuk menampilkan rekap.</div>
@endif

@endsection
