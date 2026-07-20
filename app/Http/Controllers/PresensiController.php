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
use App\Jobs\SendWhatsAppNotification; // <-- PANGGILAN CLASS JOB BARU[cite: 2]

use App\Exports\RekapBulananExport;   // <-- TAMBAHKAN BARIS INI[cite: 2]

use Maatwebsite\Excel\Facades\Excel;

class PresensiController extends Controller
{
    // ==========================================
    // FUNGSI UTAMA: REKAP LAPORAN BULANAN (SINKRONISASI MATRIKS 100% REAL-TIME)
    // ==========================================
    public function laporan(Request $request)
    {
        $user = auth()->user(); //[cite: 2]

        // 3. REKAP LAPORAN: Proteksi hak akses agar Wali Kelas langsung membuka kelasnya sendiri
        if ($user->role == 'guru') { //[cite: 2]
            $daftar_kelas = Kelas::where('id', $user->kelas_id)->get(); //[cite: 2]
            $kelas_id = $user->kelas_id; //[cite: 2]
        } else {
            $daftar_kelas = Kelas::orderBy('nama_kelas', 'asc')->get(); //[cite: 2]
            $kelas_id = $request->input('kelas_id'); //[cite: 2]
        }
        
        $bulan = (int) $request->input('bulan', date('m')); //[cite: 2]
        $tahun = (int) $request->input('tahun', date('Y')); //[cite: 2]

        $data = $this->getDataLaporan( //[cite: 2]
            $kelas_id, //[cite: 2]
            $bulan, //[cite: 2]
            $tahun //[cite: 2]
        );

        return view( //[cite: 2]
            'laporan.index', //[cite: 2]
            array_merge( //[cite: 2]
                [ //[cite: 2]
                    'daftar_kelas' => $daftar_kelas //[cite: 2]
                ], //[cite: 2]
                $data //[cite: 2]
            ) //[cite: 2]
        );
    }

