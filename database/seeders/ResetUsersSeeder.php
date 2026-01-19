<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Hapus semua user dan buat user baru
     */
    public function run(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus semua user (termasuk soft deleted)
        User::withTrashed()->forceDelete();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Semua user berhasil dihapus.');
        
        // Buat user baru
        $users = [
            [
                'nama' => 'Administrator',
                'email' => 'admin@asi.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'nama' => 'Petugas Gudang',
                'email' => 'petugas@asi.com',
                'password' => Hash::make('petugas123'),
                'role' => 'petugas',
                'is_active' => true,
            ],
            [
                'nama' => 'Satpam',
                'email' => 'satpam@asi.com',
                'password' => Hash::make('satpam123'),
                'role' => 'security',
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
            $this->command->info("User {$userData['email']} berhasil dibuat dengan role {$userData['role']}");
        }

        $this->command->info('');
        $this->command->info('=== DAFTAR LOGIN ===');
        $this->command->info('1. admin@asi.com / admin123 (admin)');
        $this->command->info('2. petugas@asi.com / petugas123 (petugas)');
        $this->command->info('3. satpam@asi.com / satpam123 (security)');
    }
}
