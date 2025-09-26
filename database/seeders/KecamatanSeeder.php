<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data kecamatan di Kota Makassar
        $makassarKecamatan = [
            'Mamajang',
            'Makassar',
            'Mariso',
            'Ujung Pandang',
            'Wajo',
            'Bontoala',
            'Tallo',
            'Ujung Tanah',
            'Panakkukang',
            'Manggala',
            'Tamalate',
            'Biringkanaya',
            'Mandai',
            'Metro',
        ];

        // Data kecamatan di Kabupaten Gowa
        $gowaKecamatan = [
            'Sungguminasa',
            'Tompobulu',
            'Tinggimoncong',
            'Parangloe',
            'Bontonompo',
            'Bajeng',
            'Bontomarannu',
            'Somba Opu',
            'Barombong',
            'Pattallassang',
            'Biringbulu',
            'Sinjai',
            'Depok',
            'Pabuaran',
            'Camba',
            'Lau',
            'Batang',
            'Bontosunggu',
        ];

        // Gabungkan semua kecamatan
        $allKecamatan = array_merge($makassarKecamatan, $gowaKecamatan);

        // Masukkan data ke database
        foreach ($allKecamatan as $kecamatan) {
            Kecamatan::firstOrCreate([
                'nama' => $kecamatan
            ]);
        }

        $this->command->info('Berhasil menambahkan ' . count($allKecamatan) . ' kecamatan.');
        $this->command->info('- Kota Makassar: ' . count($makassarKecamatan) . ' kecamatan');
        $this->command->info('- Kabupaten Gowa: ' . count($gowaKecamatan) . ' kecamatan');
    }
}
