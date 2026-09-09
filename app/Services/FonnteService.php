<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;

class FonnteService
{
    protected $provider;
    protected $token;
    protected $baseUrl;

    public function __construct()
    {
        // Ambil konfigurasi dari database (tabel settings), fallback ke .env kalau kosong
        $this->provider = Setting::getValue('wa_provider') ?: 'Fonnte';

        $this->token = Setting::getValue('wa_token')
            ?: config('services.fonnte.token');

        $this->baseUrl = Setting::getValue('wa_url')
            ?: config('services.fonnte.base_url');
    }

    /**
     * Mengirim pesan WhatsApp lewat provider yang sedang aktif di Pengaturan.
     */
    public function sendMessage($target, $message)
    {
        switch (strtolower(trim($this->provider))) {

            /*
            |------------------------------------------------------
            | FONNTE
            | Docs: https://docs.fonnte.com
            | Auth via header "Authorization: {token}"
            |------------------------------------------------------
            */
            case 'fonnte':

                $response = Http::withHeaders([
                    'Authorization' => $this->token,
                ])->asForm()->post($this->baseUrl, [
                    'target'  => $target,
                    'message' => $message,
                ]);

                return [
                    'success' => $response->successful(),
                    'status'  => $response->status(),
                    'body'    => $response->json(),
                ];

            /*
            |------------------------------------------------------
            | WABLAS
            | Docs: https://wablas.com/documentation/api
            | Auth via header "Authorization: {token}"
            | (untuk akun yang memakai secret key, gabungkan
            |  "token.secretkey" pada kolom Token di Pengaturan)
            |------------------------------------------------------
            */
            case 'wablas':

                // Wablas memakai domain unik per akun (mis. https://sby.wablas.com),
                // jadi endpoint lengkapnya = baseUrl + /api/send-message
                $endpoint = rtrim($this->baseUrl, '/') . '/api/send-message';

                $response = Http::withHeaders([
                    'Authorization' => $this->token,
                ])->post($endpoint, [
                    'phone'   => $target,
                    'message' => $message,
                ]);

                return [
                    'success' => $response->successful(),
                    'status'  => $response->status(),
                    'body'    => $response->json(),
                ];

            /*
            |------------------------------------------------------
            | Provider belum didukung
            |------------------------------------------------------
            */
            default:

                return [
                    'success' => false,
                    'status'  => 500,
                    'body'    => [
                        'message' => 'Provider WhatsApp "' . $this->provider . '" belum didukung sistem. Provider yang tersedia saat ini: Fonnte, Wablas.'
                    ]
                ];
        }
    }
}