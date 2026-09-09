<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        // REVISI DOSEN: Halaman ini diperluas agar tidak hanya menampilkan
        // Alumni, tapi juga siswa berstatus "Tidak Aktif" (mencakup pindah
        // sekolah, meninggal dunia, putus sekolah, dll -- alasan spesifiknya
        // ada di kolom 'alasan_nonaktif'), lewat tab yang bisa dipilih.
        $statusFilter = $request->get('status', 'Alumni');
        if (!in_array($statusFilter, ['Alumni', 'Tidak Aktif'])) {
            $statusFilter = 'Alumni';
        }

        // 1. Ambil daftar Tahun Ajaran yang benar-benar punya alumni (berdasarkan tahun_ajaran_lulus_id),
        //    bukan menebak dari tanggal update record.
        $daftarTahun = TahunAjaran::whereHas('siswaLulus')
            ->orderByDesc('tanggal_mulai')
            ->get();

        // 2. Tahun ajaran yang sedang aktif saat ini (untuk badge "Tahun Aktif")
        $tahunAktif = TahunAjaran::where('status', 1)->first();

        // 3. Buat query dasar untuk mengambil siswa sesuai tab status yang dipilih
        $query = Siswa::with(['kelas', 'tahunAjaranLulus'])
            ->where('status', $statusFilter)
            ->orderBy('nama_siswa');

        // 4. Filter Tahun Lulus / Angkatan hanya relevan untuk tab Alumni
        if ($statusFilter === 'Alumni' && $request->filled('tahun_lulus')) {
            $query->where('tahun_ajaran_lulus_id', $request->tahun_lulus);
        }

        // 4b. Filter alasan (Pindah Sekolah/Meninggal Dunia/dll) hanya relevan untuk tab Tidak Aktif
        if ($statusFilter === 'Tidak Aktif' && $request->filled('alasan')) {
            $query->where('alasan_nonaktif', $request->alasan);
        }

        // 5. Logika filter pencarian keyword nama atau NISN
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_siswa', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%');
            });
        }

        // 6. Gunakan paginate() agar performa halaman tetap gegas dan ringan
        $alumni = $query->paginate(15)->withQueryString();

        // 7. Hitung jumlah masing-masing tab untuk badge angka di navigasi
        $jumlahPerStatus = Siswa::whereIn('status', ['Alumni', 'Tidak Aktif'])
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // 8. Daftar alasan unik yang pernah tercatat, untuk dropdown filter di tab Tidak Aktif
        $daftarAlasan = Siswa::where('status', 'Tidak Aktif')
            ->whereNotNull('alasan_nonaktif')
            ->distinct()
            ->pluck('alasan_nonaktif');

        return view('riwayat.index', compact('alumni', 'daftarTahun', 'tahunAktif', 'statusFilter', 'jumlahPerStatus', 'daftarAlasan'));
    }
}