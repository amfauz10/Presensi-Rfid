<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\FonnteService;
use App\Models\LogNotifikasi;

class SendWhatsAppNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Batasi percobaan ulang maksimal 3 kali jika terjadi gangguan koneksi ke Fonnte
    public $tries = 3; 

    protected $noHpOrangTua;
    protected $pesan;
    protected $rfidCode;
    protected $namaSiswa;
    protected $status;
    protected $siswaId;
    protected $presensiId;

    /**
     * Create a new job instance.
     *
     * PERBAIKAN: Parameter $siswaId & $presensiId ditambahkan agar setiap
     * baris log yang dibuat oleh job ini bisa disertai foreign key menuju
     * tabel siswas DAN presensis, bukan hanya salinan nama & rfid_code tanpa
     * relasi sama sekali.
     */
    public function __construct($noHpOrangTua, $pesan, $rfidCode, $namaSiswa, $status, $siswaId = null, $presensiId = null)
    {
        $this->noHpOrangTua = $noHpOrangTua;
        $this->pesan = $pesan;
        $this->rfidCode = $rfidCode;
        $this->namaSiswa = $namaSiswa;
        $this->status = $status;
        $this->siswaId = $siswaId;
        $this->presensiId = $presensiId;
    }

    /**
     * Execute the job.
     */
    public function handle(FonnteService $fonnte)
{
    try {

        if ($this->connection !== 'sync') {
            $jedaAcak = rand(3, 6);
            sleep($jedaAcak);
        }

        
        $response = $fonnte->sendMessage(
            $this->noHpOrangTua,
            $this->pesan
        );

        $berhasil = $response['success'] ?? false;

        $keterangan = $berhasil
            ? json_encode($response)
            : ($response['body']['message'] ?? json_encode($response));

        LogNotifikasi::create([
            'rfid_code'         => $this->rfidCode,
            'siswa_id'          => $this->siswaId,
            'presensi_id'       => $this->presensiId,
            'nama_siswa'        => $this->namaSiswa,
            'no_hp_orang_tua'   => $this->noHpOrangTua,
            'status_presensi'   => $this->status,
            'status_notifikasi' => $berhasil ? 'Berhasil' : 'Gagal',
            'keterangan'        => $keterangan,
        ]);

    } catch (\Exception $e) {

        // Mencatat log jika notifikasi gagal dikirim
        LogNotifikasi::create([
            'rfid_code'         => $this->rfidCode,
            'siswa_id'          => $this->siswaId,
            'presensi_id'       => $this->presensiId,
            'nama_siswa'        => $this->namaSiswa,
            'no_hp_orang_tua'   => $this->noHpOrangTua,
            'status_presensi'   => $this->status,
            'status_notifikasi' => 'Gagal',
            'keterangan'        => $e->getMessage(),
        ]);
    }
}
}