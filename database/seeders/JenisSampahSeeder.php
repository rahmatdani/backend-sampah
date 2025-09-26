<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JenisSampah;

class JenisSampahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisSampah = [
            [
                'nama' => 'Organik',
                'kode' => 'ORG',
                'faktor_konversi' => 0.5,
                'deskripsi' => 'Sampah yang berasal dari bahan alami yang mudah terurai'
            ],
            [
                'nama' => 'Plastik',
                'kode' => 'PLS',
                'faktor_konversi' => 0.04,
                'deskripsi' => 'Sampah yang terbuat dari bahan plastik'
            ],
            [
                'nama' => 'Kertas',
                'kode' => 'KTS',
                'faktor_konversi' => 0.25,
                'deskripsi' => 'Sampah yang terbuat dari bahan kertas'
            ],
            [
                'nama' => 'Logam',
                'kode' => 'LOG',
                'faktor_konversi' => 0.8,
                'deskripsi' => 'Sampah yang terbuat dari bahan logam'
            ],
            [
                'nama' => 'Residu',
                'kode' => 'RSR',
                'faktor_konversi' => 0.3,
                'deskripsi' => 'Sampah sisa yang tidak termasuk kategori lain'
            ],
        ];

        foreach ($jenisSampah as $jenis) {
            JenisSampah::firstOrCreate(
                ['kode' => $jenis['kode']],
                $jenis
            );
        }

        $this->command->info('Berhasil menambahkan ' . count($jenisSampah) . ' jenis sampah.');
    }
}
