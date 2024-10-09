<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SuppliersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Insert specific suppliers
        DB::table('suppliers')->insert([
            [
                'name' => 'Omar',
                'email' => 'omar@example.com',
                'phone' => '123-456-7890',
                'address' => '123 Main St, Anytown, USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Abd',
                'email' => 'abd@example.com',
                'phone' => '987-654-3210',
                'address' => '456 Elm St, Anytown, USA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Insert random suppliers
        $numRandomSuppliers = 8; // Adjust this number as needed

        $randomSuppliers = [];
        for ($i = 0; $i < $numRandomSuppliers; $i++) {
            $randomSuppliers[] = [
                'name' => $faker->company,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert random suppliers into the database
        DB::table('suppliers')->insert($randomSuppliers);
    }
}
