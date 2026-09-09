<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas; // <-- Tambahan: Jangan lupa import model Kelas
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user
     */
    public function index()
    {
        // Tips optimasi: Gunakan with('kelas') agar query lebih ringan saat menampilkan nama kelas di view
        $users = User::with('kelas')
                    ->orderBy('role')
                    ->orderBy('name')
                    ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user (Tahap 4 & 5)
     */
    public function create()
    {
        // Mengambil data kelas diurutkan berdasarkan kolom nama_kelas
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('users.create', compact('kelas'));
    }

    /**
     * Menyimpan user baru (Tahap 5)
     */
    public function store(Request $request)
    {
        // <-- Tambahan: rule kelas_id dasar
        $kelasIdRules = ['nullable', 'exists:kelas,id'];

        // <-- Tambahan: Aturan "1 kelas hanya boleh 1 wali kelas" HANYA berlaku
        // kalau role yang dipilih = guru. Admin tidak wajib unik terhadap kelas_id.
        if ($request->role === 'guru') {
            $kelasIdRules[] = Rule::unique('users', 'kelas_id')
                ->where(fn ($query) => $query->where('role', 'guru'));
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,guru',
            'kelas_id' => $kelasIdRules, // <-- Tambahan Tahap 5 & validasi wali kelas tunggal
            'nip' => 'nullable|string|max:30',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'kelas_id.unique' => 'Kelas ini sudah memiliki wali kelas. Pilih kelas lain atau lepas wali kelas sebelumnya terlebih dahulu.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'kelas_id' => $request->kelas_id, // <-- Tambahan Tahap 5: Simpan kelas_id ke database
            'nip' => $request->nip,
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Form edit user (Tahap 4)
     */
    public function edit(User $user)
    {
        // Mengambil data kelas diurutkan berdasarkan kolom nama_kelas
        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('users.edit', compact('user', 'kelas'));
    }

    /**
     * Update user (Tahap 5 & 6)
     */
    public function update(Request $request, User $user)
    {
        // <-- Tambahan: Cegah user mengubah ROLE akun miliknya sendiri.
        // Alasan: route ini dijaga middleware role:admin yang mengecek role dari database
        // di SETIAP request. Kalau admin mengubah role dirinya sendiri jadi 'guru',
        // request redirect setelah update() akan langsung ditolak middleware tersebut
        // (self-lockout), karena role di database sudah berubah sebelum redirect diproses.
        if (auth()->id() === $user->id && $request->role !== $user->role) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri. Minta admin lain untuk melakukan perubahan ini.');
        }

        // <-- Tambahan: rule kelas_id dasar
        $kelasIdRules = ['nullable', 'exists:kelas,id'];

        // <-- Tambahan: Aturan "1 kelas hanya boleh 1 wali kelas" HANYA berlaku
        // kalau role yang dipilih = guru. User yang sedang diedit dikecualikan (->ignore)
        // supaya dia tidak dianggap "bentrok" dengan dirinya sendiri.
        if ($request->role === 'guru') {
            $kelasIdRules[] = Rule::unique('users', 'kelas_id')
                ->where(fn ($query) => $query->where('role', 'guru'))
                ->ignore($user->id);
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,guru',
            'kelas_id' => $kelasIdRules, // <-- Tambahan Tahap 5 & 6: Validasi kelas_id + wali kelas tunggal saat update
            'nip' => 'nullable|string|max:30',
            'password' => 'nullable|min:8',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'role.required' => 'Role wajib dipilih.',
            'password.min' => 'Password minimal 8 karakter.',
            'kelas_id.unique' => 'Kelas ini sudah memiliki wali kelas. Pilih kelas lain atau lepas wali kelas sebelumnya terlebih dahulu.',
        ]);

        // Menyusun data awal yang akan di-update
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'kelas_id' => $request->kelas_id, // <-- Tambahan Tahap 6: Masukkan kelas_id ke dalam array data
            'nip' => $request->nip,
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        // Proses update data user sekaligus dengan kelas_id baru
        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun berhasil dihapus.');
    }
}