    // ==========================================
    // EXPORT REKAP LAPORAN BULANAN KE EXCEL
    // ==========================================
    public function exportLaporan(Request $request)
    {
        $user = auth()->user();

        if ($user->role == 'guru') {
            $kelas_id = $user->kelas_id;
        } else {
            $kelas_id = $request->input('kelas_id');
        }

        $bulan = (int) $request->input('bulan', date('m'));
        $tahun = (int) $request->input('tahun', date('Y'));

        $data = $this->getDataLaporan($kelas_id, $bulan, $tahun);

        $namaBulan = Carbon::create(null, $bulan)->translatedFormat('F');
        $namaKelas = $data['kelas_terpilih']->nama_kelas ?? 'Semua-Kelas';
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

    private function getDataLaporan($kelas_id, $bulan, $tahun)
    {
        $tahunAktif = TahunAjaran::aktif(); //[cite: 2]

        $data_siswa = []; //[cite: 2]
        $presensi_matriks = []; //[cite: 2]
        $kelas_terpilih = null; //[cite: 2]

        if ($kelas_id) { //[cite: 2]

            $kelas_terpilih = Kelas::find($kelas_id); //[cite: 2]

            $data_siswa = Siswa::where('kelas_id', $kelas_id) //[cite: 2]
                ->where('status', 'Aktif') //[cite: 2]
                ->orderBy('nama_siswa', 'asc') //[cite: 2]
                ->get(); //[cite: 2]

            $rfid_targets = $data_siswa->pluck('rfid_code')->filter()->toArray(); //[cite: 2]

            $manual_targets = $data_siswa->map(function ($s) { //[cite: 2]
                return 'MANUAL_'.$s->id; //[cite: 2]
            })->toArray(); //[cite: 2]

            $all_targets = array_merge($rfid_targets,$manual_targets); //[cite: 2]

            $log_presensi = Presensi::where('tahun_ajaran_id',$tahunAktif->id) //[cite: 2]
                ->where(function($query) use($all_targets,$kelas_id){ //[cite: 2]

                    $query->whereIn('rfid_code',$all_targets) //[cite: 2]
                          ->orWhere('kelas_id',$kelas_id); //[cite: 2]

                }) //[cite: 2]
                ->whereMonth('created_at',$bulan) //[cite: 2]
                ->whereYear('created_at',$tahun) //[cite: 2]
                ->get(); //[cite: 2]

            $siswa_by_key=[]; //[cite: 2]

            foreach($data_siswa as $siswa){ //[cite: 2]

                $key=!empty($siswa->rfid_code) //[cite: 2]
                    ?$siswa->rfid_code //[cite: 2]
                    :'MANUAL_'.$siswa->id; //[cite: 2]

                $siswa_by_key[$key]=$siswa; //[cite: 2]

            }

            foreach($log_presensi as $p){ //[cite: 2]

                $tanggal=sprintf('%02d',(int)date('d',strtotime($p->created_at))); //[cite: 2]

                if(isset($siswa_by_key[$p->rfid_code])){ //[cite: 2]

                    $siswaPemilik=$siswa_by_key[$p->rfid_code]; //[cite: 2]

                    $presensi_matriks[$siswaPemilik->id][$tanggal]=$p->status; //[cite: 2]

                }

            }

        }

        return [ //[cite: 2]
            'data_siswa'=>$data_siswa, //[cite: 2]
            'presensi_matriks'=>$presensi_matriks, //[cite: 2]
            'kelas_terpilih'=>$kelas_terpilih, //[cite: 2]
            'bulan'=>$bulan, //[cite: 2]
            'tahun'=>$tahun, //[cite: 2]
            'tahunAktif'=>$tahunAktif //[cite: 2]
        ];
    }

    // ==========================================
    // PERBAIKAN UTAMA: FUNGSI LOG NOTIFIKASI DENGAN AUTO-CLEANUP 24 JAM
    // ==========================================
    public function logNotifikasi(Request $request)
    {
        // 1. Eksekusi query penghapusan log yang umurnya sudah melebihi 24 jam dari detik ini
        LogNotifikasi::where('created_at', '<', Carbon::now()->subDay())->delete(); //[cite: 2]

        // 2. Bangun query dengan filter pencarian & status (bisa dikombinasikan)
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
        $logs = $query->latest()->paginate(20)->withQueryString(); //[cite: 2]

        // 4. KALKULASI DATA STATISTIK UNTUK BLOK CARD WIDGET (tetap dihitung dari total keseluruhan, bukan hasil filter)
        $totalLog      = LogNotifikasi::count(); //[cite: 2]
        $totalBerhasil = LogNotifikasi::where('status_notifikasi', 'Berhasil')->count(); //[cite: 2]
        $totalGagal    = LogNotifikasi::where('status_notifikasi', 'Gagal')->count(); //[cite: 2]
        $totalHariIni  = LogNotifikasi::whereDate('created_at', Carbon::today())->count(); //[cite: 2]

        return view('notifikasi.index', compact( //[cite: 2]
            'logs', //[cite: 2]
            'totalLog', //[cite: 2]
            'totalBerhasil', //[cite: 2]
            'totalGagal', //[cite: 2]
            'totalHariIni' //[cite: 2]
        ));
    }

    // FUNGSI HAPUS DATA LOG SECARA MANUAL
    public function destroyLog($id)
    {
        LogNotifikasi::findOrFail($id)->delete(); //[cite: 2]
        return redirect()->route('log.notifikasi') //[cite: 2]
            ->with('sukses', 'Log riwayat notifikasi berhasil dihapus dari sistem.'); //[cite: 2]
    }

    // ==========================================
    // FUNGSI DASHBOARD (DENGAN FILTER MULTI-ROLE: ADMIN & GURU/WALI KELAS)
    // ==========================================
    public function dashboard(Request $request)
    {
        $user = auth()->user(); //[cite: 2]
        $hariIni = Carbon::today()->toDateString(); //[cite: 2]
        $tahunAktif = TahunAjaran::aktif(); //[cite: 2]

        // Inisialisasi variabel untuk view custom Wali Kelas agar terhindar dari error undefined
        $belumHadirList = collect(); //[cite: 2]
        $riwayatPresensi = collect(); //[cite: 2]

        // 1. FILTER DATA UTAMA BERDASARKAN ROLE USER LOGIN
        if ($user->role == 'guru') { //[cite: 2]
            // kelas yang diampu wali kelas
            $kelasId = $user->kelas_id; //[cite: 2]
            $kelas = Kelas::find($kelasId); //[cite: 2]

            if (!$kelas) { //[cite: 2]
                abort(403, 'Akun belum memiliki kelas.'); //[cite: 2]
            }

            // Ambil info data kelas khusus yang diampu oleh wali kelas tersebut beserta relasi hitungannya
            $daftar_kelas = Kelas::where('id', $kelasId)->withCount([ //[cite: 2]
                'siswa as siswa_count' => function($q) { //[cite: 2]
                    $q->where('status', 'Aktif'); //[cite: 2]
                }, 'siswa as hadir_count' => function($query) use ($hariIni) { //[cite: 2]
                    $query->whereHas('presensi', function($q) use ($hariIni) { //[cite: 2]
                        $q->whereDate('created_at', $hariIni) //[cite: 2]
                          ->whereIn('status', ['Hadir', 'Terlambat']); //[cite: 2]
                    });
                }
            ])->get(); //[cite: 2]

            // Scope data siswa & riwayat log hari ini terfilter sesuai kelas_id Guru
            $semua_siswa = Siswa::where('kelas_id', $kelasId)->where('status', 'Aktif')->get(); //[cite: 2]
            $totalSiswa = $semua_siswa->count(); //[cite: 2]
            
            $riwayat_presensi = Presensi::with(['siswa.kelas']) //[cite: 2]
                ->where('tahun_ajaran_id', $tahunAktif->id) //[cite: 2]
                ->where('kelas_id', $kelasId) //[cite: 2]
                ->whereDate('created_at', $hariIni) //[cite: 2]
                ->orderBy('created_at', 'desc') //[cite: 2]
                ->get(); //[cite: 2]

            // FITUR REKOMENDASI DOSEN: Data spesifik pendukung UI Dashboard Wali Kelas
            $riwayatPresensi = $riwayat_presensi; // Alias camelCase untuk sinkronisasi layout Blade wali kelas[cite: 2]
            
            // Ambil daftar ID siswa di kelas ini yang sudah melakukan presensi hari ini
            $idSiswaSudahPresensi = $riwayat_presensi->pluck('siswa_id')->toArray(); //[cite: 2]
            
            // Dapatkan daftar siswa yang belum hadir hari ini
            $belumHadirList = Siswa::where('kelas_id', $kelasId) //[cite: 2]
                ->where('status', 'Aktif') //[cite: 2]
                ->whereNotIn('id', $idSiswaSudahPresensi) //[cite: 2]
                ->orderBy('nama_siswa', 'asc') //[cite: 2]
                ->get(); //[cite: 2]

        } else {
            // Role Admin: Load keseluruhan data sekolah tanpa filter parameter kelas_id
           $daftar_kelas = Kelas::with('siswa')->get(); //[cite: 2]

           foreach ($daftar_kelas as $kelas) { //[cite: 2]

             $kelas->siswa_count = Siswa::where('kelas_id', $kelas->id) //[cite: 2]
            ->where('status', 'Aktif') //[cite: 2]
            ->count(); //[cite: 2]

               $kelas->hadir_count = Presensi::where('kelas_id', $kelas->id) //[cite: 2]
               ->whereDate('created_at', $hariIni) //[cite: 2]
              ->whereIn('status', ['Hadir','Terlambat']) //[cite: 2]
             ->count(); //[cite: 2]
           }

            $semua_siswa = Siswa::where('status', 'Aktif')->get(); //[cite: 2]
            $totalSiswa = $semua_siswa->count(); //[cite: 2]
            
            $riwayat_presensi = Presensi::with(['siswa.kelas']) //[cite: 2]
                ->where('tahun_ajaran_id', $tahunAktif->id) //[cite: 2]
                ->whereDate('created_at', $hariIni) //[cite: 2]
                ->orderBy('created_at', 'desc') //[cite: 2]
                ->get(); //[cite: 2]
        }

        // 2. KALKULASI COUNTER DATA WIDGET BOX (Sinkron dengan scope data di atas)
        $siswaHadirHariIni = $riwayat_presensi->where('status', 'Hadir')->count(); //[cite: 2]
        $siswaTerlambat    = $riwayat_presensi->where('status', 'Terlambat')->count(); //[cite: 2]
        $siswaSakit        = $riwayat_presensi->where('status', 'Sakit')->count(); //[cite: 2]
        $siswaIzin         = $riwayat_presensi->where('status', 'Izin')->count(); //[cite: 2]
        
        $siswaBelumHadir   = max(0, $totalSiswa - ($siswaHadirHariIni + $siswaTerlambat + $siswaSakit + $siswaIzin)); //[cite: 2]

        /*
        |--------------------------------------------------------------------------
        | GRAFIK MINGGUAN DINAMIS (DENGAN KONDISI DI-FILTER OLEH SCOPE ROLE)
        |--------------------------------------------------------------------------
        |
        */
        $label_grafik_mingguan = []; //[cite: 2]
        $data_grafik_mingguan = []; //[cite: 2]

        $hariSetting = [ //[cite: 2]
            1 => 'senin', 2 => 'selasa', 3 => 'rabu', 4 => 'kamis', 5 => 'jumat', 6 => 'sabtu', 0 => 'minggu' //[cite: 2]
        ]; //[cite: 2]

        // Jumlah hari yang ditampilkan di grafik SEKARANG MENGIKUTI Pengaturan > Hari Aktif,
        // bukan lagi di-hardcode ke angka 5. Kalau admin aktifkan 7 hari, grafik tampil 7 titik;
        // kalau cuma 5 hari yang aktif, grafik tampil 5 titik saja.
        $jumlahHariAktif = collect($hariSetting)->filter(fn($namaHari) => Setting::getValue($namaHari))->count();
        if ($jumlahHariAktif < 1) {
            $jumlahHariAktif = 7; // fallback aman kalau belum ada satupun hari yang diatur aktif
        }

        $today = Carbon::now('Asia/Jakarta'); //[cite: 2]
        $tanggalGrafik = []; //[cite: 2]

        for ($i = 0; $i < 14; $i++) { //[cite: 2]
            $tanggal = $today->copy()->subDays($i); //[cite: 2]
            $indexHari = $tanggal->dayOfWeek; //[cite: 2]
            $namaHari = $hariSetting[$indexHari]; //[cite: 2]

            if (Setting::getValue($namaHari)) { //[cite: 2]
                $tanggalGrafik[] = $tanggal; //[cite: 2]
            } //[cite: 2]

            if (count($tanggalGrafik) == $jumlahHariAktif) { //[cite: 2]
                break; //[cite: 2]
            }
        }

        $tanggalGrafik = array_reverse($tanggalGrafik); //[cite: 2]
        $kumpulanTanggal = collect($tanggalGrafik)->map(fn($t) => $t->toDateString())->toArray(); //[cite: 2]

        // Query Dasar Mingguan
        $queryMingguan = Presensi::where('tahun_ajaran_id', $tahunAktif->id) //[cite: 2]
            ->whereIn('status', ['Hadir', 'Terlambat']) //[cite: 2]
            ->whereIn(DB::raw('DATE(created_at)'), $kumpulanTanggal); //[cite: 2]

        // Sisapkan filter kelas jika yang mengakses adalah Guru
        if ($user->role == 'guru') { //[cite: 2]
            $queryMingguan->where('kelas_id', $user->kelas_id); //[cite: 2]
        }

        $dataPresensi = $queryMingguan->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('count(*) as total')) //[cite: 2]
            ->groupBy('tanggal') //[cite: 2]
            ->pluck('total', 'tanggal'); //[cite: 2]

        foreach ($tanggalGrafik as $tanggal) { //[cite: 2]
            $strTanggal = $tanggal->toDateString(); //[cite: 2]
            $label_grafik_mingguan[] = $tanggal->translatedFormat('D'); //[cite: 2]
            $data_grafik_mingguan[] = $dataPresensi[$strTanggal] ?? 0; //[cite: 2]
        }

        /*
        |--------------------------------------------------------------------------
        | GRAFIK BULANAN (DENGAN KONDISI DI-FILTER OLEH SCOPE ROLE)
        |--------------------------------------------------------------------------
        |
        */
        $bulanIni = Carbon::now(); //[cite: 2]
        $jumlahHariBulanIni = $bulanIni->daysInMonth; //[cite: 2]

        $tanggalMulai = $bulanIni->startOfMonth()->toDateString(); //[cite: 2]
        $tanggalSelesai = $bulanIni->endOfMonth()->toDateString(); //[cite: 2]

        // Query Dasar Bulanan
        $queryBulanan = Presensi::where('tahun_ajaran_id', $tahunAktif->id) //[cite: 2]
            ->whereIn('status', ['Hadir', 'Terlambat']) //[cite: 2]
            ->whereBetween(DB::raw('DATE(created_at)'), [$tanggalMulai, $tanggalSelesai]); //[cite: 2]

        // Sisapkan filter kelas jika yang mengakses adalah Guru
        if ($user->role == 'guru') { //[cite: 2]
            $queryBulanan->where('kelas_id', $user->kelas_id); //[cite: 2]
        }

        $dataPresensiBulanan = $queryBulanan->select(DB::raw('DAY(created_at) as tanggal'), DB::raw('count(*) as total')) //[cite: 2]
            ->groupBy('tanggal') //[cite: 2]
            ->pluck('total', 'tanggal'); //[cite: 2]

        $data_grafik_bulanan = []; //[cite: 2]
        $labels_bulanan = []; //[cite: 2]

        for ($d = 1; $d <= $jumlahHariBulanIni; $d++) { //[cite: 2]
            $data_grafik_bulanan[] = $dataPresensiBulanan[$d] ?? 0; //[cite: 2]
            $labels_bulanan[] = $d; //[cite: 2]
        }

        $log_notifikasi = collect(); //[cite: 2]
        if (request()->routeIs('log.notifikasi')) { //[cite: 2]
            $queryLog = Presensi::with(['siswa.kelas']) //[cite: 2]
                ->whereDate('created_at', $hariIni) //[cite: 2]
                ->orderBy('created_at', 'desc'); //[cite: 2]
                
            if ($user->role == 'guru') { //[cite: 2]
                $queryLog->where('kelas_id', $user->kelas_id); //[cite: 2]
            }
            $log_notifikasi = $queryLog->get(); //[cite: 2]
        }

        // --- BLOK PENAMBAHAN KONDISI DATA HAK AKSES GURU ---
        if($user->role == 'guru'){ //[cite: 2]
            $kelas = $user->kelas; //[cite: 2]
            $totalSiswa = \App\Models\Siswa::where('kelas_id', $kelas->id)->where('status', 'Aktif')->count(); //[cite: 2]
            
            $hadirHariIni = \App\Models\Presensi::whereDate('created_at', today()) //[cite: 2]
                ->where('kelas_id', $kelas->id) //[cite: 2]
                ->whereIn('status', ['Hadir', 'Terlambat']) //[cite: 2]
                ->count(); //[cite: 2]

            $terlambat = \App\Models\Presensi::whereDate('created_at', today()) //[cite: 2]
                ->where('kelas_id', $kelas->id) //[cite: 2]
                ->where('status', 'Terlambat') //[cite: 2]
                ->count(); //[cite: 2]

            $belumHadir = max(0, $totalSiswa - $hadirHariIni); //[cite: 2]
        } else {
            // Nilai default/fallback untuk admin agar variabel tidak error saat di-compact
            $hadirHariIni = $siswaHadirHariIni; //[cite: 2]
            $terlambat = $siswaTerlambat; //[cite: 2]
            $belumHadir = $siswaBelumHadir; //[cite: 2]
        }

        return view('dashboard.index', [ //[cite: 2]
            'daftar_kelas' => $daftar_kelas, //[cite: 2]
            'totalSiswa' => $totalSiswa, //[cite: 2]
            'siswaHadirHariIni' => $siswaHadirHariIni, //[cite: 2]
            'siswaTerlambat' => $siswaTerlambat, //[cite: 2]
            'siswaSakit' => $siswaSakit, //[cite: 2]
            'siswaIzin' => $siswaIzin, //[cite: 2]
            'siswaBelumHadir' => $siswaBelumHadir, //[cite: 2]
            'data_grafik_mingguan' => $data_grafik_mingguan, //[cite: 2]
            'label_grafik_mingguan' => $label_grafik_mingguan, //[cite: 2]
            'data_grafik_bulanan' => $data_grafik_bulanan, //[cite: 2]
            'labels_bulanan' => $labels_bulanan, //[cite: 2]
            'riwayat_presensi' => $riwayat_presensi, //[cite: 2]
            'log_notifikasi' => $log_notifikasi, //[cite: 2]
            // Data tambahan untuk kebutuhan view wali kelas/guru
            'hadirHariIni' => $hadirHariIni, //[cite: 2]
            'terlambat' => $terlambat, //[cite: 2]
            'belumHadir' => $belumHadir, //[cite: 2]
            'belumHadirList' => $belumHadirList, //[cite: 2]
            'riwayatPresensi' => $riwayatPresensi, //[cite: 2]
        ]);
    }

