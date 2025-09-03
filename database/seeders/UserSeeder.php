<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();
        
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@app.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create Operator User
        User::create([
            'name' => 'Operator',
            'email' => 'operator@app.com',
            'password' => Hash::make('operator123'),
            'role' => 'operator',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create Regular User
        User::create([
            'name' => 'User Demo',
            'email' => 'user@app.com',
            'password' => Hash::make('user123'),
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create additional demo users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
    }
}