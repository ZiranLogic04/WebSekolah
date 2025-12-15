<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@sekolah.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Guru
        User::updateOrCreate(
            ['email' => 'guru@sekolah.com'],
            [
                'name' => 'Guru Teladan',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );

        // Siswa
        User::updateOrCreate(
            ['email' => 'siswa@sekolah.com'],
            [
                'name' => 'Siswa Berprestasi',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
    }
}
