<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Product_Color;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            ColorsTableSeeder::class,
            UserSeeder::class,
            User2Seeder::class,
            PermissionSeeder::class,
            CitiesTableSeeder::class,
            PlacesTableSeeder::class,

        ]);

        Product::factory(30)->create();
        Product_Color::factory(30)->create();
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
