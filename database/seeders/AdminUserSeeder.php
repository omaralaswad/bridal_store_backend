<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First admin user
        DB::table('users')->updateOrInsert(
            ['email' => 'omaralaswad@gmail.com'], // Admin 1's email
            [
                'first_name' => 'omar',
                'last_name' => 'alaswad',
                'age' => 24, // Example age
                'email' => 'omaralaswad@gmail.com',
                'password' => Hash::make('omar2001'), // Admin 1's password
                'role' => 'admin', // Assign the admin role
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Second admin user
        DB::table('users')->updateOrInsert(
            ['email' => 'abd@gmail.com'], // Admin 2's email
            [
                'first_name' => 'abd',
                'last_name' => 'ouzon',
                'age' => 40, // Example age
                'email' => 'abd@gmail.com',
                'password' => Hash::make('112233'), // Admin 2's password
                'role' => 'admin', // Assign the admin role
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
