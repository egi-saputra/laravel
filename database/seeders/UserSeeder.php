<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Developer',
            'email' => 'dev@example.com',
            'password' => Hash::make('password'),
            'role' => 'developer',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // User::create([
        //     'name' => 'Guru',
        //     'email' => 'guru@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'guru',
        // ]);

        // User::create([
        //     'name' => 'Staff',
        //     'email' => 'staff@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'staff',
        // ]);

        // User::create([
        //     'name' => 'User',
        //     'email' => 'user@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        // ]);

        // User::create([
        //     'name' => 'Siswa',
        //     'email' => 'siswa@example.com',
        //     'password' => Hash::make('password'),
        //     'role' => 'siswa',
        // ]);
    }
}
