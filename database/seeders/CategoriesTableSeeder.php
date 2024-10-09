<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // Define the categories to seed
        $categories = [
            ['name' => 'Healthy drink'],
            ['name' => 'Kilocntral'],
            ['name' => 'Body product'],
            ['name' => 'Vitamin'],
            ['name' => 'Hygiene'],
            ['name' => 'Makup'],
            ['name' => 'Summer product'],
            ['name' => 'Coffee'],
            ['name' => 'Tea'],
            ['name' => 'Dite Drink'],
            ['name' => 'men'],
            ['name' => 'women'],
            ['name' => 'children'],
            ['name' => 'specail'],
            ['name' => 'general'],
            ['name' => 'massage'],
            ['name' => 'care'],
            ['name' => 'summer_product'],
            ['name' => 'personal'],
            ['name' => 'home'],
            ['name' => 'latest-collection'],
            ['name' => 'special-offers'],
        ];

        // Insert the categories into the database
        DB::table('categories')->insert($categories);
   
    }
}
