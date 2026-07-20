<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil daftar Tahun Ajaran yang benar-benar punya alumni (berdasarkan tahun_ajaran_lulus_id),
        //    bukan menebak dari tanggal update record.
        $daftarTahun = TahunAjaran::whereHas('siswaLulus')
            ->orderByDesc('tanggal_mulai')
            ->get();

        // 2. Tahun ajaran yang sedang aktif saat ini (untuk badge "Tahun Aktif")
        $tahunAktif = TahunAjaran::where('status', 1)->first();

        // 3. Buat query dasar untuk mengambil siswa berstatus Alumni
        $query = Siswa::with(['kelas', 'tahunAjaranLulus'])
            ->where('status', 'Alumni')
            ->orderBy('nama_siswa');

        // 4. Logika filter berdasarkan Tahun Lulus / Angkatan jika dipilih (kini pakai ID Tahun Ajaran asli)
        if ($request->filled('tahun_lulus')) {
            $query->where('tahun_ajaran_lulus_id', $request->tahun_lulus);
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
        $alumni = $query->paginate(15);

        return view('alumni.index', compact('alumni', 'daftarTahun', 'tahunAktif'));
    }
}