    // ==========================================
    // FUNGSI INPUT MANUAL: DI-FILTER OLEH SCOPE ROLE (GURU vs ADMIN)
    // ==========================================
    public function inputManual(Request $request)
    {
        $user = auth()->user(); //[cite: 2]

        // 2. INPUT MANUAL: Membatasi drop-down agar Wali Kelas hanya melihat data kelas miliknya sendiri
        if ($user->role == 'guru') { //[cite: 2]
            $daftar_kelas = Kelas::where('id', $user->kelas_id)->get(); //[cite: 2]
            $kelas_id = $user->kelas_id; //[cite: 2]
        } else {
            $daftar_kelas = Kelas::orderBy('nama_kelas', 'asc')->get(); //[cite: 2]
            $kelas_id = $request->input('kelas_id'); //[cite: 2]
        }

        $tanggal = $request->input('tanggal', date('Y-m-d')); //[cite: 2]
        $semua_siswa = collect(); //[cite: 2]
        $tahunAktif = TahunAjaran::aktif(); //[cite: 2]

        if ($kelas_id) { //[cite: 2]
            $semua_siswa = Siswa::where('kelas_id', $kelas_id) //[cite: 2]
                ->where('status', 'Aktif') //[cite: 2]
                ->orderBy('nama_siswa') //[cite: 2]
                ->get(); //[cite: 2]
            
            $data_presensi = Presensi::where('tahun_ajaran_id', $tahunAktif->id) //[cite: 2]
                ->where('kelas_id', $kelas_id) //[cite: 2]
                ->whereDate('created_at', $tanggal) //[cite: 2]
                ->get() //[cite: 2]
                ->keyBy('rfid_code'); //[cite: 2]

            foreach ($semua_siswa as $siswa) { //[cite: 2]
                $keyPencarian = !empty($siswa->rfid_code) ? $siswa->rfid_code : 'MANUAL_' . $siswa->id; //[cite: 2]

                if (isset($data_presensi[$keyPencarian])) { //[cite: 2]
                    $siswa->status_hari_ini = $data_presensi[$keyPencarian]->status; //[cite: 2]
                    $siswa->keterangan_hari_ini = $data_presensi[$keyPencarian]->keterangan; //[cite: 2]
                } else {
                    $siswa->status_hari_ini = 'Belum Absen'; //[cite: 2]
                    $siswa->keterangan_hari_ini = ''; //[cite: 2]
                }
            }
        }
        return view('presensi.manual', compact('daftar_kelas', 'semua_siswa', 'kelas_id', 'tanggal')); //[cite: 2]
    }

