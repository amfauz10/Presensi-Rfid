<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Presensi;
use App\Models\Kelas; 
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        // REVISI DOSEN (Poin 3): Dropdown "Rombel / Penempatan Kelas" hanya
        // menampilkan kelas seangkatan dengan kelas siswa saat ini (mis. siswa
        // di "3A" hanya menampilkan "3A" & "3B"), agar admin tidak salah pindah
        // tingkat. Diambil dari huruf/angka depan nama_kelas (mis. "3A" -> "3"),
        // tanpa kolom/tabel tingkat baru.
        $tingkatSaatIni = preg_replace('/[^0-9]/', '', $siswa->kelas->nama_kelas ?? '');
        if ($tingkatSaatIni !== '') {
            $daftar_kelas = Kelas::where('nama_kelas', 'like', $tingkatSaatIni . '%')
                ->orderBy('nama_kelas')
                ->get();
        } else {
            // Fallback: siswa belum punya kelas (data lama/kosong), tampilkan semua
            $daftar_kelas = Kelas::orderBy('nama_kelas')->get();
        }
        
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
            // REVISI DOSEN (Poin 1 & 2 - disederhanakan): status sekarang cuma
            // 3 nilai umbrella: Aktif, Alumni, Tidak Aktif. Alasan spesifik
            // "Tidak Aktif" (pindah/meninggal/putus sekolah/dll) disimpan
            // terpisah di kolom 'alasan_nonaktif' agar tidak menampilkan label
            // sensitif ("Meninggal") secara terang-terangan di banyak tempat UI.
            'status'          => 'nullable|in:Aktif,Tidak Aktif,Alumni',
            'alasan_nonaktif' => 'nullable|required_if:status,Tidak Aktif|string|max:100',
        ], [
            'rfid_code.unique'   => 'UID RFID ini sudah digunakan oleh siswa lain!',
            'nisn.unique'        => 'NISN ini sudah digunakan oleh siswa lain!',
            'nama_siswa.required'=> 'Nama siswa tidak boleh kosong!',
            'foto.image'         => 'Berkas harus berupa gambar.',
            'foto.mimes'         => 'Format foto harus jpeg, png, atau jpg.',
            'foto.max'           => 'Ukuran foto maksimal adalah 2 MB.',
            'status.in'          => 'Status siswa tidak valid.',
            'alasan_nonaktif.required_if' => 'Alasan wajib dipilih ketika status "Tidak Aktif".',
        ]);

        $dataUpdate = [
            'rfid_code'       => $request->rfid_code,
            'nisn'            => $request->nisn,
            'nama_siswa'      => $request->nama_siswa,
            'no_hp_orang_tua' => $request->no_hp_orang_tua, 
            'kelas_id'        => $request->kelas_id,
            'status'          => $request->status ?? $siswa->status,
            // Kosongkan alasan_nonaktif otomatis kalau status bukan "Tidak Aktif"
            // (mis. admin balikin status jadi Aktif lagi), biar data tidak nyangkut.
            'alasan_nonaktif' => $request->status === 'Tidak Aktif' ? $request->alasan_nonaktif : null,
        ];

        if ($request->hasFile('foto')) {
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }
            $dataUpdate['foto'] = $request->file('foto')->store('siswa', 'public');
        }

        $siswa->update($dataUpdate);

        // OOTB REDIRECT HANDLING: 
        // Siswa berstatus Alumni/Tidak Aktif otomatis hilang dari daftar
        // aktif kelas (lihat SiswaController@index & @show yang memfilter
        // where('status','Aktif')), sehingga diarahkan ke halaman "Riwayat
        // Siswa Nonaktif" (tab sesuai statusnya) alih-alih ke kelas yang
        // sudah tidak menampilkannya.
        if (in_array($siswa->status, ['Alumni', 'Tidak Aktif'])) {
            return redirect()
                ->route('alumni.index', ['status' => $siswa->status])
                ->with('sukses', 'Status siswa "' . $siswa->nama_siswa . '" berhasil diperbarui. Data & riwayat presensinya tetap tersimpan.');
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

            // PERBAIKAN: Riwayat presensi dihapus lewat foreign key 'siswa_id'
            // yang sesungguhnya, bukan lagi menyusun daftar rfid_code + kode
            // semu 'MANUAL_<id>'. Constraint FK di database (cascadeOnDelete)
            // sebenarnya sudah otomatis membersihkan baris ini saat siswa
            // dihapus, namun dihapus eksplisit di sini agar pesan sukses tetap
            // akurat dan proses tetap jelas dibaca dalam satu transaksi.
            Presensi::where('siswa_id', $siswa->id)->delete();
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

                // PERBAIKAN: Hapus riwayat presensi lewat 'siswa_id' (FK asli)
                // ditambah 'kelas_id' sebagai jaring pengaman, tanpa perlu lagi
                // menyusun daftar rfid_code + kode semu 'MANUAL_<id>'.
                Presensi::whereIn('siswa_id', $daftar_siswa->pluck('id'))
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
        // PERBAIKAN: Sebelumnya fungsi ini memakai curl manual yang di-hardcode
        // khusus format request Fonnte (field 'target', 'message', 'countryCode').
        // Kalau Admin mengganti provider WA di Pengaturan menjadi Wablas (yang
        // formatnya beda -- field 'phone', endpoint '/api/send-message'), pesan
        // pendaftaran siswa baru ini akan tetap terkirim dengan format Fonnte ke
        // URL Wablas dan gagal, walau notifikasi presensi RFID (yang sudah
        // memakai FonnteService) berjalan normal sesuai provider yang dipilih.
        // Sekarang kedua jalur notifikasi memakai service yang sama, sehingga
        // provider yang dipakai konsisten di seluruh sistem.
        $fonnte = new FonnteService();
        return $fonnte->sendMessage($nomor_tujuan, $pesan);
    }
}