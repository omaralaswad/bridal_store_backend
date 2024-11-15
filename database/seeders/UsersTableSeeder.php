<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Seed admin user
        User::create([
            'first_name' => 'omar',
            'last_name' => 'alaswad',
            'age' => 30,
            'email' => 'o@o.com',
            'password' => Hash::make('112233'), // Encrypt the password
            'role' => 'admin',
        ]);

        // Seed regular user
        User::create([
            'first_name' => 'abd',
            'last_name' => 'abd',
            'age' => 25,
            'email' => 'a@a.com',
            'password' => Hash::make('112233'),
            'role' => 'admin',
        ]);

        // Seed additional users if needed
        User::factory()->count(10)->create();
    }
}