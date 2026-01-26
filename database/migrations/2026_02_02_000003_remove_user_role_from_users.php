<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'user')->update(['role' => 'petugas']);

        try {
            DB::statement("ALTER TABLE users DROP CONSTRAINT users_role_check");
        } catch (\Exception $e) {
        }

        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'petugas', 'guest', 'security'))");
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE users DROP CONSTRAINT users_role_check");
        } catch (\Exception $e) {
        }

        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'petugas', 'guest', 'user', 'security'))");
    }
};
