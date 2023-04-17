<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

        public function run()
        {
          Category::create(['category' => 'Men']);
          Category::create(['category' => 'Women']);
          Category::create(['category' => 'Children']);
        }
}
