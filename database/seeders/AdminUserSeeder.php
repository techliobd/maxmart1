<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@maxmart.com'],
            [
                'name' => 'MaxMart Admin',
                'email' => 'admin@maxmart.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@maxmart.com'],
            [
                'name' => 'MaxMart Staff',
                'email' => 'staff@maxmart.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
    }
}
