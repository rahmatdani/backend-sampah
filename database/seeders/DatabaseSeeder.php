<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menjalankan seeder dalam urutan yang benar
        $this->call([
            KecamatanSeeder::class,
            JenisSampahSeeder::class,
            PenggunaSeeder::class,
            PenelitiSeeder::class,
            CatatanSampahSeeder::class,
        ]);
    }
}
