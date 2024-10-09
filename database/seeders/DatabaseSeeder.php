<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Users;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(CategoriesTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(AdminUserSeeder::class);
        //  Users::factory(10)->create();

        //   Users::factory()->create([
        //       'name' => 'Test User',
        //       'email' => 'test@example.com',
        //   ]);
    }
}
