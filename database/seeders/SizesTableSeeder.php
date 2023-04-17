<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('sizes')->insert([
            ['size' => 'S'],
            ['size' => 'M'],
            ['size' => 'L'],
            ['size' => 'XL'],
            ['size' => 'All Size'],

            ['size' => '30'],
            ['size' => '31'],
            ['size' => '32'],
            ['size' => '33'],
            ['size' => '34'],
            ['size' => '35'],
            ['size' => '36'],
            ['size' => '37'],
            ['size' => '38'],
            ['size' => '39'],
            ['size' => '40'],
            ['size' => '42'],
            ['size' => '43'],




        ]);

    }
}
