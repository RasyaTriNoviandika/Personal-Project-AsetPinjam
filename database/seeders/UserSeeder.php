<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        // Create Operator User
        User::firstOrCreate(
            ['email' => 'operator@operator.com'],
            [
                'name' => 'Operator',
                'password' => Hash::make('password'),
                'role' => 'operator',
                'status' => 'active',
            ]
        );

        // Create Regular User
        User::firstOrCreate(
            ['email' => 'user@user.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'role' => 'user',
                'status' => 'active',
            ]
        );

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@admin.com / password');
        $this->command->info('Operator: operator@operator.com / password');
        $this->command->info('User: user@user.com / password');
    }
}