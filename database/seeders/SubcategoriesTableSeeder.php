<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubcategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $menCategory = Category::where('category', 'Men')->first();
        Subcategory::create(['subcategory' => 'Shoes', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'Pants', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'Shorts', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'watches', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'bags', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'Accessories', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'sport wear', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'jackets', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'hats', 'category_id' => $menCategory->id]);
        Subcategory::create(['subcategory' => 'dress', 'category_id' => $menCategory->id]);


        $womenCategory = Category::where('category', 'Women')->first();
        Subcategory::create(['subcategory' => 'Shoes', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'Pants', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'Shorts', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'watches', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'bags', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'Accessories', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'sport wear', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'jackets', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'hats', 'category_id' => $womenCategory->id]);
        Subcategory::create(['subcategory' => 'dress', 'category_id' => $womenCategory->id]);


        $childrenCategory = Category::where('category', 'Children')->first();
        Subcategory::create(['subcategory' => 'Shoes', 'category_id' => $childrenCategory->id]);
        Subcategory::create(['subcategory' => 'Pants', 'category_id' => $childrenCategory->id]);
        Subcategory::create(['subcategory' => 'Shorts', 'category_id' => $childrenCategory->id]);
        Subcategory::create(['subcategory' => 'watches', 'category_id' => $childrenCategory->id]);
        Subcategory::create(['subcategory' => 'bags', 'category_id' => $childrenCategory->id]);
        Subcategory::create(['subcategory' => 'Accessories', 'category_id' => $childrenCategory->id]);
        Subcategory::create(['subcategory' => 'sport wear', 'category_id' =>$childrenCategory ->id]);
        Subcategory::create(['subcategory' => 'jackets', 'category_id' => $childrenCategory->id]);
        Subcategory::create(['subcategory' => 'hats', 'category_id' => $childrenCategory->id]);
        Subcategory::create(['subcategory' => 'dress', 'category_id' => $childrenCategory->id]);

    }


}
