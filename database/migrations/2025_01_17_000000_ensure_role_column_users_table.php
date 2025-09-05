<?php
// database/migrations/2025_01_16_000000_ensure_role_column_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add role column if it doesn't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['admin', 'operator', 'user'])->default('user')->after('email');
            }
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
            }
        });

        // Update existing users without role to have 'user' role
        DB::table('users')->whereNull('role')->orWhere('role', '')->update(['role' => 'user']);
        
        // Update existing users without status to have 'active' status
        DB::table('users')->whereNull('status')->orWhere('status', '')->update(['status' => 'active']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};