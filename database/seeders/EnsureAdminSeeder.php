<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EnsureAdminSeeder extends Seeder
{
    public function run()
    {
        $u = User::where('role', 'admin')->first();
        if (!$u) {
            $u = User::create([
                'name' => 'Administrator', 
                'email' => 'admin@pojok.com', 
                'password' => Hash::make('password'), 
                'role' => 'admin'
            ]);
            $this->command->info("Created Admin User: " . $u->email);
        } else {
            $this->command->info("Existing Admin User: " . $u->email);
            // Reset password biar tahu
            $u->update(['password' => Hash::make('password')]);
            $this->command->info("Password reset to: password");
        }
    }
}
