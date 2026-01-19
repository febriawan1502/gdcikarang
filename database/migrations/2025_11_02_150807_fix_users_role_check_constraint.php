<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus constraint lama (kalau ada)
        $constraintName = 'users_role_check';
        $exists = DB::select("SELECT * FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND CONSTRAINT_NAME = ?", [$constraintName]);

        if (!empty($exists)) {
            DB::statement("ALTER TABLE users DROP CONSTRAINT $constraintName");
        }

        // Tambahkan constraint baru dengan 'guest' di dalamnya
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'petugas', 'guest'))");
    }

    public function down(): void
    {
        // Balik ke constraint lama
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'petugas'))");
    }
};
