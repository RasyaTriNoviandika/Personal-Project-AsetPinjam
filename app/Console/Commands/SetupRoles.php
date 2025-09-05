<?php
// app/Console/Commands/SetupRoles.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SetupRoles extends Command
{
    protected $signature = 'roles:setup';
    protected $description = 'Setup role system and create default users';

    public function handle()
    {
        $this->info('Setting up role system...');

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Create Operator User
        $operator = User::firstOrCreate(
            ['email' => 'operator@operator.com'],
            [
                'name' => 'Operator',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'status' => 'active',
            ]
        );

        // Create Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        // Update existing users without role
        $usersWithoutRole = User::whereNull('role')->orWhere('role', '')->get();
        foreach ($usersWithoutRole as $u) {
            $u->update(['role' => 'user', 'status' => 'active']);
            $this->info("Updated user: {$u->email} - assigned 'user' role");
        }

        $this->info('✅ Role system setup completed!');
        $this->info('');
        $this->info('Default users created:');
        $this->table(['Role', 'Email', 'Password'], [
            ['admin', 'admin@admin.com', 'password'],
            ['operator', 'operator@operator.com', 'password'],
            ['user', 'user@user.com', 'password'],
        ]);

        return 0;
    }
}