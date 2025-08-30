<?php
// database/seeders/UserSeeder.php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;

// class UserSeeder extends Seeder
// {
//     public function run()
//     {
//         User::create([
//             'name' => 'Administrator',
//             'email' => 'admin@example.com',
//             'password' => Hash::make('password'),
//             'email_verified_at' => now(),
//         ]);
//     }
// }
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@app.com',
            'password' => Hash::make('rasya123'),
        ]);
    }
}
