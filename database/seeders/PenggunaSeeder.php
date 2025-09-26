<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada kecamatan di database
        if (Kecamatan::count() == 0) {
            $this->call(KecamatanSeeder::class);
        }

        // Ambil beberapa kecamatan untuk digunakan
        $kecamatans = Kecamatan::inRandomOrder()->limit(5)->get();

        // Buat pengguna contoh
        $penggunas = [
            [
                'nama' => 'Ahmad Rifai',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'alamat' => 'Jl. Perintis Kemerdekaan No. 1',
                'kecamatan_id' => $kecamatans->first()->id,
                'points' => 150,
                'streak_days' => 3,
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'email' => 'siti@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'alamat' => 'Jl. Sultan Alauddin No. 25',
                'kecamatan_id' => $kecamatans->skip(1)->first()->id,
                'points' => 200,
                'streak_days' => 5,
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'alamat' => 'Jl. Pettarani No. 10',
                'kecamatan_id' => $kecamatans->skip(2)->first()->id,
                'points' => 75,
                'streak_days' => 1,
            ],
            [
                'nama' => 'Fatimah Azzahra',
                'email' => 'fatimah@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'alamat' => 'Jl. Boulevard No. 5',
                'kecamatan_id' => $kecamatans->skip(3)->first()->id,
                'points' => 300,
                'streak_days' => 7,
            ],
            [
                'nama' => 'Muhammad Ibrahim',
                'email' => 'ibrahim@example.com',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'alamat' => 'Jl. Latimojong No. 33',
                'kecamatan_id' => $kecamatans->skip(4)->first()->id,
                'points' => 125,
                'streak_days' => 2,
            ],
        ];

        foreach ($penggunas as $penggunaData) {
            Pengguna::firstOrCreate(
                ['email' => $penggunaData['email']],
                $penggunaData
            );
        }

        $this->command->info('Berhasil menambahkan ' . count($penggunas) . ' pengguna contoh.');
    }
}
