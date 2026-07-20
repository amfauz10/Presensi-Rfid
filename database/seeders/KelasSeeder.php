<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $daftar_kelas = ['1A', '1B', '2A', '2B', '3A', '3B', '4A', '4B', '5A', '5B', '6A', '6B'];

        foreach ($daftar_kelas as $kelas) {
            DB::table('kelas')->insert([
                'nama_kelas' => $kelas,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}