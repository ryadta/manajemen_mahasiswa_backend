<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mahasiswa::create([
            'nim' => '12345678',
            'nama' => 'Budi Santoso',
            'jurusan' => 'Teknik Informatika',
            'angkatan' => '2023',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Sudirman No. 123, Jakarta'
        ]);

        Mahasiswa::create([
            'nim' => '87654321',
            'nama' => 'Siti Aminah',
            'jurusan' => 'Sistem Informasi',
            'angkatan' => '2022',
            'no_hp' => '089876543210',
            'alamat' => 'Jl. Thamrin No. 45, Jakarta'
        ]);
    }
}
