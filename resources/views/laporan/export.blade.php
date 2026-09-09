@php
    // ==========================================================
    // PERSIAPAN DATA (disamakan persis dengan tampilan Cetak/PDF)
    // ==========================================================
    $jumlahSiswaSatuKelas = isset($data_siswa) ? count($data_siswa) : 0;

    $hariEfektifBulanIni = 0;
    for ($d = 1; $d <= 31; $d++) {
        if (checkdate((int) $bulan, $d, (int) $tahun)) {
            $dow = \Carbon\Carbon::create((int) $tahun, (int) $bulan, $d)->dayOfWeek;
            if ($dow != \Carbon\Carbon::SATURDAY && $dow != \Carbon\Carbon::SUNDAY) {
                $hariEfektifBulanIni++;
            }
        }
    }

    $totalSakitKelas  = 0;
    $totalIzinKelas   = 0;
    $totalAlpaKelas   = 0;
    $totalHadirKelas  = 0;

    // Warna & style dasar (dipakai berulang di header/isi tabel)
    $YELLOW  = '#FFE600';
    $RED     = '#FF0000';
    $AMBER   = '#FFC107';
    $GRAY    = '#D1D5DB';
    $BORDER  = '1px solid #000000';

    $thBase = "border:{$BORDER}; text-align:center; vertical-align:middle; font-weight:bold; font-family:Arial, sans-serif; font-size:10px; padding:2px;";
    $tdBase = "border:{$BORDER}; text-align:center; vertical-align:middle; font-family:Arial, sans-serif; font-size:10px; padding:2px;";

    // Guru / wali kelas pemilik kelas yang direkap
    if (auth()->check() && auth()->user()->role == 'guru') {
        $namaWali = strtoupper(auth()->user()->name ?? '-');
        $nipWali  = auth()->user()->nip ?? '-';
    } else {
        $namaWali = strtoupper($kelas_terpilih->guru->name ?? '-');
        $nipWali  = $kelas_terpilih->guru->nip ?? '-';
    }

    $namaKepsek = \App\Models\Setting::getValue('kepala_sekolah') ?? 'SUGESTI, S.Pd';
    $nipKepsek  = \App\Models\Setting::getValue('nip_kepala') ?? '196607081998032003';
    $labelKepsek = \App\Models\Setting::getValue('nama_sekolah') ?? 'Kepala SD Negeri Tengah 03 Jakarta Timur';
