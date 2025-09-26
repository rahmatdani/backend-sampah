<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AvatarProfil;

class AvatarProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $avatars = [
            [
                'path' => 'avatars/default_1.png',
            ],
            [
                'path' => 'avatars/default_2.png',
            ],
            [
                'path' => 'avatars/default_3.png',
            ],
            [
                'path' => 'avatars/default_4.png',
            ],
            [
                'path' => 'avatars/default_5.png',
            ],
            [
                'path' => 'avatars/default_6.png',
            ],
            [
                'path' => 'avatars/default_7.png',
            ],
            [
                'path' => 'avatars/default_8.png',
            ],
        ];

        foreach ($avatars as $avatar) {
            AvatarProfil::firstOrCreate(
                ['path' => $avatar['path']],
                $avatar
            );
        }
    }
}
