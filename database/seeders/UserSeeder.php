<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan database seeds.
     */
    public function run(): void
    {
        // Admin Default
        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@asi.com',
            'password' => Hash::make('admin123'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Petugas Default
        User::create([
            'nama' => 'Petugas Inventory',
            'email' => 'petugas@asi.com',
            'password' => Hash::make('petugas123'),
            'role' => User::ROLE_PETUGAS,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Sample users untuk testing
        User::create([
            'nama' => 'John Doe',
            'email' => 'john@asi.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_PETUGAS,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'nama' => 'Jane Smith',
            'email' => 'jane@asi.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}