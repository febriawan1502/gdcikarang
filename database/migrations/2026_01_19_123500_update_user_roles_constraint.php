<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing constraint
        $constraintName = 'users_role_check';
        
        // Cek dulu apakah ada, kalau ada drop
        // Kita pakai cara brute force drop if exists untuk mysql/mariadb biasanya tidak support IF EXISTS di drop constraint secara langsung di versi lama, 
        // tapi di sini kita asumsikan bisa atau kita jalankan query drop biasa.
        // Namun cara paling aman adalah check dulu.
        
        try {
            DB::statement("ALTER TABLE users DROP CONSTRAINT $constraintName");
        } catch (\Exception $e) {
            // Ignore if not exists or if checking failed, we try to overwrite anyway or it might be a different name
            // Tapi biasanya nama default check constraint di laravel/sql create statement manual itu fix.
        }

        // Add new constraint with more roles
        // Roles from View: user, admin, security, guest, petugas
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'petugas', 'guest', 'user', 'security'))");
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE users DROP CONSTRAINT users_role_check");
        } catch (\Exception $e) {}

        // Balik ke constraint sebelumnya (admin, petugas, guest)
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'petugas', 'guest'))");
    }
};