    // ==========================================
    // FUNGSI SIMPAN INPUT MANUAL: DATA TIDAK AKAN PERNAH LOGKOR/GANDA
    // ==========================================
    public function storeManual(Request $request)
    {
        $tahunAktif = TahunAjaran::aktif(); //[cite: 2]

        $request->validate([ //[cite: 2]
            'tanggal' => 'required|date',  //[cite: 2]
            'presensi' => 'required|array' //[cite: 2]
        ]); //[cite: 2]

        DB::beginTransaction(); //[cite: 2]
        try {
            foreach ($request->presensi as $siswa_id => $data) { //[cite: 2]
                $status = in_array($data['status'], ['Belum Absen', 'Belum Hadir']) ? 'Alpa' : $data['status']; //[cite: 2]
                $siswa = Siswa::find($siswa_id); //[cite: 2]
                if (!$siswa) continue; //[cite: 2]

                $rfidSiswa = !empty($siswa->rfid_code) ? $siswa->rfid_code : 'MANUAL_' . $siswa->id; //[cite: 2]

                $presensiHariIni = Presensi::where('tahun_ajaran_id', $tahunAktif->id) //[cite: 2]
                    ->whereDate('created_at', $request->tanggal) //[cite: 2]
                    ->where('rfid_code', $rfidSiswa) //[cite: 2]
                    ->first(); //[cite: 2]

                if ($presensiHariIni) { //[cite: 2]
                    $presensiHariIni->update([ //[cite: 2]
                        'status' => $status, //[cite: 2]
                        'kelas_id' => $siswa->kelas_id, //[cite: 2]
                        'keterangan' => $data['keterangan'] ?? null, //[cite: 2]
                    ]); //[cite: 2]
                } else {
                    Presensi::create([ //[cite: 2]
                        'rfid_code' => $rfidSiswa, //[cite: 2]
                        'kelas_id' => $siswa->kelas_id, //[cite: 2]
                        'tahun_ajaran_id'  => $tahunAktif->id, //[cite: 2]
                        'status' => $status, //[cite: 2]
                        'keterangan' => $data['keterangan'] ?? null, //[cite: 2]
                        'created_at' => $request->tanggal . ' ' . date('H:i:s'),  //[cite: 2]
                        'waktu_masuk' => $request->tanggal . ' ' . date('H:i:s') //[cite: 2]
                    ]); //[cite: 2]
                }
            }
            
            DB::commit(); //[cite: 2]
            return redirect()->back()->with('sukses', 'Seluruh data presensi manual kelas berhasil diperbarui dan masuk ke rekap laporan bulanan!'); //[cite: 2]
            
        } catch (\Exception $e) {
            DB::rollBack(); //[cite: 2]
            return redirect()->back()->with('error', 'Gagal menyimpan presensi manual: ' . $e->getMessage()); //[cite: 2]
        }
    }

