<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Halaman Pengaturan Sistem
     */
    public function index()
    {
        // Memastikan key dari database menjadi key array asosiatif di Blade
        $settings = Setting::pluck('value', 'key');

        return view('settings.index', compact('settings'));
    }

    /**
     * Simpan Pengaturan Sistem
     */
    public function update(Request $request)
    {
        // 1. Validasi Input (Tetap mempertahankan semua field penting)
        $request->validate([
            'jam_mulai'       => 'required',
            'batas_hadir'     => 'required',
            'jam_tutup'       => 'required',
            'kepala_sekolah'  => 'required',
            'nip_kepala'      => 'required',

            // Mode Notifikasi WhatsApp
            'wa_mode'         => 'required|in:off,queue,direct',

            // Validasi WhatsApp Gateway (Fleksibel/Nullable)
            'wa_provider'     => 'nullable|string|max:100',
            'wa_admin'        => 'nullable|string|max:30',
            'wa_url'          => 'nullable|string|max:255',
            'wa_token'        => 'nullable|string',
            'pesan_template'  => 'nullable|string|max:1000',
        ], [
            'jam_mulai.required'      => 'Jam mulai presensi wajib diisi.',
            'batas_hadir.required'    => 'Batas hadir wajib diisi.',
            'jam_tutup.required'      => 'Jam penutupan wajib diisi.',
            'kepala_sekolah.required' => 'Nama Kepala Sekolah wajib diisi.',
            'nip_kepala.required'     => 'NIP Kepala Sekolah wajib diisi.',
            'wa_mode.required'        => 'Mode notifikasi WhatsApp wajib dipilih.',
            'wa_mode.in'              => 'Mode notifikasi WhatsApp tidak valid.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validasi Logika & Urutan Jam
        |--------------------------------------------------------------------------
        */
        if ($request->jam_mulai >= $request->batas_hadir) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Jam Mulai Presensi harus lebih kecil daripada Batas Hadir.');
        }

        if ($request->batas_hadir >= $request->jam_tutup) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Batas Hadir harus lebih kecil daripada Jam Penutupan.');
        }

        /*
        |--------------------------------------------------------------------------
        | Proses Penyimpanan ke Database (Menggunakan Setting::setValue)
        |--------------------------------------------------------------------------
        */

        // Simpan Jam Presensi
        Setting::setValue('jam_mulai', $request->jam_mulai);
        Setting::setValue('batas_hadir', $request->batas_hadir);
        Setting::setValue('jam_tutup', $request->jam_tutup);

        // Simpan Mode Notifikasi WhatsApp
        Setting::setValue('wa_mode', $request->wa_mode);

        // Simpan WhatsApp Gateway
        Setting::setValue('wa_provider', $request->wa_provider);
        Setting::setValue('wa_admin', $request->wa_admin);
        Setting::setValue('wa_url', $request->wa_url);
        Setting::setValue('wa_token', $request->wa_token);

        // Simpan Kalimat Pembuka & Penutup Pesan Notifikasi WhatsApp
        // Simpan Template Pesan Notifikasi WhatsApp
        Setting::setValue('pesan_template', $request->pesan_template);

        // Simpan Identitas Sekolah
        Setting::setValue('kepala_sekolah', $request->kepala_sekolah);
        Setting::setValue('nip_kepala', $request->nip_kepala);

        // Simpan Hari Aktif
        Setting::setValue('senin', $request->has('senin') ? 1 : 0);
        Setting::setValue('selasa', $request->has('selasa') ? 1 : 0);
        Setting::setValue('rabu', $request->has('rabu') ? 1 : 0);
        Setting::setValue('kamis', $request->has('kamis') ? 1 : 0);
        Setting::setValue('jumat', $request->has('jumat') ? 1 : 0);
        Setting::setValue('sabtu', $request->has('sabtu') ? 1 : 0);
        Setting::setValue('minggu', $request->has('minggu') ? 1 : 0);

        return redirect()
            ->route('settings.index')
            ->with('sukses', 'Pengaturan Sistem berhasil diperbarui.');
    }
}