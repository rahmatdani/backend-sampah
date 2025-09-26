<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Kecamatan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kecamatan>
 */
class KecamatanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kecamatan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar kecamatan di Makassar dan Gowa
        $kecamatans = [
            'Mamajang', 'Makassar', 'Mariso', 'Ujung Pandang', 'Wajo', 'Bontoala', 
            'Tallo', 'Ujung Tanah', 'Panakkukang', 'Manggala', 'Tamalate', 
            'Biringkanaya', 'Mandai', 'Metro', 'Sungguminasa', 'Tompobulu', 
            'Tinggimoncong', 'Parangloe', 'Bontonompo', 'Bajeng', 'Bontomarannu', 
            'Somba Opu', 'Barombong', 'Pattallassang', 'Biringbulu', 'Sinjai', 
            'Depok', 'Pabuaran', 'Camba', 'Lau', 'Batang', 'Bontosunggu'
        ];

        return [
            'nama' => $this->faker->unique()->randomElement($kecamatans),
        ];
    }
}