    // ==========================================
    // FUNGSI SCAN RFID VIA WEB (MODERN ULTRA-FAST RESPONSE ENGINE WITH ENQUEUED WA GATWAY)
    // ==========================================
    public function tapRFIDWeb(Request $request, FonnteService $fonnte)
    {
        $request->validate([ //[cite: 2]
            'rfid_code' => 'required|string' //[cite: 2]
        ]); //[cite: 2]

        $rfidCode = trim($request->rfid_code); //[cite: 2]

        // Optimasi Kueri Kolom Terseleksi untuk Response Tercepat
        $siswa = Siswa::select('id', 'nama_siswa', 'kelas_id', 'foto', 'no_hp_orang_tua', 'rfid_code', 'status') //[cite: 2]
            ->with(['kelas' => function($query) { //[cite: 2]
                $query->select('id', 'nama_kelas'); //[cite: 2]
            }]) //[cite: 2]
            ->where('rfid_code', $rfidCode) //[cite: 2]
            ->where('status', 'Aktif') //[cite: 2]
            ->first(); //[cite: 2]
            
        if (!$siswa) { //[cite: 2]
            return response()->json([ //[cite: 2]
                'success' => false, //[cite: 2]
                'message' => 'Gagal Presensi! Kartu RFID (' . $rfidCode . ') tidak terdaftar.' //[cite: 2]
            ], 404); //[cite: 2]
        }

        $sudahPresensi = Presensi::where('rfid_code', $rfidCode) //[cite: 2]
            ->whereDate('created_at', Carbon::today()) //[cite: 2]
            ->exists(); //[cite: 2]
            
        if ($sudahPresensi) { //[cite: 2]
            return response()->json([ //[cite: 2]
                'success' => false, //[cite: 2]
                'message' => 'Info: Ananda ' . $siswa->nama_siswa . ' sudah merekam presensi hari ini.' //[cite: 2]
            ], 400); //[cite: 2]
        }

        $jamSekarang = Carbon::now('Asia/Jakarta')->format('H:i'); //[cite: 2]
        $hari = strtolower(Carbon::now('Asia/Jakarta')->locale('id')->dayName); //[cite: 2]
        $hariAktif = Setting::getValue($hari); //[cite: 2]

        if (!$hariAktif) { //[cite: 2]
            return response()->json([ //[cite: 2]
                'success' => false, //[cite: 2]
                'message' => 'Hari ini presensi dinonaktifkan oleh Administrator.' //[cite: 2]
            ], 403); //[cite: 2]
        }

        $jamMulai   = Setting::getValue('jam_mulai'); //[cite: 2]
        $batasHadir = Setting::getValue('batas_hadir'); //[cite: 2]
        $jamTutup   = Setting::getValue('jam_tutup'); //[cite: 2]

        if ($jamSekarang < $jamMulai) { //[cite: 2]
            return response()->json([ //[cite: 2]
                'success' => false, //[cite: 2]
                'message' => 'Presensi belum dibuka. Silakan kembali pukul ' . $jamMulai //[cite: 2]
            ], 403); //[cite: 2]
        }

        if ($jamSekarang > $jamTutup) { //[cite: 2]
            return response()->json([ //[cite: 2]
                'success' => false, //[cite: 2]
                'message' => 'Presensi telah ditutup pada pukul ' . $jamTutup //[cite: 2]
            ], 403); //[cite: 2]
        }

        $status = ($jamSekarang > $batasHadir) ? 'Terlambat' : 'Hadir'; //[cite: 2]
        $tahunAktif = TahunAjaran::aktif(); //[cite: 2]

        // Simpan Log Absensi ke Database secara Real-Time
        Presensi::create([ //[cite: 2]
            'rfid_code'        => $rfidCode, //[cite: 2]
            'kelas_id'         => $siswa->kelas_id, //[cite: 2]
            'tahun_ajaran_id'  => $tahunAktif?->id, //[cite: 2]
            'waktu_masuk'      => Carbon::now('Asia/Jakarta'), //[cite: 2]
            'status'           => $status //[cite: 2]
        ]); //[cite: 2]

        $pesan = "📢 INFORMASI PRESENSI SISWA\n\n"; //[cite: 2]
        $pesan .= "Yth. Bapak/Ibu Orang Tua/Wali,\n\n"; //[cite: 2]
        $pesan .= "Ananda : {$siswa->nama_siswa}\n"; //[cite: 2]
        $pesan .= "Kelas : " . ($siswa->kelas->nama_kelas ?? '-') . "\n"; //[cite: 2]
        $pesan .= "Tanggal : " . Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') . "\n"; //[cite: 2]
        $pesan .= "Jam Masuk : " . Carbon::now('Asia/Jakarta')->format('H:i') . " WIB\n"; //[cite: 2]
        $pesan .= "Status : {$status}\n\n"; //[cite: 2]
        $pesan .= "Terima kasih.\n"; //[cite: 2]
        $pesan .= "SD Negeri Tengah 03 Jakarta Timur"; //[cite: 2]

        $noHpOrangTua = $siswa->no_hp_orang_tua; //[cite: 2]
        $namaSiswa = $siswa->nama_siswa; //[cite: 2]

        // OPTIMASI ANTI-SPAM LEVEL TINGGI:
        // Cukup beri delay flat 2 detik agar masuk antrean database secara berurutan.
        // Penanganan anti-spam jeda acak dikerjakan di background worker oleh SendWhatsAppNotification.
        SendWhatsAppNotification::dispatch(
            $noHpOrangTua, 
            $pesan, 
            $rfidCode, 
            $namaSiswa, 
            $status
        ); 

        // Kembalikan objek data JSON secara kilat ke Terminal AJAX Pembaca RFID
        return response()->json([ //[cite: 2]
            'success' => true, //[cite: 2]
            'nama'    => $siswa->nama_siswa, //[cite: 2]
            'kelas'   => $siswa->kelas->nama_kelas ?? '-', //[cite: 2]
            'status'  => $status, //[cite: 2]
            'jam'     => Carbon::now('Asia/Jakarta')->format('H:i'), //[cite: 2]
            'foto'    => $siswa->foto //[cite: 2]
        ]); //[cite: 2]
    }

    public function truncateNotifikasi()
{
    // Mengosongkan seluruh data pada tabel log_notifikasis
    DB::table('log_notifikasis')->truncate();

    return redirect()->back()->with('sukses', 'Seluruh riwayat log notifikasi berhasil dibersihkan.');
}

}