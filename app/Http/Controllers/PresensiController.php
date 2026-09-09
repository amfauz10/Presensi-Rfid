<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\FonnteService;
use App\Models\LogNotifikasi;
use App\Models\Setting;
use App\Models\TahunAjaran;
use App\Jobs\SendWhatsAppNotification; 
use App\Exports\RekapBulananExport;   
use App\Exports\LaporanKetidakhadiranExport;
use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
    // ==========================================
    // FUNGSI UTAMA: REKAP LAPORAN BULANAN (SINKRONISASI MATRIKS 100% REAL-TIME)
    // ==========================================
    public function laporan(Request $request)
    {
        $user = auth()->user(); 

        // 3. REKAP LAPORAN: Proteksi hak akses agar Wali Kelas langsung membuka kelasnya sendiri
        if ($user->role == 'guru') { 
            $daftar_kelas = Kelas::where('id', $user->kelas_id)->get(); 
            $kelas_id = $user->kelas_id; 
        } else {
            $daftar_kelas = Kelas::orderBy('nama_kelas', 'asc')->get(); 
            $kelas_id = $request->input('kelas_id'); 
        }
        
        $bulan = (int) $request->input('bulan', date('m')); 
        $tahun = (int) $request->input('tahun', date('Y')); 

        // REVISI DOSEN (Poin 4): Tahun Ajaran kini bisa dipilih (bukan selalu
        // tahun ajaran yang sedang aktif), supaya admin/wali kelas bisa melihat
        // riwayat presensi kelas di tahun ajaran sebelumnya -- misal siswa yang
        // dulu di kelas 2A (tahun lalu) sudah naik ke 3A (tahun ini).
        $daftarTahunAjaran = TahunAjaran::orderByDesc('tanggal_mulai')->get();
        $tahun_ajaran_id = $request->input('tahun_ajaran_id') ?: optional(TahunAjaran::aktif())->id;

        $data = $this->getDataLaporan( 
            $kelas_id, 
            $bulan, 
            $tahun, 
            $tahun_ajaran_id 
        );

        return view( 
            'laporan.index', 
            array_merge( 
                [ 
                    'daftar_kelas' => $daftar_kelas, 
                    'daftarTahunAjaran' => $daftarTahunAjaran, 
                    // Alias snake_case, berjaga-jaga kalau view lokal Anda memakai nama ini
                    'daftar_tahun_ajaran' => $daftarTahunAjaran, 
                ], 
                $data 
            ) 
        );
    }

    // ==========================================
    // EXPORT REKAP LAPORAN BULANAN KE EXCEL
    // ==========================================
    public function exportLaporan(Request $request)
    {
        $user = auth()->user();
        $jenis = $request->input('jenis', 'bulanan'); // Tangkap parameter jenis laporan

        if ($user->role == 'guru') {
            $kelas_id = $user->kelas_id;
        } else {
            $kelas_id = $request->input('kelas_id');
        }

        $bulan = (int) $request->input('bulan', date('m'));
        $tahun = (int) $request->input('tahun', date('Y'));

        $kelas_terpilih = Kelas::with('guru')->find($kelas_id);
        $namaBulan = Carbon::create(null, $bulan)->translatedFormat('F');
        $namaKelas = $kelas_terpilih->nama_kelas ?? 'Semua-Kelas';

        // JIKA YANG DIPILIH ADALAH REKAP KETIDAKHADIRAN
        if ($jenis === 'ketidakhadiran') {
            // Ambil data rekap menggunakan query yang sama dengan function laporanKetidakhadiran
            $rekap = Siswa::where('kelas_id', $kelas_id)
                ->where('status', 'Aktif')
                ->withCount([
                    'presensi as sakit' => function ($q) use ($bulan, $tahun) {
                        $q->whereMonth('created_at', $bulan)
                          ->whereYear('created_at', $tahun)
                          ->where('status', 'Sakit');
                    },
                    'presensi as izin' => function ($q) use ($bulan, $tahun) {
                        $q->whereMonth('created_at', $bulan)
                          ->whereYear('created_at', $tahun)
                          ->where('status', 'Izin');
                    },
                    'presensi as alpa' => function ($q) use ($bulan, $tahun) {
                        $q->whereMonth('created_at', $bulan)
                          ->whereYear('created_at', $tahun)
                          ->where('status', 'Alpa');
                    },
                ])
                ->orderBy('nama_siswa', 'asc')
                ->get();

            $namaFile = 'Rekap-Ketidakhadiran-' . str_replace(' ', '-', $namaKelas) . '-' . $namaBulan . '-' . $tahun . '.xlsx';

            return Excel::download(
                new LaporanKetidakhadiranExport($rekap, $kelas_terpilih, $bulan, $tahun),
                $namaFile
            );
        }

        // DEFAULT: REKAP BULANAN
        // REVISI DOSEN (Poin 4): Export Excel juga ikut hormati Tahun Ajaran
        // yang dipilih di filter, bukan selalu tahun ajaran aktif.
        $tahun_ajaran_id = $request->input('tahun_ajaran_id') ?: optional(TahunAjaran::aktif())->id;
        $data = $this->getDataLaporan($kelas_id, $bulan, $tahun, $tahun_ajaran_id);
        $namaFile = 'Rekap-Presensi-' . str_replace(' ', '-', $namaKelas) . '-' . $namaBulan . '-' . $tahun . '.xlsx';

        return Excel::download(
            new RekapBulananExport(
                $data['data_siswa'],
                $data['presensi_matriks'],
                $data['kelas_terpilih'],
                $data['bulan'],
                $data['tahun'],
                $data['tahunAktif']
            ),
            $namaFile
        );
    }

    private function getDataLaporan($kelas_id, $bulan, $tahun, $tahun_ajaran_id = null)
    {
        $tahunAktif = TahunAjaran::aktif(); 

        // REVISI DOSEN (Poin 4): Tahun ajaran yang dipakai untuk laporan kini
        // mengikuti pilihan admin/wali kelas (bukan selalu tahun aktif).
        $tahunAjaranTerpilih = $tahun_ajaran_id
            ? (TahunAjaran::find($tahun_ajaran_id) ?? $tahunAktif)
            : $tahunAktif;

        $data_siswa = []; 
        $presensi_matriks = []; 
        $kelas_terpilih = null; 

        if ($kelas_id) { 

            $kelas_terpilih = Kelas::find($kelas_id); 

            $melihatTahunAktif = $tahunAjaranTerpilih && $tahunAktif
                && $tahunAjaranTerpilih->id == $tahunAktif->id;

            if ($melihatTahunAktif) {
                // Tahun ajaran aktif: siswa diambil dari data terkini (perilaku lama, tidak diubah)
                $data_siswa = Siswa::where('kelas_id', $kelas_id) 
                    ->where('status', 'Aktif') 
                    ->orderBy('nama_siswa', 'asc') 
                    ->get(); 
            } else {
                // REVISI DOSEN (Poin 4): Tahun ajaran LAMA -- siswa.kelas_id sekarang
                // sudah tidak mencerminkan kelas siswa di tahun itu (mis. siswa yang
                // dulu di 2A sudah naik ke 3A). Maka daftar siswa direkonstruksi dari
                // jejak tabel 'presensis' (siapa saja yang tercatat kelas_id +
                // tahun_ajaran_id ini), bukan dari siswas.kelas_id saat ini.
                // Catatan/batasan: siswa yang pernah di kelas ini di tahun tsb tapi
                // tidak pernah absen sama sekali tidak akan muncul di sini.
                $siswaIdHistoris = Presensi::where('kelas_id', $kelas_id)
                    ->where('tahun_ajaran_id', $tahunAjaranTerpilih->id ?? 0)
                    ->distinct()
                    ->pluck('siswa_id');

                $data_siswa = Siswa::whereIn('id', $siswaIdHistoris)
                    ->orderBy('nama_siswa', 'asc')
                    ->get();
            }

            // PERBAIKAN: Sebelumnya di sini dibangun daftar 'rfid_targets' +
            // 'manual_targets' (kode semu 'MANUAL_<id>') hanya untuk mencocokkan
            // riwayat presensi ke siswa lewat string rfid_code. Sekarang cukup
            // filter langsung berdasarkan foreign key 'siswa_id' yang sudah ada.
            $log_presensi = Presensi::where('tahun_ajaran_id', $tahunAjaranTerpilih->id ?? 0) 
                ->whereIn('siswa_id', $data_siswa->pluck('id')) 
                ->whereMonth('created_at', $bulan) 
                ->whereYear('created_at', $tahun) 
                ->get(); 

            foreach ($log_presensi as $p) { 

                $tanggal = sprintf('%02d', (int) date('d', strtotime($p->created_at))); 

                $presensi_matriks[$p->siswa_id][$tanggal] = $p->status; 

            }

        }

        return [ 
            'data_siswa'=>$data_siswa, 
            'presensi_matriks'=>$presensi_matriks, 
            'kelas_terpilih'=>$kelas_terpilih, 
            'bulan'=>$bulan, 
            'tahun'=>$tahun, 
            'tahunAktif'=>$tahunAktif, 
            'tahunAjaranTerpilih'=>$tahunAjaranTerpilih, 
        ];
    }

    // ==========================================
    // FUNGSI LOG NOTIFIKASI
    // ==========================================
    public function logNotifikasi(Request $request)
    {
        // Bangun query dengan filter pencarian & status (bisa dikombinasikan)
        $query = LogNotifikasi::query();

        // Pencarian bebas: nama siswa, kode RFID, atau nomor WA orang tua
        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('rfid_code', 'like', "%{$search}%")
                  ->orWhere('no_hp_orang_tua', 'like', "%{$search}%");
            });
        }

        // Filter status pengiriman WhatsApp (Berhasil / Gagal)
        $statusKirim = $request->input('status_kirim');
        if (!empty($statusKirim) && in_array($statusKirim, ['Berhasil', 'Gagal'])) {
            $query->where('status_notifikasi', $statusKirim);
        }

        // Filter status kehadiran (Hadir, Terlambat, Sakit, Izin, Alpa)
        $statusPresensi = $request->input('status_presensi');
        if (!empty($statusPresensi)) {
            $query->where('status_presensi', $statusPresensi);
        }

        // Filter tanggal (opsional)
        if (!empty($request->input('tanggal'))) {
            $query->whereDate('created_at', $request->input('tanggal'));
        }

        // 3. Ambil data log tersisa untuk ditampilkan di tabel, tetap sertakan query string filter di pagination
        $logs = $query->latest()->paginate(20)->withQueryString(); 

        // 4. KALKULASI DATA STATISTIK UNTUK BLOK CARD WIDGET (tetap dihitung dari total keseluruhan, bukan hasil filter)
        $totalLog      = LogNotifikasi::count(); 
        $totalBerhasil = LogNotifikasi::where('status_notifikasi', 'Berhasil')->count(); 
        $totalGagal    = LogNotifikasi::where('status_notifikasi', 'Gagal')->count(); 
        $totalHariIni  = LogNotifikasi::whereDate('created_at', Carbon::today())->count(); 

        return view('notifikasi.index', compact( 
            'logs', 
            'totalLog', 
            'totalBerhasil', 
            'totalGagal', 
            'totalHariIni' 
        ));
    }

    // FUNGSI HAPUS DATA LOG SECARA MANUAL
    public function destroyLog($id)
    {
        LogNotifikasi::findOrFail($id)->delete(); 
        return redirect()->route('log.notifikasi') 
            ->with('sukses', 'Log riwayat notifikasi berhasil dihapus dari sistem.'); 
    }

    // ==========================================
    // FUNGSI DASHBOARD (DENGAN FILTER MULTI-ROLE: ADMIN & GURU/WALI KELAS)
    // ==========================================
    public function dashboard(Request $request)
    {
        $user = auth()->user(); 
        $hariIni = Carbon::today()->toDateString(); 
        $tahunAktif = TahunAjaran::aktif(); 

        // Guard: jika belum ada tahun ajaran yang aktif, arahkan ke halaman pengaturannya
        // agar tidak terjadi fatal error 500 saat $tahunAktif->id dipanggil di bawah.
        if (!$tahunAktif) {
            return redirect()->route('tahunajaran.index')
                ->with('error', 'Belum ada Tahun Ajaran yang aktif. Silakan aktifkan atau tambahkan Tahun Ajaran terlebih dahulu.');
        }

        // Inisialisasi variabel untuk view custom Wali Kelas agar terhindar dari error undefined
        $belumHadirList = collect(); 
        $riwayatPresensi = collect(); 

        // 1. FILTER DATA UTAMA BERDASARKAN ROLE USER LOGIN
        if ($user->role == 'guru') { 
            // kelas yang diampu wali kelas
            $kelasId = $user->kelas_id; 
            $kelas = Kelas::find($kelasId); 

            if (!$kelas) { 
                abort(403, 'Akun belum memiliki kelas.'); 
            }

            // Ambil info data kelas khusus yang diampu oleh wali kelas tersebut beserta relasi hitungannya
            $daftar_kelas = Kelas::where('id', $kelasId)->withCount([ 
                'siswa as siswa_count' => function($q) { 
                    $q->where('status', 'Aktif'); 
                }, 'siswa as hadir_count' => function($query) use ($hariIni) { 
                    $query->whereHas('presensi', function($q) use ($hariIni) { 
                        $q->whereDate('created_at', $hariIni) 
                          ->whereIn('status', ['Hadir', 'Terlambat']); 
                    });
                }
            ])->get(); 

            // Scope data siswa & riwayat log hari ini terfilter sesuai kelas_id Guru
            $semua_siswa = Siswa::where('kelas_id', $kelasId)->where('status', 'Aktif')->get(); 
            $totalSiswa = $semua_siswa->count(); 
            
            $riwayat_presensi = Presensi::with(['siswa.kelas']) 
                ->where('tahun_ajaran_id', $tahunAktif->id) 
                ->where('kelas_id', $kelasId) 
                ->whereDate('created_at', $hariIni) 
                ->orderBy('created_at', 'desc') 
                ->get(); 

            // FITUR REKOMENDASI DOSEN: Data spesifik pendukung UI Dashboard Wali Kelas
            $riwayatPresensi = $riwayat_presensi; // Alias camelCase untuk sinkronisasi layout Blade wali kelas[cite: 2]
            
            // Ambil daftar ID siswa di kelas ini yang sudah melakukan presensi hari ini
            $idSiswaSudahPresensi = $riwayat_presensi->pluck('siswa_id')->toArray(); 
            
            // Dapatkan daftar siswa yang belum hadir hari ini
            $belumHadirList = Siswa::where('kelas_id', $kelasId) 
                ->where('status', 'Aktif') 
                ->whereNotIn('id', $idSiswaSudahPresensi) 
                ->orderBy('nama_siswa', 'asc') 
                ->get(); 

        } else {
            // Role Admin: Load keseluruhan data sekolah tanpa filter parameter kelas_id
           $daftar_kelas = Kelas::with('siswa')->get(); 

           foreach ($daftar_kelas as $kelas) { 

             $kelas->siswa_count = Siswa::where('kelas_id', $kelas->id) 
            ->where('status', 'Aktif') 
            ->count(); 

               $kelas->hadir_count = Presensi::where('kelas_id', $kelas->id) 
               ->whereDate('created_at', $hariIni) 
              ->whereIn('status', ['Hadir','Terlambat']) 
             ->count(); 
           }

            $semua_siswa = Siswa::where('status', 'Aktif')->get(); 
            $totalSiswa = $semua_siswa->count(); 
            
            $riwayat_presensi = Presensi::with(['siswa.kelas']) 
                ->where('tahun_ajaran_id', $tahunAktif->id) 
                ->whereDate('created_at', $hariIni) 
                ->orderBy('created_at', 'desc') 
                ->get(); 
        }

        // 2. KALKULASI COUNTER DATA WIDGET BOX (Sinkron dengan scope data di atas)
        $siswaHadirHariIni = $riwayat_presensi->where('status', 'Hadir')->count(); 
        $siswaTerlambat    = $riwayat_presensi->where('status', 'Terlambat')->count(); 
        $siswaSakit        = $riwayat_presensi->where('status', 'Sakit')->count(); 
        $siswaIzin         = $riwayat_presensi->where('status', 'Izin')->count(); 
        
        $siswaBelumHadir   = max(0, $totalSiswa - ($siswaHadirHariIni + $siswaTerlambat + $siswaSakit + $siswaIzin)); 

        /*
        |--------------------------------------------------------------------------
        | GRAFIK MINGGUAN DINAMIS (DENGAN KONDISI DI-FILTER OLEH SCOPE ROLE)
        |--------------------------------------------------------------------------
        |
        */
        $label_grafik_mingguan = []; 
        $data_grafik_mingguan = []; 

        $hariSetting = [ 
        1 => 'senin', 2 => 'selasa', 3 => 'rabu', 4 => 'kamis', 5 => 'jumat', 6 => 'sabtu', 0 => 'minggu'
        ];

        // Jumlah hari yang ditampilkan di grafik SEKARANG MENGIKUTI Pengaturan > Hari Aktif,
        // bukan lagi di-hardcode ke angka 5. Kalau admin aktifkan 7 hari, grafik tampil 7 titik;
        // kalau cuma 5 hari yang aktif, grafik tampil 5 titik saja.
        $jumlahHariAktif = collect($hariSetting)->filter(fn($namaHari) => Setting::getValue($namaHari))->count();
        if ($jumlahHariAktif < 1) {
            $jumlahHariAktif = 7; // fallback aman kalau belum ada satupun hari yang diatur aktif
        }

        $today = Carbon::now('Asia/Jakarta'); 
        $tanggalGrafik = []; 

        for ($i = 0; $i < 14; $i++) { 
            $tanggal = $today->copy()->subDays($i); 
            $indexHari = $tanggal->dayOfWeek; 
            $namaHari = $hariSetting[$indexHari]; 

            if (Setting::getValue($namaHari)) { 
                $tanggalGrafik[] = $tanggal; 
            } 

            if (count($tanggalGrafik) == $jumlahHariAktif) { 
                break; 
            }
        }

        $tanggalGrafik = array_reverse($tanggalGrafik); 
        $kumpulanTanggal = collect($tanggalGrafik)->map(fn($t) => $t->toDateString())->toArray(); 

        // Query Dasar Mingguan
        // Catatan: khusus status 'Hadir' saja (bukan digabung 'Terlambat'),
        // karena bar 'Terlambat' sudah dihitung terpisah di bawah. Kalau digabung
        // di sini, siswa yang Terlambat akan ikut terhitung di kedua bar sekaligus.
        $queryMingguan = Presensi::where('tahun_ajaran_id', $tahunAktif->id) 
            ->where('status', 'Hadir') 
            ->whereIn(DB::raw('DATE(created_at)'), $kumpulanTanggal); 

        // Sisapkan filter kelas jika yang mengakses adalah Guru
        if ($user->role == 'guru') { 
            $queryMingguan->where('kelas_id', $user->kelas_id); 
        }

        $dataPresensi = $queryMingguan->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('count(*) as total')) 
            ->groupBy('tanggal') 
            ->pluck('total', 'tanggal'); 

        foreach ($tanggalGrafik as $tanggal) { 
            $strTanggal = $tanggal->toDateString(); 
            $label_grafik_mingguan[] = $tanggal->translatedFormat('D'); 
            $data_grafik_mingguan[] = $dataPresensi[$strTanggal] ?? 0; 
        }

        // Query khusus status Terlambat (dipakai untuk bar "Terlambat" di grafik mingguan)
        $queryTerlambatMingguan = Presensi::where('tahun_ajaran_id', $tahunAktif->id) 
            ->where('status', 'Terlambat') 
            ->whereIn(DB::raw('DATE(created_at)'), $kumpulanTanggal); 

        if ($user->role == 'guru') { 
            $queryTerlambatMingguan->where('kelas_id', $user->kelas_id); 
        }

        $dataTerlambatPresensi = $queryTerlambatMingguan->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('count(*) as total')) 
            ->groupBy('tanggal') 
            ->pluck('total', 'tanggal'); 

        $data_terlambat_mingguan = []; 
        foreach ($tanggalGrafik as $tanggal) { 
            $strTanggal = $tanggal->toDateString(); 
            $data_terlambat_mingguan[] = $dataTerlambatPresensi[$strTanggal] ?? 0; 
        }

        /*
        |--------------------------------------------------------------------------
        | GRAFIK BULANAN (DENGAN KONDISI DI-FILTER OLEH SCOPE ROLE)
        |--------------------------------------------------------------------------
        |
        */
        $bulanIni = Carbon::now(); 
        $jumlahHariBulanIni = $bulanIni->daysInMonth; 

        $tanggalMulai = $bulanIni->startOfMonth()->toDateString(); 
        $tanggalSelesai = $bulanIni->endOfMonth()->toDateString(); 

        // Query Dasar Bulanan
        // Sama seperti grafik mingguan: hanya status 'Hadir' murni, karena
        // 'Terlambat' sudah punya bar/dataset sendiri (dihitung terpisah di bawah).
        $queryBulanan = Presensi::where('tahun_ajaran_id', $tahunAktif->id) 
            ->where('status', 'Hadir') 
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalMulai, $tanggalSelesai]); 

        // Sisapkan filter kelas jika yang mengakses adalah Guru
        if ($user->role == 'guru') { 
            $queryBulanan->where('kelas_id', $user->kelas_id); 
        }

        $dataPresensiBulanan = $queryBulanan->select(DB::raw('DAY(created_at) as tanggal'), DB::raw('count(*) as total')) 
            ->groupBy('tanggal') 
            ->pluck('total', 'tanggal'); 

        $data_grafik_bulanan = []; 
        $labels_bulanan = []; 

        for ($d = 1; $d <= $jumlahHariBulanIni; $d++) { 
            $data_grafik_bulanan[] = $dataPresensiBulanan[$d] ?? 0; 
            $labels_bulanan[] = $d; 
        }

        // Query khusus status Terlambat (dipakai untuk bar "Terlambat" di grafik bulanan)
        $queryTerlambatBulanan = Presensi::where('tahun_ajaran_id', $tahunAktif->id) 
            ->where('status', 'Terlambat') 
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalMulai, $tanggalSelesai]); 

        if ($user->role == 'guru') { 
            $queryTerlambatBulanan->where('kelas_id', $user->kelas_id); 
        }

        $dataTerlambatPresensiBulanan = $queryTerlambatBulanan->select(DB::raw('DAY(created_at) as tanggal'), DB::raw('count(*) as total')) 
            ->groupBy('tanggal') 
            ->pluck('total', 'tanggal'); 

        $data_terlambat_bulanan = []; 
        for ($d = 1; $d <= $jumlahHariBulanIni; $d++) { 
            $data_terlambat_bulanan[] = $dataTerlambatPresensiBulanan[$d] ?? 0; 
        }

        $log_notifikasi = collect(); 
        if (request()->routeIs('log.notifikasi')) { 
            $queryLog = Presensi::with(['siswa.kelas']) 
                ->whereDate('created_at', $hariIni) 
                ->orderBy('created_at', 'desc'); 
                
            if ($user->role == 'guru') { 
                $queryLog->where('kelas_id', $user->kelas_id); 
            }
            $log_notifikasi = $queryLog->get(); 
        }

        // --- BLOK PENAMBAHAN KONDISI DATA HAK AKSES GURU ---
        if($user->role == 'guru'){ 
            $kelas = $user->kelas; 
            $totalSiswa = \App\Models\Siswa::where('kelas_id', $kelas->id)->where('status', 'Aktif')->count(); 
            
            $hadirHariIni = \App\Models\Presensi::whereDate('created_at', today()) 
                ->where('kelas_id', $kelas->id) 
                ->whereIn('status', ['Hadir', 'Terlambat']) 
                ->count(); 

            $terlambat = \App\Models\Presensi::whereDate('created_at', today()) 
                ->where('kelas_id', $kelas->id) 
                ->where('status', 'Terlambat') 
                ->count(); 

            // PERBAIKAN: Sebelumnya dihitung ulang dengan rumus
            // 'totalSiswa - hadirHariIni' yang hanya mengurangi status
            // Hadir/Terlambat saja, sehingga siswa berstatus Sakit/Izin ikut
            // terhitung "Belum Hadir" di angka widget ini -- padahal mereka
            // TIDAK muncul di daftar $belumHadirList di bawah (yang sudah
            // benar mengecualikan siswa dgn status presensi apapun). Akibatnya
            // angka & daftar di halaman yang sama bisa tidak sinkron.
            // Sekarang angka ini diambil langsung dari $belumHadirList supaya
            // keduanya selalu konsisten satu sama lain.
            $belumHadir = $belumHadirList->count(); 
        } else {
            // Nilai default/fallback untuk admin agar variabel tidak error saat di-compact
            $hadirHariIni = $siswaHadirHariIni; 
            $terlambat = $siswaTerlambat; 
            $belumHadir = $siswaBelumHadir; 
        }

        return view('dashboard.index', [ 
            'daftar_kelas' => $daftar_kelas, 
            'totalSiswa' => $totalSiswa, 
            'siswaHadirHariIni' => $siswaHadirHariIni, 
            'siswaTerlambat' => $siswaTerlambat, 
            'siswaSakit' => $siswaSakit, 
            'siswaIzin' => $siswaIzin, 
            'siswaBelumHadir' => $siswaBelumHadir, 
            'data_grafik_mingguan' => $data_grafik_mingguan, 
            'label_grafik_mingguan' => $label_grafik_mingguan, 
            'data_grafik_bulanan' => $data_grafik_bulanan, 
            'labels_bulanan' => $labels_bulanan, 
            'data_terlambat_mingguan' => $data_terlambat_mingguan, 
            'data_terlambat_bulanan' => $data_terlambat_bulanan, 
            'riwayat_presensi' => $riwayat_presensi, 
            'log_notifikasi' => $log_notifikasi, 
            // Data tambahan untuk kebutuhan view wali kelas/guru
            'hadirHariIni' => $hadirHariIni, 
            'terlambat' => $terlambat, 
            'belumHadir' => $belumHadir, 
            'belumHadirList' => $belumHadirList, 
            'riwayatPresensi' => $riwayatPresensi, 
        ]);
    }

    // ==========================================
    // FUNGSI INPUT MANUAL: DI-FILTER OLEH SCOPE ROLE (GURU vs ADMIN)
    // ==========================================
    public function inputManual(Request $request)
    {
        $user = auth()->user(); 

        // 2. INPUT MANUAL: Membatasi drop-down agar Wali Kelas hanya melihat data kelas miliknya sendiri
        if ($user->role == 'guru') { 
            $daftar_kelas = Kelas::where('id', $user->kelas_id)->get(); 
            $kelas_id = $user->kelas_id; 
        } else {
            $daftar_kelas = Kelas::orderBy('nama_kelas', 'asc')->get(); 
            $kelas_id = $request->input('kelas_id'); 
        }

        $tanggal = $request->input('tanggal', date('Y-m-d')); 
        $semua_siswa = collect(); 
        $tahunAktif = TahunAjaran::aktif(); 

        if ($kelas_id) { 
            $semua_siswa = Siswa::where('kelas_id', $kelas_id) 
                ->where('status', 'Aktif') 
                ->orderBy('nama_siswa') 
                ->get(); 
            
            // PERBAIKAN: key by 'siswa_id' (foreign key asli), bukan lagi
            // 'rfid_code' + kode semu 'MANUAL_<id>'.
            $data_presensi = Presensi::where('tahun_ajaran_id', $tahunAktif->id) 
                ->where('kelas_id', $kelas_id) 
                ->whereDate('created_at', $tanggal) 
                ->get() 
                ->keyBy('siswa_id'); 

            foreach ($semua_siswa as $siswa) { 
                if (isset($data_presensi[$siswa->id])) { 
                    $siswa->status_hari_ini = $data_presensi[$siswa->id]->status; 
                    $siswa->keterangan_hari_ini = $data_presensi[$siswa->id]->keterangan; 
                    // Baris yang sumbernya 'rfid' dikunci di form: tidak boleh ditimpa manual.
                    // Data lama (sebelum kolom 'sumber' ada) dianggap 'manual' agar tetap bisa dikoreksi.
                    $siswa->terkunci_rfid = ($data_presensi[$siswa->id]->sumber === 'rfid'); 
                } else {
                    $siswa->status_hari_ini = 'Belum Absen'; 
                    $siswa->keterangan_hari_ini = ''; 
                    $siswa->terkunci_rfid = false; 
                }
            }
        }
        return view('presensi.manual', compact('daftar_kelas', 'semua_siswa', 'kelas_id', 'tanggal')); 
    }

    // ==========================================
    // FUNGSI SIMPAN INPUT MANUAL: DATA TIDAK AKAN PERNAH LOGKOR/GANDA
    // ==========================================
    public function storeManual(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif(); 

        $request->validate([ 
            'tanggal' => 'required|date',  
            'presensi' => 'required|array' 
        ]); 

        DB::beginTransaction(); 
        try {
            $dilewati = []; // Nama siswa yang statusnya sudah terkunci hasil scan RFID (dilewati, tidak ditimpa)

            foreach ($request->presensi as $siswa_id => $data) { 
                $status = in_array($data['status'], ['Belum Absen', 'Belum Hadir']) ? 'Alpa' : $data['status']; 
                $siswa = Siswa::find($siswa_id); 
                if (!$siswa) continue; 

                // PERBAIKAN: Pencarian & penyimpanan presensi manual sekarang
                // langsung memakai foreign key 'siswa_id', bukan lagi kode semu
                // 'MANUAL_<id>' yang sebelumnya dipakai untuk mengakali kolom
                // rfid_code agar siswa tanpa kartu RFID tetap bisa "dicocokkan".
                $presensiHariIni = Presensi::where('tahun_ajaran_id', $tahunAktif->id) 
                    ->whereDate('created_at', $request->tanggal) 
                    ->where('siswa_id', $siswa->id) 
                    ->first(); 

                // Presensi hasil tap RFID kini BOLEH diedit lewat menu manual
                // (tidak dikunci lagi di tampilan) - TAPI hanya kalau admin
                // benar-benar menyentuh/mengubah baris siswa tsb (flag
                // 'diubah' dikirim dari JS saat radio/keterangan/dokumen
                // diubah). Kalau baris tidak disentuh (mis. karena admin
                // pakai tombol "Hadir Semua" atau submit tanpa membukanya),
                // data hasil tap (misal status Terlambat) TETAP tidak ditimpa,
                // supaya tidak hilang begitu saja saat submit massal.
                $diubahManual = !empty($data['diubah']) && $data['diubah'] == '1'; 

                if ($presensiHariIni && $presensiHariIni->sumber === 'rfid' && !$diubahManual) { 
                    $dilewati[] = $siswa->nama_siswa; 
                    continue; 
                }

                if ($presensiHariIni) { 
                    $presensiHariIni->update([ 
                        'status' => $status, 
                        'sumber' => 'manual', 
                        'kelas_id' => $siswa->kelas_id, 
                        'keterangan' => $data['keterangan'] ?? null, 
                    ]); 
                } else {
                    Presensi::create([ 
                        'rfid_code' => $siswa->rfid_code, 
                        'siswa_id' => $siswa->id, 
                        'kelas_id' => $siswa->kelas_id, 
                        'tahun_ajaran_id'  => $tahunAktif->id, 
                        'status' => $status, 
                        'sumber' => 'manual', 
                        'keterangan' => $data['keterangan'] ?? null, 
                        'created_at' => $request->tanggal . ' ' . date('H:i:s'),  
                    ]); 
                }
            }
            
            DB::commit(); 

            $pesan = 'Seluruh data presensi manual kelas berhasil diperbarui dan masuk ke rekap laporan bulanan!'; 
            if (!empty($dilewati)) { 
                $pesan .= ' Catatan: ' . count($dilewati) . ' siswa (' . implode(', ', $dilewati) . ') dilewati karena statusnya sudah terkunci hasil scan RFID.'; 
            } 

            return redirect()->back()->with('sukses', $pesan); 
            
        } catch (\Exception $e) {
            DB::rollBack(); 
            return redirect()->back()->with('error', 'Gagal menyimpan presensi manual: ' . $e->getMessage()); 
        }
    }

    // ==========================================
    // FUNGSI SCAN RFID VIA WEB (MODERN ULTRA-FAST RESPONSE ENGINE WITH ENQUEUED WA GATWAY)
    // ==========================================
    public function tapRFIDWeb(Request $request, FonnteService $fonnte)
    {
        $request->validate([ 
            'rfid_code' => 'required|string' 
        ]); 

        $rfidCode = trim($request->rfid_code); 

        // Optimasi Kueri Kolom Terseleksi untuk Response Tercepat
        $siswa = Siswa::select('id', 'nama_siswa', 'kelas_id', 'foto', 'no_hp_orang_tua', 'rfid_code', 'status') 
            ->with(['kelas' => function($query) { 
                $query->select('id', 'nama_kelas'); 
            }]) 
            ->where('rfid_code', $rfidCode) 
            ->where('status', 'Aktif') 
            ->first(); 
            
        if (!$siswa) { 
            return response()->json([ 
                'success' => false, 
                'message' => 'Gagal Presensi! Kartu RFID (' . $rfidCode . ') tidak terdaftar.' 
            ], 404); 
        }

        $sudahPresensi = Presensi::where('siswa_id', $siswa->id) 
            ->whereDate('created_at', Carbon::today()) 
            ->exists(); 
            
        if ($sudahPresensi) { 
            return response()->json([ 
                'success' => false, 
                'message' => 'Info: Ananda ' . $siswa->nama_siswa . ' sudah merekam presensi hari ini.' 
            ], 400); 
        }

        $jamSekarang = Carbon::now('Asia/Jakarta')->format('H:i'); 
        $hari = strtolower(Carbon::now('Asia/Jakarta')->locale('id')->dayName); 
        $hariAktif = Setting::getValue($hari); 

        if (!$hariAktif) { 
            return response()->json([ 
                'success' => false, 
                'message' => 'Hari ini presensi dinonaktifkan oleh Administrator.' 
            ], 403); 
        }

        $jamMulai   = Setting::getValue('jam_mulai'); 
        $batasHadir = Setting::getValue('batas_hadir'); 
        $jamTutup   = Setting::getValue('jam_tutup'); 

        if ($jamSekarang < $jamMulai) { 
            return response()->json([ 
                'success' => false, 
                'message' => 'Presensi belum dibuka. Silakan kembali pukul ' . $jamMulai 
            ], 403); 
        }

        if ($jamSekarang > $jamTutup) { 
            return response()->json([ 
                'success' => false, 
                'message' => 'Presensi telah ditutup pada pukul ' . $jamTutup 
            ], 403); 
        }

        $status = ($jamSekarang > $batasHadir) ? 'Terlambat' : 'Hadir'; 
        $tahunAktif = TahunAjaran::aktif(); 

        $presensiBaru = Presensi::create([ 
            'rfid_code'        => $rfidCode, 
            'siswa_id'         => $siswa->id, 
            'kelas_id'         => $siswa->kelas_id, 
            'tahun_ajaran_id'  => $tahunAktif?->id, 
            'status'           => $status,
            'sumber'           => 'rfid', 
        ]); 

        // Template pesan aktif: kustom dari halaman Pengaturan, atau format bawaan bila dikosongkan
        $templatePesan = Setting::templatePesanAktif();

        $pesan = strtr($templatePesan, [
            '{nama_siswa}'   => $siswa->nama_siswa,
            '{kelas}'        => $siswa->kelas->nama_kelas ?? '-',
            '{tanggal}'      => Carbon::now('Asia/Jakarta')->translatedFormat('d F Y'),
            '{jam}'          => Carbon::now('Asia/Jakarta')->format('H:i'),
            '{status}'       => $status,
            '{nama_sekolah}' => Setting::getValue('nama_sekolah', 'SD Negeri Tengah 03 Jakarta Timur'),
        ]);

        $noHpOrangTua = $siswa->no_hp_orang_tua; 
        $namaSiswa = $siswa->nama_siswa; 

                  // ==========================================================
// MODE PENGIRIMAN NOTIFIKASI WHATSAPP
// ==========================================================

$waMode = Setting::getValue('wa_mode', 'queue');

if ($waMode === 'queue') {

    // MODE 1: QUEUE / BACKGROUND
    // Masuk database queue dan diproses queue:work.
    // Delay 3-6 detik tetap di dalam Job.
    SendWhatsAppNotification::dispatch(
        $noHpOrangTua,
        $pesan,
        $rfidCode,
        $namaSiswa,
        $status,
        $siswa->id,
        $presensiBaru->id
    )->onConnection('database');

} elseif ($waMode === 'direct') {

    // MODE 2: LANGSUNG
    // Job dijalankan setelah response presensi dikirim ke browser.
    // Dengan demikian data siswa dapat tampil lebih dahulu.
    SendWhatsAppNotification::dispatchAfterResponse(
        $noHpOrangTua,
        $pesan,
        $rfidCode,
        $namaSiswa,
        $status,
        $siswa->id,
        $presensiBaru->id
    )->onConnection('sync');
}

// Jika wa_mode = "off", tidak ada Job WhatsApp yang dijalankan.

        // Kembalikan objek data JSON secara kilat ke Terminal AJAX Pembaca RFID
        return response()->json([ 
            'success' => true, 
            'nama'    => $siswa->nama_siswa, 
            'kelas'   => $siswa->kelas->nama_kelas ?? '-', 
            'status'  => $status, 
            'jam'     => Carbon::now('Asia/Jakarta')->format('H:i'), 
            'foto'    => $siswa->foto 
        ]); 
    }

    public function truncateNotifikasi()
{
    // Mengosongkan seluruh data pada tabel log_notifikasis
    DB::table('log_notifikasis')->truncate();

    return redirect()->back()->with('sukses', 'Seluruh riwayat log notifikasi berhasil dibersihkan.');
}

public function laporanKetidakhadiran(Request $request)
{
    $user = auth()->user();

    if ($user->role == 'guru') {
        $daftar_kelas = Kelas::where('id', $user->kelas_id)->get();
        $kelas_id = $user->kelas_id;
    } else {
        $daftar_kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $kelas_id = $request->input('kelas_id');
    }

    $bulan = (int) $request->input('bulan', date('m'));
    $tahun = (int) $request->input('tahun', date('Y'));

    $rekap = Siswa::where('kelas_id', $kelas_id)
        ->where('status', 'Aktif')
        ->withCount([
            'presensi as sakit' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun)
                  ->where('status', 'Sakit');
            },
            'presensi as izin' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun)
                  ->where('status', 'Izin');
            },
            'presensi as alpa' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('created_at', $bulan)
                  ->whereYear('created_at', $tahun)
                  ->where('status', 'Alpa');
            },
        ])
        ->orderBy('nama_siswa', 'asc')
        ->get();

    return view('laporan.ketidakhadiran', compact('daftar_kelas', 'rekap', 'kelas_id', 'bulan', 'tahun'));
}

}