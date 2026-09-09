<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\DB;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::orderByDesc('id')->get();

        return view('tahunajaran.index', compact('tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:tahun_ajarans,nama',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        TahunAjaran::create([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 0
        ]);

        return back()->with('sukses', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function aktifkan($id)
    {
        TahunAjaran::query()->update([
            'status' => 0
        ]);

        TahunAjaran::where('id', $id)->update([
            'status' => 1
        ]);

        return back()->with('sukses', 'Tahun Ajaran berhasil diaktifkan.');
    }

    public function kenaikan()
{
    $kelas = \App\Models\Kelas::with([
        'siswa' => function ($query) {
            $query->where('status', 'Aktif')
                  ->orderBy('nama_siswa');
        }
    ])->orderBy('nama_kelas')->get();

    $tahunAktif = TahunAjaran::where('status', 1)->first();

    return view('tahunajaran.kenaikan', compact(
        'kelas',
        'tahunAktif'
    ));
}

public function prosesKenaikan(Request $request)
{
    DB::transaction(function () use ($request) {

        $naik = $request->siswa ?? [];

        // Tahun ajaran yang sedang aktif SAAT proses kenaikan/kelulusan ini dijalankan
        $tahunAktif = TahunAjaran::where('status', 1)->first();

        foreach ($naik as $id) {

            $siswa = Siswa::find($id);

            if (!$siswa) {
                continue;
            }

            $kelas = Kelas::find($siswa->kelas_id);

            if (!$kelas) {
                continue;
            }

            $namaKelas = strtoupper($kelas->nama_kelas);

            preg_match('/(\d)([A-Z])/', $namaKelas, $hasil);

            if (!$hasil) {
                continue;
            }

            $tingkat = (int) $hasil[1];
            $rombel  = $hasil[2];

            // Kelas 6 otomatis menjadi Alumni
            if ($tingkat >= 6) {

                $siswa->status = 'Alumni';
                $siswa->tahun_ajaran_lulus_id = $tahunAktif->id ?? null;
                $siswa->save();

                continue;
            }

            $kelasBaru = ($tingkat + 1) . $rombel;

            $kelasTujuan = Kelas::whereRaw('UPPER(nama_kelas)=?', [$kelasBaru])->first();

            if ($kelasTujuan) {

                $siswa->kelas_id = $kelasTujuan->id;
                $siswa->save();

            }

        }

    });

    return redirect()
            ->route('tahunajaran.index')
            ->with('sukses','Proses kenaikan kelas berhasil dilakukan.');
}
public function update(Request $request, $id)
{
    $request->validate([
        'nama'=>'required',
        'tanggal_mulai'=>'required',
        'tanggal_selesai'=>'required',
    ]);

    $tahun = TahunAjaran::findOrFail($id);

    $tahun->update([
        'nama'=>$request->nama,
        'tanggal_mulai'=>$request->tanggal_mulai,
        'tanggal_selesai'=>$request->tanggal_selesai,
    ]);

    return back()->with('sukses','Data berhasil diubah.');
}

public function destroy($id)
{
    $tahun = TahunAjaran::findOrFail($id);

    if($tahun->status){

    return back()->with(

        'error',

        'Tahun aktif tidak boleh dihapus.'

    );

}

    $tahun->delete();

    return back()->with('sukses','Data berhasil dihapus.');
}
}