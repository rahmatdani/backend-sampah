<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\JenisSampah;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisSampah>
 */
class JenisSampahFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JenisSampah::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisSampah = [
            ['nama' => 'Organik', 'kode' => 'ORG', 'faktor_konversi' => 0.5],
            ['nama' => 'Plastik', 'kode' => 'PLS', 'faktor_konversi' => 0.04],
            ['nama' => 'Kertas', 'kode' => 'KTS', 'faktor_konversi' => 0.25],
            ['nama' => 'Logam', 'kode' => 'LOG', 'faktor_konversi' => 0.8],
            ['nama' => 'Residu', 'kode' => 'RSR', 'faktor_konversi' => 0.3],
        ];

        $jenis = $this->faker->randomElement($jenisSampah);

        return [
            'nama' => $jenis['nama'],
            'kode' => $jenis['kode'],
            'faktor_konversi' => $jenis['faktor_konversi'],
            'deskripsi' => $this->faker->sentence(),
        ];
    }
}
