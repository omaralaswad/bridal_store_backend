<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch category IDs
        $categoryIds = DB::table('categories')->pluck('id');

        // Fetch supplier IDs
        $supplierIds = DB::table('suppliers')->pluck('id');

        // Generate 20 sample products
        $products = [];
        for ($i = 0; $i < 20; $i++) {
            $products[] = [
                'name' => $faker->word,
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 1, 100), // Random price between 1 and 100
                'category_id' => $faker->randomElement($categoryIds),
                'supplier_id' => $faker->randomElement($supplierIds),
                'image' => 'path_to_image_file_' . $i . '.jpg', // Example image path, replace as needed
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert products into the database
        DB::table('products')->insert($products);
    }
}
