<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenelitiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat pengguna dengan role peneliti
        User::updateOrCreate(
            ['email' => 'peneliti@ecosort.com'],
            [
                'name' => 'Peneliti EcoSort',
                'password' => Hash::make('password'), // Password: password
                'role' => 'peneliti',
            ]
        );

        // Membuat pengguna dengan role admin
        User::updateOrCreate(
            ['email' => 'admin@ecosort.com'],
            [
                'name' => 'Admin EcoSort',
                'password' => Hash::make('password'), // Password: password
                'role' => 'admin',
            ]
        );
    }
}
