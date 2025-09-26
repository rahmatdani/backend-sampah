<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pengguna;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengguna>
 */
class PenggunaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pengguna::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'), // password
            'role' => 'user',
            'alamat' => $this->faker->address(),
            'kecamatan_id' => Kecamatan::inRandomOrder()->first()->id ?? Kecamatan::factory(),
            'points' => $this->faker->numberBetween(0, 500),
            'streak_days' => $this->faker->numberBetween(0, 10),
            'last_scan_at' => $this->faker->optional()->dateTimeThisMonth(),
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Indicate that the user is a peneliti.
     */
    public function peneliti(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'peneliti',
        ]);
    }
}
