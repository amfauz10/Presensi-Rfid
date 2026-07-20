<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Kelas; 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Services\FonnteService;

class SiswaController extends Controller
{
    /**
     * Menampilkan Halaman Master Data Siswa / Kelas
     */
    public function index()
    {
        // --- BLOK PERUBAHAN FILTER ROLE GURU ---
        $user = auth()->user();

        if ($user->role == 'guru') {
            $kelas = Kelas::findOrFail($user->kelas_id);
            $semua_siswa = Siswa::with('kelas')
                ->where('status', 'Aktif')
                ->where('kelas_id', $user->kelas_id)
                ->orderBy('nama_siswa')
                ->get();

            // Langsung memuat tampilan detail siswa untuk kelas yang diampu guru
            return view('kelas.show', compact('kelas', 'semua_siswa'));
        }
        // ----------------------------------------

        // Kode lama untuk Admin: Menampilkan daftar seluruh kelas
        $daftar_kelas = Kelas::withCount([
            'siswa as siswa_count' => function ($query) {
                $query->where('status', 'Aktif');
            }
        ])
        ->orderBy('nama_kelas')
        ->get();

        return view('kelas.index', compact('daftar_kelas'));
    }

    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);

        $semua_siswa = Siswa::with('kelas')
            ->where('status', 'Aktif')
            ->where('kelas_id', $id)
            ->orderBy('nama_siswa')
            ->get();
        return view('kelas.show', compact('kelas', 'semua_siswa'));
    }

    /**
     * Menampilkan Form Tambah Siswa Baru.
     */
    public function tambah(Request $request)
    {
        $kelasTerpilih = $request->query('kelas_id');
        $daftar_kelas = Kelas::all(); 
        
        return view('siswa.tambah', compact('kelasTerpilih', 'daftar_kelas'));
    }

    /**
     * Menyimpan data siswa baru ke database.
     */
    public function simpan(Request $request)
    {
        $request->validate([
            'rfid_code'       => 'nullable|string|unique:siswas,rfid_code',
            'nisn'            => 'required|string|unique:siswas,nisn',
            'nama_siswa'      => 'required|string|max:255',
            'no_hp_orang_tua' => 'required|string',
            'kelas_id'        => 'required|exists:kelas,id',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'rfid_code.unique'   => 'UID RFID ini sudah terdaftar oleh siswa lain!',
            'nisn.unique'        => 'NISN ini sudah terdaftar!',
            'nama_siswa.required'=> 'Nama siswa tidak boleh kosong!',
            'foto.image'         => 'Berkas harus berupa gambar.',
            'foto.mimes'         => 'Format foto harus jpeg, png, atau jpg.',
            'foto.max'           => 'Ukuran foto maksimal adalah 2 MB.',
        ]);

        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('siswa', 'public');
        }

        $siswa = Siswa::create([
            'rfid_code'       => $request->rfid_code,
            'nisn'            => $request->nisn,
            'nama_siswa'      => $request->nama_siswa,
            'no_hp_orang_tua' => $request->no_hp_orang_tua,
            'kelas_id'        => $request->kelas_id,
            'foto'            => $foto,
            'status'          => 'Aktif',
        ]);

        if (!empty($siswa->no_hp_orang_tua) && $siswa->no_hp_orang_tua != '-') {
            try {
                $this->kirimNotifikasiPendaftaran($siswa);
            } catch (\Exception $e) {
                return redirect()
                    ->route('kelas.show', $request->kelas_id)
                    ->with('sukses', 'Siswa berhasil disimpan, namun notifikasi WA gagal dikirim.');
            }
        }

        return redirect()
            ->route('kelas.show', $request->kelas_id)
            ->with('sukses', 'Data siswa baru berhasil disimpan ke dalam sistem!');
    }

    private function kirimNotifikasiPendaftaran($siswa)
    {
        $isiPesan = "Halo Bapak/Ibu Wali Murid, data pendaftaran sekolah ananda *{$siswa->nama_siswa}* dengan NISN *{$siswa->nisn}* telah berhasil diregistrasikan ke dalam Sistem Presensi RFID Sekolah.";
        return $this->kirimNotifikasiWhatsApp($siswa->no_hp_orang_tua, $isiPesan);
    }

    /**
     * Fungsi untuk ekspor excel data siswa per kelas.
     */
    public function exportExcel(Request $request)
    {
        $kelas_id = $request->query('kelas_id');
        
        $nama_file = 'Data_Siswa';
        if ($kelas_id) {
            $kelas = Kelas::find($kelas_id);
            $nama_file .= '_Kelas_' . ($kelas ? $kelas->nama_kelas : $kelas_id);
        } else {
            $nama_file .= '_Semua_Kelas';
        }
        $nama_file .= '_' . date('Y-m-d') . '.xlsx';

        return Excel::download(new SiswaExport($kelas_id), $nama_file);
    }

    /**
     * Fungsi proses impor data excel/csv dari sekolah
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|file|max:4096',
            'kelas_id'   => 'required|exists:kelas,id',
        ], [
            'file_excel.required' => 'Silakan pilih berkas terlebih dahulu!',
            'file_excel.file'     => 'Format unggahan berkas tidak valid.',
            'file_excel.max'      => 'Ukuran berkas maksimal adalah 4MB.',
        ]);

        try {
            $kelasId = $request->input('kelas_id');
            $file = $request->file('file_excel');
            
            $ekstensi = strtolower($file->getClientOriginalExtension());
            if (!in_array($ekstensi, ['xlsx', 'xls', 'csv'])) {
                return redirect()->back()->with('error', 'Format berkas tidak didukung! Pastikan berkas berformat .xlsx, .xls, atau .csv');
            }

            Excel::import(new SiswaImport($kelasId), $file);
            
            return redirect()
                ->route('kelas.show', $kelasId)
                ->with('sukses', 'Berhasil mengimpor daftar nama siswa dari berkas berkas sekolah!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses berkas: ' . $e->getMessage());
        }
    }

   /**
     * Menampilkan Form Edit Data Siswa / Alumni.
     */
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $daftar_kelas = Kelas::all(); 
        
        // PERBAIKAN: Blok abort(404) dihapus agar data berstatus 'Alumni' bisa tetap dibuka form editnya.
        
        return view('siswa.edit', compact('siswa', 'daftar_kelas'));
    }

    /**
     * Memproses Perubahan Data Siswa / Alumni ke Database.
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        
        // PERBAIKAN: Blok pembatasan alumni dihapus agar perubahan data ijazah/biodata alumni bisa disimpan.

        $request->validate([
            'rfid_code'       => 'nullable|string|unique:siswas,rfid_code,' . $siswa->id,
            'nisn'            => 'required|string|unique:siswas,nisn,' . $siswa->id,
            'nama_siswa'      => 'required|string|max:255',
            'no_hp_orang_tua' => 'required|string', 
            'kelas_id'        => 'required|exists:kelas,id',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'rfid_code.unique'   => 'UID RFID ini sudah digunakan oleh siswa lain!',
            'nisn.unique'        => 'NISN ini sudah digunakan oleh siswa lain!',
            'nama_siswa.required'=> 'Nama siswa tidak boleh kosong!',
            'foto.image'         => 'Berkas harus berupa gambar.',
            'foto.mimes'         => 'Format foto harus jpeg, png, atau jpg.',
            'foto.max'           => 'Ukuran foto maksimal adalah 2 MB.',
        ]);

        $dataUpdate = [
            'rfid_code'       => $request->rfid_code,
            'nisn'            => $request->nisn,
            'nama_siswa'      => $request->nama_siswa,
            'no_hp_orang_tua' => $request->no_hp_orang_tua, 
            'kelas_id'        => $request->kelas_id,
        ];

        if ($request->hasFile('foto')) {
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $dataUpdate['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        $siswa->update($dataUpdate);

        // OOTB REDIRECT HANDLING: 
        // Jika yang diedit adalah alumni, kembalikan ke halaman daftar alumni agar admin tidak bingung
        if ($siswa->status == 'Alumni') {
            return redirect()
                ->route('alumni.index')
                ->with('sukses', 'Data alumni berhasil diperbarui!');
        }

        return redirect()
            ->route('kelas.show', $request->kelas_id)
            ->with('sukses', 'Data siswa berhasil diperbarui!');
    }

    /**
     * Menghapus Data Tunggal Siswa Berserta Log Manualnya
     */
    public function hapus($id)
    {
        $siswa = Siswa::findOrFail($id);
        
        DB::beginTransaction();
        try {
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            $target_hapus = ['MANUAL_' . $siswa->id];
            if (!empty($siswa->rfid_code)) {
                $target_hapus[] = $siswa->rfid_code;
            }
            
            Presensi::whereIn('rfid_code', $target_hapus)->delete();
            $siswa->delete();

            DB::commit();
            return redirect()->back()->with('sukses', 'Data siswa beserta seluruh riwayat presensinya (RFID & Manual) berhasil dihapus bersih!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus Massal Se-Kelas Tanpa Menyisakan Log Manual Yatim Piatu
     */
    public function hapusSemuaMassa(Request $request)
    {
        $kelas_id = $request->input('kelas_id');

        if (!$kelas_id) {
            return redirect()->back()->with('error', 'Gagal memproses tindakan: Parameter ruang kelas tidak valid.');
        }

        DB::beginTransaction();
        try {
            $daftar_siswa = Siswa::where('kelas_id', $kelas_id)
                ->where('status', 'Aktif')
                ->get();

            if ($daftar_siswa->isNotEmpty()) {
                foreach ($daftar_siswa as $siswa) {
                    if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                        Storage::disk('public')->delete($siswa->foto);
                    }
                }

                $rfid_targets = $daftar_siswa->pluck('rfid_code')->filter()->toArray();
                $manual_targets = $daftar_siswa->map(function($s) { return 'MANUAL_' . $s->id; })->toArray();
                $all_targets = array_merge($rfid_targets, $manual_targets);

                Presensi::whereIn('rfid_code', $all_targets)
                    ->orWhere('kelas_id', $kelas_id)
                    ->delete();

                Siswa::where('kelas_id', $kelas_id)
                    ->where('status', 'Aktif')
                    ->delete();

                DB::commit();
                return redirect()->route('kelas.show', $kelas_id)->with('sukses', 'Berhasil mengosongkan seluruh data siswa serta riwayat kehadiran (RFID & Manual) di kelas ini. Grafik dashboard kembali bersih!');
            }

            DB::rollBack();
            return redirect()->route('kelas.show', $kelas_id)->with('error', 'Tidak ada data siswa yang bisa dihapus di kelas ini.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('kelas.show', $kelas_id)->with('error', 'Terjadi kesalahan sistem saat menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Integrasi API WhatsApp Gateway (Fonnte)
     */
    private function kirimNotifikasiWhatsApp($nomor_tujuan, $pesan)
    {
        $token = "TOKEN_FONNTE_ANDA_DISINI"; 

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 10, 
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'target'      => $nomor_tujuan,
                'message'     => $pesan,
                'countryCode' => '62',
            ),
            CURLOPT_HTTPHEADER => array(
                "Authorization: $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}