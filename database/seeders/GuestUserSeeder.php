<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GuestUserSeeder extends Seeder
{
    /**
     * Create guest user with view-only access
     */
    public function run(): void
    {
        // Check if guest already exists
        $existingGuest = User::where('email', 'guest')->first();
        
        if ($existingGuest) {
            $this->command->info('User guest sudah ada, melewati...');
            return;
        }
        
        User::create([
            'nama' => 'Guest User',
            'email' => 'guest',
            'password' => Hash::make('guest'),
            'role' => 'guest',
            'is_active' => true,
        ]);

        $this->command->info('User guest berhasil dibuat!');
        $this->command->info('');
        $this->command->info('=== LOGIN GUEST ===');
        $this->command->info('Email: guest');
        $this->command->info('Password: guest');
        $this->command->info('Role: guest (view-only)');
    }
}
