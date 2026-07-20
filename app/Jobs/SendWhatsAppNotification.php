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

    /**
     * Create a new job instance.
     */
    public function __construct($noHpOrangTua, $pesan, $rfidCode, $namaSiswa, $status)
    {
        $this->noHpOrangTua = $noHpOrangTua;
        $this->pesan = $pesan;
        $this->rfidCode = $rfidCode;
        $this->namaSiswa = $namaSiswa;
        $this->status = $status;
    }

    /**
     * Execute the job.
     */
    public function handle(FonnteService $fonnte)
    {
        try {
            // ==========================================
            // PROTEKSI ANTI-SPAM LEVEL TINGGI (HUMAN-LIKE DELAY)
            // ==========================================
            // Membuat jeda tidur acak antara 4 sampai 8 detik sesaat sebelum mengirim pesan.
            // Cara ini mengelabui sistem AI WhatsApp agar aktivitas kirim pesan terbaca 
            // sebagai ketikan manual manusia, bukan tembakan bot massal yang kaku.
            $jedaAcak = rand(4, 8);
            sleep($jedaAcak);

            // Mengirim pesan WhatsApp di background via Fonnte
            $response = $fonnte->sendMessage($this->noHpOrangTua, $this->pesan);

            // Mencatat log jika notifikasi berhasil dikirim
            LogNotifikasi::create([
                'rfid_code'         => $this->rfidCode,
                'nama_siswa'        => $this->namaSiswa,
                'no_hp_orang_tua'   => $this->noHpOrangTua,
                'status_presensi'   => $this->status,
                'status_notifikasi' => 'Berhasil',
                'keterangan'        => json_encode($response),
            ]);
        } catch (\Exception $e) {
            // Mencatat log jika notifikasi gagal dikirim
            LogNotifikasi::create([
                'rfid_code'         => $this->rfidCode,
                'nama_siswa'        => $this->namaSiswa,
                'no_hp_orang_tua'   => $this->noHpOrangTua,
                'status_presensi'   => $this->status,
                'status_notifikasi' => 'Gagal',
                'keterangan'        => $e->getMessage(),
            ]);
        }
    }
}