@endphp
<table style="border-collapse:collapse; font-family:Arial, sans-serif;">
    {{-- ==================== KOP SURAT ==================== --}}
    <tr>
        <td colspan="38" style="text-align:center; font-weight:bold; font-size:16px;">DAFTAR HADIR SISWA</td>
    </tr>
    <tr>
        <td colspan="38" style="text-align:center; font-weight:bold; font-size:14px;">SDN TENGAH 03</td>
    </tr>
    <tr>
        <td colspan="38" style="text-align:center; font-size:12px;">Tahun Ajaran {{ $tahunAktif->nama ?? '-' }}</td>
    </tr>
    <tr><td colspan="38" style="font-size:6px;">&nbsp;</td></tr>

    {{-- ==================== BULAN / KELAS ==================== --}}
    <tr>
        <td colspan="19" style="text-align:left; font-weight:bold; font-size:11px;">
            Bulan : {{ \Carbon\Carbon::create(null, (int) $bulan)->translatedFormat('F') }} {{ $tahun }}
        </td>
        <td colspan="19" style="text-align:right; font-weight:bold; font-size:11px;">
            Kelas : {{ $kelas_terpilih->nama_kelas ?? '-' }}
        </td>
    </tr>
    <tr><td colspan="38" style="font-size:6px;">&nbsp;</td></tr>

    {{-- ==================== HEADER TABEL ==================== --}}
    <tr>
        <td rowspan="2" style="{{ $thBase }} background-color:{{ $YELLOW }}; width:26px;">N O</td>
        <td rowspan="2" style="{{ $thBase }} background-color:{{ $YELLOW }}; width:75px;">NISN</td>
        <td rowspan="2" style="{{ $thBase }} background-color:{{ $YELLOW }}; width:170px; text-align:left; padding-left:6px;">NAMA</td>
        <td colspan="31" style="{{ $thBase }} background-color:{{ $YELLOW }};">
            BULAN : {{ strtoupper(\Carbon\Carbon::create(null, (int) $bulan)->translatedFormat('F')) }}
        </td>
        <td colspan="4" style="{{ $thBase }} background-color:{{ $YELLOW }};">JUMLAH</td>
    </tr>
    <tr>
        @for ($d = 1; $d <= 31; $d++)
            @php
                $isValidDate = checkdate((int) $bulan, $d, (int) $tahun);
                $isSaturday = false;
                $isSunday = false;
                if ($isValidDate) {
                    $dow = \Carbon\Carbon::create((int) $tahun, (int) $bulan, $d)->dayOfWeek;
                    $isSaturday = ($dow == \Carbon\Carbon::SATURDAY);
                    $isSunday = ($dow == \Carbon\Carbon::SUNDAY);
                }
                $bg = !$isValidDate ? $GRAY : ($isSunday ? $RED : ($isSaturday ? $AMBER : $YELLOW));
                $fg = $isSunday ? '#FFFFFF' : '#000000';
            @endphp
            <td style="{{ $thBase }} background-color:{{ $bg }}; color:{{ $fg }}; width:18px; font-size:8px;">
                {{ $isValidDate ? $d : '' }}
            </td>
        @endfor
        <td style="{{ $thBase }} background-color:{{ $YELLOW }}; width:20px;">S</td>
        <td style="{{ $thBase }} background-color:{{ $YELLOW }}; width:20px;">I</td>
        <td style="{{ $thBase }} background-color:{{ $YELLOW }}; width:20px;">A</td>
        <td style="{{ $thBase }} background-color:{{ $YELLOW }}; width:20px;">H</td>
    </tr>

    {{-- ==================== ISI DATA SISWA ==================== --}}
    @foreach ($data_siswa as $index => $siswa)
        @php
            $countS = 0; $countI = 0; $countA = 0; $countHadir = 0;
            // Paksa NISN dibaca sebagai TEKS oleh Excel (zero-width space di depan
            // membuat isinya tidak lagi murni angka, jadi tidak dikonversi ke
            // scientific notation), tapi tetap tampil normal secara visual.
            $nisnAman = $siswa->nisn ? "\u{200B}" . $siswa->nisn : '-';
        @endphp
        <tr>
            <td style="{{ $tdBase }}">{{ $index + 1 }}</td>
            <td style="{{ $tdBase }} font-family:'Courier New', monospace;">{{ $nisnAman }}</td>
            <td style="{{ $tdBase }} text-align:left; padding-left:6px; font-weight:bold;">
                {{ ucwords(strtolower($siswa->nama_siswa)) }}
            </td>
            @for ($d = 1; $d <= 31; $d++)
                @php
                    $tglKey = sprintf('%02d', $d);
                    $statusHariIni = $presensi_matriks[$siswa->id][$tglKey] ?? '-';

                    $isValidDate = checkdate((int) $bulan, $d, (int) $tahun);
                    $isSaturday = false;
                    $isSunday = false;
                    if ($isValidDate) {
                        $dow = \Carbon\Carbon::create((int) $tahun, (int) $bulan, $d)->dayOfWeek;
                        $isSaturday = ($dow == \Carbon\Carbon::SATURDAY);
                        $isSunday = ($dow == \Carbon\Carbon::SUNDAY);
                    }
                    $isWeekend = $isSaturday || $isSunday;

                    $simbol = '-';
                    if (!$isValidDate) {
                        $simbol = '';
                    } elseif ($statusHariIni == 'Sakit') {
                        $simbol = 'S'; $countS++; $totalSakitKelas++;
                    } elseif ($statusHariIni == 'Izin') {
                        $simbol = 'I'; $countI++; $totalIzinKelas++;
                    } elseif ($statusHariIni == 'Alpa') {
                        $simbol = 'A'; $countA++; $totalAlpaKelas++;
                    } elseif (in_array($statusHariIni, ['Hadir', 'Terlambat'])) {
                        $simbol = 'H'; $countHadir++; $totalHadirKelas++;
                    } elseif ($isWeekend) {
                        $simbol = '-';
                    }

                    $bg = !$isValidDate ? $GRAY : ($isSunday ? $RED : ($isSaturday ? $AMBER : '#FFFFFF'));
                    $fg = $isSunday ? '#FFFFFF' : '#000000';
                @endphp
                <td style="{{ $tdBase }} background-color:{{ $bg }}; color:{{ $fg }}; font-weight:bold;">{{ $simbol }}</td>
            @endfor
            <td style="{{ $tdBase }} font-weight:bold;">{{ $countS ?: '-' }}</td>
            <td style="{{ $tdBase }} font-weight:bold;">{{ $countI ?: '-' }}</td>
            <td style="{{ $tdBase }} font-weight:bold;">{{ $countA ?: '-' }}</td>
            <td style="{{ $tdBase }} font-weight:bold;">{{ $countHadir ?: '-' }}</td>
        </tr>
    @endforeach

    <tr><td colspan="38" style="font-size:6px;">&nbsp;</td></tr>

    {{-- ==================== RUMUS PERSENTASE ==================== --}}
    @php
        $pembagiAman = $jumlahSiswaSatuKelas > 0 ? $jumlahSiswaSatuKelas : 1;
        $persenSakitKelas = round(($totalSakitKelas / $pembagiAman) * 100, 2);
        $persenIzinKelas  = round(($totalIzinKelas / $pembagiAman) * 100, 2);
        $persenAlpaKelas  = round(($totalAlpaKelas / $pembagiAman) * 100, 2);
        $persenHadirKelas = round(100 - ($persenSakitKelas + $persenIzinKelas + $persenAlpaKelas), 2);

        $rumusStyle = "font-family:Arial, sans-serif; font-size:10px; text-align:left;";
    @endphp
    <tr>
        <td style="{{ $rumusStyle }}">S</td>
        <td style="{{ $rumusStyle }}">=</td>
        <td style="{{ $rumusStyle }}">{{ $totalSakitKelas }}</td>
        <td style="{{ $rumusStyle }}">÷</td>
        <td colspan="3" style="{{ $rumusStyle }}">{{ $jumlahSiswaSatuKelas }}</td>
        <td colspan="5" style="{{ $rumusStyle }}">x 100%</td>
        <td style="{{ $rumusStyle }}">=</td>
        <td colspan="25" style="{{ $rumusStyle }} font-weight:bold;">{{ number_format($persenSakitKelas, 2) }}%</td>
    </tr>
    <tr>
        <td style="{{ $rumusStyle }}">I</td>
        <td style="{{ $rumusStyle }}">=</td>
        <td style="{{ $rumusStyle }}">{{ $totalIzinKelas }}</td>
        <td style="{{ $rumusStyle }}">÷</td>
        <td colspan="3" style="{{ $rumusStyle }}">{{ $jumlahSiswaSatuKelas }}</td>
        <td colspan="5" style="{{ $rumusStyle }}">x 100%</td>
        <td style="{{ $rumusStyle }}">=</td>
        <td colspan="25" style="{{ $rumusStyle }} font-weight:bold;">{{ number_format($persenIzinKelas, 2) }}%</td>
    </tr>
    <tr>
        <td style="{{ $rumusStyle }}">A</td>
        <td style="{{ $rumusStyle }}">=</td>
        <td style="{{ $rumusStyle }}">{{ $totalAlpaKelas }}</td>
        <td style="{{ $rumusStyle }}">÷</td>
        <td colspan="3" style="{{ $rumusStyle }}">{{ $jumlahSiswaSatuKelas }}</td>
        <td colspan="5" style="{{ $rumusStyle }}">x 100%</td>
        <td style="{{ $rumusStyle }}">=</td>
        <td colspan="25" style="{{ $rumusStyle }} font-weight:bold;">{{ number_format($persenAlpaKelas, 2) }}%</td>
    </tr>
    <tr>
        <td style="{{ $rumusStyle }}">H</td>
        <td style="{{ $rumusStyle }}">=</td>
        <td colspan="4" style="{{ $rumusStyle }}">100% - (S + I + A)</td>
        <td style="{{ $rumusStyle }}">=</td>
        <td colspan="31" style="{{ $rumusStyle }} font-weight:bold;">{{ number_format($persenHadirKelas, 2) }}%</td>
    </tr>
    <tr>
        <td colspan="38" style="{{ $rumusStyle }} font-style:italic;">
            Keterangan &nbsp;: &nbsp; S = Sakit &nbsp;&nbsp; I = Izin &nbsp;&nbsp; A = Alpa &nbsp;&nbsp; H = Hadir
        </td>
    </tr>

    <tr><td colspan="38" style="font-size:10px;">&nbsp;</td></tr>
    <tr><td colspan="38" style="font-size:10px;">&nbsp;</td></tr>

    {{-- ==================== BLOK TANDA TANGAN ==================== --}}
    @php $ttdStyle = "font-family:Arial, sans-serif; font-size:10px; text-align:center;"; @endphp
    <tr>
        <td colspan="3"></td>
        <td colspan="15" style="{{ $ttdStyle }}">Mengetahui,</td>
        <td colspan="2"></td>
        <td colspan="15" style="{{ $ttdStyle }}">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="15" style="{{ $ttdStyle }} font-weight:bold;">{{ $labelKepsek }}</td>
        <td colspan="2"></td>
        <td colspan="15" style="{{ $ttdStyle }} font-weight:bold;">Wali Kelas</td>
        <td colspan="3"></td>
    </tr>
    <tr><td colspan="38" style="font-size:13px;">&nbsp;</td></tr>
    <tr><td colspan="38" style="font-size:13px;">&nbsp;</td></tr>
    <tr><td colspan="38" style="font-size:13px;">&nbsp;</td></tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="15" style="{{ $ttdStyle }} font-weight:bold; text-decoration:underline;">{{ $namaKepsek }}</td>
        <td colspan="2"></td>
        <td colspan="15" style="{{ $ttdStyle }} font-weight:bold; text-decoration:underline;">{{ $namaWali }}</td>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="15" style="{{ $ttdStyle }} font-size:9px;">NIP. {{ $nipKepsek }}</td>
        <td colspan="2"></td>
        <td colspan="15" style="{{ $ttdStyle }} font-size:9px;">NIP. {{ $nipWali }}</td>
        <td colspan="3"></td>
    </tr>
</table>