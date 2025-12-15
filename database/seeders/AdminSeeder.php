<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@diviacode.com'],
            [
                'name' => 'Admin Diviacode',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );
    }
}
