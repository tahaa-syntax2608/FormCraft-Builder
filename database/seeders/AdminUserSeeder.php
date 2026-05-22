<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Agar pehle se account exist nahi karta toh create karein
        User::firstOrCreate(
            ['email' => 'admin@formcraft.com'],
            [
                'name' => 'Muhammad Taha',
                'password' => Hash::make('password'),
            ]
        );
    }
}