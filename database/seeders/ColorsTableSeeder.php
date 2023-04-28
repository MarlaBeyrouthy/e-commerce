<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
   /*     DB::table('colors')->insert([
            ['color' => 'red'],
            ['color' => 'blue'],
            ['color' => 'green'],
            ['color' => 'black'],
            ['color' => 'white'],
            ['color' => 'pink'],*/


         $colors = [
             'red',
            'green',
             'blue',
             'black',
             'white',
             'gray',
             'brown',
             'pink',
             'multi-color',
             'other'
         ];

        foreach ($colors as $color) {
            DB::table('colors')->insert(['color' => $color]);
        }



    }
}
