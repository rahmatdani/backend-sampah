<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CatatanSampah;
use App\Models\Pengguna;
use App\Models\Kecamatan;

class CatatanSampahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada pengguna dan kecamatan di database
        if (Pengguna::count() == 0 || Kecamatan::count() == 0) {
            $this->call([
                KecamatanSeeder::class,
                PenggunaSeeder::class,
            ]);
        }

        // Ambil semua pengguna dengan role 'user'
        $penggunas = Pengguna::where('role', 'user')->get();

        // Buat catatan sampah untuk setiap pengguna
        foreach ($penggunas as $pengguna) {
            // Pastikan pengguna memiliki kecamatan_id
            if ($pengguna->kecamatan_id) {
                // Buat 3-5 catatan sampah untuk setiap pengguna
                CatatanSampah::factory()
                    ->count(rand(3, 5))
                    ->create([
                        'pengguna_id' => $pengguna->id,
                        'kecamatan_id' => $pengguna->kecamatan_id,
                    ]);
            }
        }

        $this->command->info('Berhasil menambahkan catatan sampah untuk ' . $penggunas->count() . ' pengguna.');
    }
}
