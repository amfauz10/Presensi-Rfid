<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun admin sesuai permintaan Anda
        User::factory()->create([
            'name' => 'Admin SDN Tengah 03',
            'email' => 'admin@gmail.com',
            'password' => '12345', // Mengenkripsi password 12345
        ]);

        // Tetap memanggil KelasSeeder untuk mengisi kelas 1A - 6B
        $this->call([
            KelasSeeder::class,
        ]);
    }
}