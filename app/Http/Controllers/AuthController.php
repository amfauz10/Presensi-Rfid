<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Memproses data login yang dikirim dari form
     */
    public function login_proses(Request $request)
    {
        // 1. Validasi input agar email dan password tidak boleh kosong
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Mengambil data email dan password dari inputan form
        $kredensial = $request->only('email', 'password');

        // 3. Mengecek ke database apakah data tersebut cocok
        if (Auth::attempt($kredensial)) {
            // Jika benar, buat sesi login
            $request->session()->regenerate();
            
            // LANGSUNG DIALIHKAN: Dipaksa langsung masuk ke dashboard utama tanpa mengingat halaman error sebelumnya
            return redirect('/dashboard');
        }

        // 4. JIKA SALAH: Kembali ke halaman login dengan pesan flash session error
        // Ini disesuaikan agar dibaca oleh @if(session('error')) di halaman HTML Anda
        return back()
            ->with('error', 'Alamat email atau kata sandi yang Anda masukkan salah. Silakan coba kembali.')
            ->withInput();
    }

    /**
     * Mengeluarkan admin dari sistem (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}